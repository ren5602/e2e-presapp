# K6 Load Test - Login with 10 Concurrent Users

## Overview

This load test simulates **10 concurrent users** performing login operations against the production environment to assess browser and server stability under load.

**Test Duration:** 2 minutes
- Ramp-up: 10 seconds (0 → 10 VU)
- Sustained: 80 seconds (10 VU)
- Ramp-down: 30 seconds (10 → 0 VU)

**Target:** https://presma.dbsnetwork.my.id
**Credentials:** admin / admin123

---

## Prerequisites

### Install K6

**On Windows (using Chocolatey):**
```powershell
choco install k6
```

**On Windows (using MSI installer):**
Download from https://github.com/grafana/k6/releases and install the latest version.

**Verify installation:**
```powershell
k6 version
```

---

## Running the Load Test

### Basic Execution (with JSON and CSV reports)

```powershell
k6 run non-testing\login-load-test-10users.js --out json=results.json --out csv=results.csv
```

This will:
- Run the load test with 10 concurrent users
- Generate `results.json` for detailed metrics
- Generate `results.csv` for spreadsheet analysis
- Display real-time metrics in the terminal

### With Summary Statistics

```powershell
k6 run non-testing\login-load-test-10users.js --out json=results.json --out csv=results.csv --summary-export=summary.json
```

### Custom VU Count (if needed)

To override the stages configuration and run with different VU counts:

```powershell
k6 run non-testing\login-load-test-10users.js --vus 5 --duration 2m --out json=results_5vu.json
```

---

## Test Scenarios

The load test performs these operations for each virtual user:

### 1. Get CSRF Token
- Fetches the login page
- Extracts the CSRF token from meta tag
- Used for web form submission

### 2. Web Form Login
- Submits username, password, and CSRF token to `/login`
- Validates response status (302 redirect or 200 OK)
- Checks if login is successful

### 3. API Login (Sanctum)
- Sends credentials to `/api/auth/login` endpoint
- Validates response contains authentication token
- Measures response time (should be < 3 seconds)

### 4. Think Time
- 1-second pause between iterations to simulate realistic user behavior

---

## Performance Thresholds

The test validates these criteria for browser/server stability:

| Metric | Threshold | Purpose |
|--------|-----------|----------|
| p95 Response Time | < 5 seconds | 95% of requests complete within 5 seconds |
| p99 Response Time | < 10 seconds | 99% of requests complete within 10 seconds |
| Error Rate | < 10% | Less than 10% of requests should fail |
| HTTP Failures | < 10% | Less than 10% HTTP errors (non-2xx/3xx status) |

---

## Custom Metrics Tracked

- **login_success:** Counter for successful logins
- **login_failure:** Counter for failed logins
- **login_duration:** Trend of API login response times (milliseconds)
- **errors:** Rate of errors throughout the test

---

## Analyzing Results

### View Real-time Output
The terminal displays:
```
     data_received..................: 245 kB    2.0 kB/s
     data_sent.......................: 120 kB    1.0 kB/s
     http_req_blocked...............: avg=1.23ms   min=0.10ms   med=0.45ms   max=12.34ms p(90)=2.34ms p(95)=3.45ms
     http_req_connecting............: avg=0.45ms   min=0ms      med=0.10ms   max=5.67ms  p(90)=1.23ms p(95)=2.34ms
     http_req_duration..............: avg=450ms    min=100ms    med=400ms    max=2300ms  p(90)=800ms  p(95)=1200ms
     http_req_failed................: 2.34%     ✓ rate<0.05
     http_req_receiving.............: avg=10ms     min=5ms      med=8ms      max=50ms    p(90)=15ms   p(95)=20ms
     http_req_sending...............: avg=5ms      min=1ms      med=3ms      max=20ms    p(90)=8ms    p(95)=10ms
     http_req_tls_handshaking.......: avg=0.45ms   min=0ms      med=0.10ms   max=5.67ms  p(90)=1.23ms p(95)=2.34ms
     http_req_waiting...............: avg=435ms    min=95ms     med=390ms    max=2280ms  p(90)=780ms  p(95)=1180ms
     http_reqs.......................: 450      37.5/s
     http_reqs_200..................: 400
     http_reqs_302..................: 40
     http_reqs_500..................: 10
     iteration_duration.............: avg=2.5s     min=2.0s     med=2.4s     max=5.1s    p(90)=2.8s   p(95)=3.2s
     iterations.....................: 450       37.5/s
     login_duration.................: avg=500ms    min=100ms    med=450ms    max=2500ms  p(90)=900ms  p(95)=1300ms
     login_failure..................: 10        0.833/s
     login_success..................: 440       36.667/s
     vus............................: 10
     vus_max.........................: 10
```

