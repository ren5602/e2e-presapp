import http from 'k6/http';
import { check, sleep, group } from 'k6';
import { Rate, Trend, Counter } from 'k6/metrics';

/**
 * K6 Load Test - Login with 10 Concurrent Users
 * 
 * Test Configuration:
 * - Virtual Users (VU): 10
 * - Total Duration: 2 minutes
 *   - Ramp-up: 10 seconds (0 → 10 VU)
 *   - Sustained: 80 seconds (10 VU)
 *   - Ramp-down: 30 seconds (10 → 0 VU)
 * - Target URL: https://presma.dbsnetwork.my.id
 * - Credentials: admin / admin123
 * 
 * Purpose: Test browser and server stability under concurrent login load
 * 
 * Run with:
 *   k6 run login-load-test-10users.js --out json=results.json --out csv=results.csv
 */

// Configuration
const BASE_URL = 'https://presma.dbsnetwork.my.id';
const LOGIN_URL = `${BASE_URL}/login`;

// Credentials
const USERNAME = 'admin';
const PASSWORD = 'admin123';

// Custom Metrics
const loginSuccess = new Counter('login_success');
const loginFailure = new Counter('login_failure');
const loginDuration = new Trend('login_duration');
const errorRate = new Rate('errors');

export const options = {
  stages: [
    // Ramp-up: 0 to 10 VU in 10 seconds
    { duration: '10s', target: 10 },
    // Sustained: 10 VU for 80 seconds
    { duration: '80s', target: 10 },
    // Ramp-down: 10 to 0 VU in 30 seconds
    { duration: '30s', target: 0 },
  ],
  thresholds: {
    // Response time threshold: adjusted for production server
    'http_req_duration': ['p(95)<5000', 'p(99)<10000'],
    // Error rate threshold: less than 10% (allowing for network issues)
    'errors': ['rate<0.10'],
    // HTTP errors: less than 10%
    'http_req_failed': ['rate<0.10'],
  },
  // Discard data from the ramp-up and ramp-down phases
  ext: {
    loadimpact: {
      projectID: 3405143,
      name: 'Login Load Test - 10 Users (2 minutes)',
    },
  },
};

/**
 * Extracts CSRF token from HTML response
 * Looks for both meta tag format and input field format
 */
function extractCsrfToken(htmlContent) {
  // Try meta tag format first
  let match = htmlContent.match(/<meta name="csrf-token" content="([^"]+)"/);
  if (match) {
    return match[1];
  }
  
  // Try input field format (name="_token" value="...")
  match = htmlContent.match(/name="_token"\s+value="([^"]+)"/);
  if (match) {
    return match[1];
  }
  
  return null;
}

/**
 * Get CSRF token by visiting login page
 */
function getCsrfToken() {
  try {
    const loginPageRes = http.get(LOGIN_URL, {
      tags: { name: 'GetLoginPage' },
      timeout: '30s',
    });

    if (loginPageRes.status !== 200) {
      console.error(`Failed to fetch login page: ${loginPageRes.status}`);
      errorRate.add(1);
      return null;
    }

    const csrfToken = extractCsrfToken(loginPageRes.body);
    if (!csrfToken) {
      console.error('Failed to extract CSRF token from login page');
      errorRate.add(1);
      return null;
    }

    return csrfToken;
  } catch (error) {
    console.error(`Error getting CSRF token: ${error}`);
    errorRate.add(1);
    return null;
  }
}

/**
 * Login via Web Form (simulating browser login)
 */
function loginViaWebForm(csrfToken) {
  try {
    const loginPayload = {
      username: USERNAME,
      password: PASSWORD,
      _token: csrfToken,
    };

    const loginRes = http.post(LOGIN_URL, loginPayload, {
      tags: { name: 'WebFormLogin' },
      headers: {
        'Referer': LOGIN_URL,
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      redirects: 0,
      timeout: '30s',
    });

    return loginRes;
  } catch (error) {
    console.error(`Error in web form login: ${error}`);
    return null;
  }
}

/**
 * Main test function - executed by each VU
 */
export default function () {
  // Group 1: Get CSRF Token
  let csrfToken;
  group('Get CSRF Token', function () {
    csrfToken = getCsrfToken();
  });

  if (!csrfToken) {
    loginFailure.add(1);
    errorRate.add(1);
    return;
  }

  // Group 2: Web Form Login
  group('Web Form Login', function () {
    const startTime = Date.now();
    const loginRes = loginViaWebForm(csrfToken);
    const duration = Date.now() - startTime;

    loginDuration.add(duration);

    // Handle null/timeout responses
    if (!loginRes) {
      loginFailure.add(1);
      errorRate.add(1);
      return;
    }

    // 302 is expected (redirect after successful login)
    // 200 is also acceptable
    const isSuccess = check(loginRes, {
      'login status is 302 (redirect - success)': (r) => r && r.status === 302,
      'login status is 200 (success)': (r) => r && r.status === 200,
    });

    if (isSuccess) {
      loginSuccess.add(1);
    } else {
      loginFailure.add(1);
      if (loginRes && loginRes.status !== 302 && loginRes.status !== 200) {
        errorRate.add(1);
      }
    }
  });

  // Think time: Wait before next iteration
  sleep(1);
}

/**
 * Teardown: Called after all VUs finish
 */
export function teardown(data) {
  console.log(`
╔════════════════════════════════════════════════════════╗
║           LOAD TEST COMPLETED - SUMMARY                ║
╠════════════════════════════════════════════════════════╣
║ Test Configuration:                                    ║
║   - Virtual Users: 10                                  ║
║   - Duration: 2 minutes (10s ramp-up + 80s + 30s down)║
║   - Target: https://presma.dbsnetwork.my.id           ║
║   - Credentials: admin / admin123                      ║
║                                                        ║
║ Check reports:                                         ║
║   - JSON: results.json                                 ║
║   - CSV: results.csv                                   ║
╚════════════════════════════════════════════════════════╝
  `);
}
