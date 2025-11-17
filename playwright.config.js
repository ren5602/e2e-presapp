import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: './tests/e2e',
  fullyParallel: true,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,
//   workers: process.env.CI ? 1 : undefined,
  workers: 2,
  reporter: 'html',
  timeout: 30 * 1000,
  expect: {
    timeout: 5000,
  },
  use: {
    baseURL: 'http://localhost:8000',
    trace: 'on-first-retry',
    screenshot: 'on',
    video: 'on',
    headless: false,
  },

  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
    // {
    //   name: 'firefox',
    //   use: { ...devices['Desktop Firefox'] },
    // },
    // {
    //   name: 'webkit',
    //   use: { ...devices['Desktop Safari'] },
    // },
  ],

  webServer: process.env.CI ? undefined : {
    command: 'php artisan serve',
    url: 'http://localhost:8000',
    reuseExistingServer: true,
    timeout: 120 * 1000,
  },
});