### JSON Report (`results.json`)
Contains detailed metrics for each request. Can be imported into InfluxDB or processed with scripts.

### CSV Report (`results.csv`)
Spreadsheet-compatible format for:
- Creating custom charts
- Time-series analysis
- Comparing multiple test runs

---

## Interpreting Results

### Green Checkmarks (✓) = PASS
- Thresholds met - browser/server is stable under 10 concurrent users

### Red X (✗) = FAIL
- Thresholds exceeded - potential performance issues detected

### Key Metrics to Review

1. **p95 Response Time**: 95% of requests complete within this time
   - Goal: < 5 seconds
   - If exceeded: Server may be experiencing latency under load

2. **p99 Response Time**: 99% of requests complete within this time
   - Goal: < 10 seconds
   - If exceeded: Some requests taking significantly longer

3. **Error Rate**: Percentage of failed requests
   - Goal: < 10%
   - If exceeded: Check login endpoint or server logs

4. **HTTP Failures**: Non-successful HTTP status codes
   - Goal: < 10%
   - Review server logs if this exceeds threshold

5. **VU Duration**: Time each virtual user takes to complete one iteration
   - Should be ~2.5 seconds (CSRF fetch + login + 1s think time)

---

## Troubleshooting

### Connection Refused
```
Error: Connection refused - Is the server running?
```
**Solution:** Check if https://presma.dbsnetwork.my.id is accessible and not blocking the requests.

### High Error Rate
```
http_req_failed: 10% ✗ rate<0.05
```
**Possible Causes:**
- Server rate limiting (check for 429 Too Many Requests)
- Credentials invalid (verify admin/admin123 are correct)
- Server overload (reduce VU count and retry)

**Solution:** Check server logs and reduce VU count to identify bottleneck.

### CSRF Token Extraction Failed
```
Failed to extract CSRF token from login page
```
**Solution:** 
- Verify login page structure hasn't changed
- Check if CSRF token meta tag format is different
- Update token extraction regex if needed

### Timeout Errors
```
http_req_duration: exceeded 5s threshold
```
**Solution:**
- Network latency issue
- Server processing slow
- Consider running from closer network location

---

## Advanced: Modifying the Test

### Change VU Count
Edit `login-load-test-10users.js` and modify the stages:
```javascript
stages: [
  { duration: '10s', target: 20 },  // Change 10 to 20
  { duration: '80s', target: 20 },
  { duration: '30s', target: 0 },
]
```

### Add More Actions
After login, add browsing dashboard:
```javascript
group('View Dashboard', function () {
  const dashRes = http.get(`${BASE_URL}/dashboard`, {
    headers: { 'Authorization': `Bearer ${token}` }
  });
  check(dashRes, { 'dashboard status 200': r => r.status === 200 });
});
```

### Use Different Credentials
Edit credentials at the top:
```javascript
const USERNAME = 'NIDN0001';
const PASSWORD = 'dosen123';
```

---

## Expected Results

For a healthy system with 10 concurrent users:
- **Success Rate:** > 90%
- **p95 Response Time:** < 5 seconds
- **p99 Response Time:** < 10 seconds
- **Error Rate:** < 10%
- **HTTP Failures:** < 10%
- **login_success counter:** Should be significantly higher than login_failure

---

## Next Steps

1. **Run the test:** Execute the command above
2. **Review results:** Check terminal output and CSV file
3. **Analyze:** Identify any threshold violations
4. **Report:** Document findings for performance assessment
5. **Iterate:** Adjust VU count if needed to find breaking point

---

## References

- K6 Documentation: https://k6.io/docs/
- K6 API Reference: https://k6.io/docs/javascript-api/
- Laravel Sanctum: https://laravel.com/docs/sanctum
- Load Testing Best Practices: https://k6.io/docs/testing-guides/load-testing/
