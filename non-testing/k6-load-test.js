import http from 'k6/http';
import { check, group, sleep } from 'k6';
import { Rate, Counter } from 'k6/metrics';

// Custom metrics
const errorRate = new Rate('errors');
const loginCounter = new Counter('login_attempts');
const pageCounter = new Counter('pages_visited');

// Configuration
const BASE_URL = 'https://presma.dbsnetwork.my.id';
const CREDENTIALS = {
  username: 'admin',
  password: 'admin123'
};

// Test options with stages (ramp-up, sustained, ramp-down)
export const options = {
  stages: [
    // Ramp-up: 0 → 5 VU in 30 seconds
    { duration: '30s', target: 5 },
    // Sustained load: maintain 5 VU for 5 minutes
    { duration: '5m', target: 5 },
    // Ramp-down: 5 → 0 VU in 30 seconds
    { duration: '30s', target: 0 }
  ],
  thresholds: {
    // p95 response time must be below 5 seconds
    'http_req_duration': ['p(95)<5000'],
    // Error rate must be below 5%
    'errors': ['rate<0.05']
  },
  summaryTrendStats: ['avg', 'min', 'med', 'max', 'p(95)', 'p(99)', 'count'],
};

/**
 * Login through web form (simulating browser behavior)
 */
function loginWebForm() {
  return group('Login Web Form', () => {
    try {
      // First, GET the login page to get CSRF token and session
      const loginPageRes = http.get(`${BASE_URL}/login`, {
        tags: { name: 'LoginPage' }
      });

      loginCounter.add(1);

      let success = check(loginPageRes, {
        'login page loaded': (r) => r.status === 200,
        'login page contains form': (r) => r.body.includes('username') || r.body.includes('password')
      });

      if (!success) {
        errorRate.add(1);
        return null;
      }

      // Extract CSRF token if present (Laravel/most frameworks)
      let csrfToken = '';
      const csrfMatch = loginPageRes.body.match(/name="_token"\s+value="([^"]+)"/);
      if (csrfMatch) {
        csrfToken = csrfMatch[1];
      }

      // POST login form
      const payload = {
        username: CREDENTIALS.username,
        password: CREDENTIALS.password
      };

      if (csrfToken) {
        payload._token = csrfToken;
      }

      const loginRes = http.post(`${BASE_URL}/login`, payload, {
        headers: {
          'Referer': `${BASE_URL}/login`,
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        tags: { name: 'LoginSubmit' },
        redirects: 0 // Don't follow redirects automatically to see redirect status
      });

      success = check(loginRes, {
        'login submit received': (r) => r.status === 200 || r.status === 302 || r.status === 303,
        'no login error': (r) => !r.body.includes('invalid') && !r.body.includes('Invalid')
      });

      if (!success) {
        errorRate.add(1);
        console.error(`Login failed with status ${loginRes.status}`);
        return null;
      }

      errorRate.add(0);
      return true; // Simulated successful login
    } catch (error) {
      errorRate.add(1);
      console.error(`Login error: ${error}`);
      return null;
    }
  });
}

/**
 * Browse dashboard page
 */
function browseDashboard() {
  return group('Browse Dashboard', () => {
    try {
      const dashboardRes = http.get(`${BASE_URL}/dashboard`, {
        tags: { name: 'Dashboard' }
      });

      pageCounter.add(1);

      const success = check(dashboardRes, {
        'dashboard page status 200': (r) => r.status === 200,
        'dashboard page loaded': (r) => r.body.length > 0
      });

      if (!success) {
        errorRate.add(1);
      } else {
        errorRate.add(0);
      }

      return success;
    } catch (error) {
      errorRate.add(1);
      console.error(`Dashboard error: ${error}`);
      return false;
    }
  });
}

/**
 * Browse prestasi page
 */
function browsePrestasi() {
  return group('Browse Prestasi', () => {
    try {
      const prestasiRes = http.get(`${BASE_URL}/prestasi`, {
        tags: { name: 'Prestasi' }
      });

      pageCounter.add(1);

      const success = check(prestasiRes, {
        'prestasi page status 200': (r) => r.status === 200,
        'prestasi page loaded': (r) => r.body.length > 0
      });

      if (!success) {
        errorRate.add(1);
      } else {
        errorRate.add(0);
      }

      return success;
    } catch (error) {
      errorRate.add(1);
      console.error(`Prestasi error: ${error}`);
      return false;
    }
  });
}

/**
 * Main test function - simulates user login and browsing
 */
export default function () {
  // Login through web form
  const loginSuccess = loginWebForm();

  if (loginSuccess) {
    // Random think time (1-2 seconds)
    sleep(__VU % 2 === 0 ? 1 : 2);

    // Browse dashboard
    browseDashboard();

    // Random think time
    sleep(__VU % 2 === 0 ? 1 : 2);

    // Browse prestasi
    browsePrestasi();
  }

  // Random think time before next iteration
  sleep(__VU % 2 === 0 ? 1 : 2);
}

/**
 * Test summary handler
 */
export function handleSummary(data) {
  return {
    'stdout': JSON.stringify(data, null, 2)
  };
}
