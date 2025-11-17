import { test, expect } from '@playwright/test';

test.describe('Tes Login', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login');
  });

  test('[POSITIVE] halaman login ditampilkan dengan benar', async ({ page }) => {
    await expect(page).toHaveTitle(/Login|Log In|Sign In/i);
    await expect(page.locator('input[name="username"], input[type="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"], input[type="password"]')).toBeVisible();
    console.log('Halaman login ditampilkan');
  });

  test('[POSITIVE] login dengan kredensial admin yang valid', async ({ page }) => {
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'admin123');
    await page.click('button[type="submit"]');
    
    // Wait for navigation
    await page.waitForURL(/\/(dashboard|admin|home)/, { timeout: 10000 });
    
    // Verify user is logged in
    const currentUrl = page.url();
    expect(currentUrl).not.toContain('/login');
    console.log('Login dengan kredensial admin yang valid');
  });

  test('[NEGATIVE] tampilkan error dengan kredensial yang tidak valid', async ({ page }) => {
    await page.fill('input[name="username"]', 'invaliduser');
    await page.fill('input[name="password"]', 'wrongpassword');
    await page.click('button[type="submit"]');
    
    // Wait for error message to appear
    const errorMessage = page.locator('[role="alert"], .alert, .error, .text-red');
    await expect(errorMessage).toBeVisible({ timeout: 5000 });
    console.log('Error ditampilkan dengan kredensial tidak valid');
  });

  test('[NEGATIVE] validasi field username harus diisi', async ({ page }) => {
    await page.fill('input[name="password"]', 'password');
    const submitButton = page.locator('button[type="submit"]');
    
    // Check if field is required or try submitting
    const usernameInput = page.locator('input[name="username"]');
    const isRequired = await usernameInput.getAttribute('required');
    
    if (!isRequired) {
      await submitButton.click();
      // Should either show error or stay on login page
      await page.waitForURL(/\/login/, { timeout: 5000 });
    }
    console.log('Field username harus diisi');
  });

  test('[NEGATIVE] validasi field password harus diisi', async ({ page }) => {
    await page.fill('input[name="username"]', 'admin');
    const submitButton = page.locator('button[type="submit"]');
    
    const passwordInput = page.locator('input[name="password"]');
    const isRequired = await passwordInput.getAttribute('required');
    
    if (!isRequired) {
      await submitButton.click();
      await page.waitForURL(/\/login/, { timeout: 5000 });
    }
    console.log('Field password harus diisi');
  });

  test('[POSITIVE] login dengan kredensial mahasiswa yang valid', async ({ page }) => {
    await page.fill('input[name="username"]', '234172001');
    await page.fill('input[name="password"]', 'mahasiswa123');
    await page.click('button[type="submit"]');
    
    // Wait for navigation
    await page.waitForURL(/\/(dashboard|profile|home)/, { timeout: 10000 });
    
    // Verify user is logged in
    const currentUrl = page.url();
    expect(currentUrl).not.toContain('/login');
    console.log('Login dengan kredensial mahasiswa yang valid');
  });

  test('[POSITIVE] login dengan kredensial dosen yang valid', async ({ page }) => {
    await page.fill('input[name="username"]', 'NIDN0001');
    await page.fill('input[name="password"]', 'dosen123');
    await page.click('button[type="submit"]');
    
    // Wait for navigation
    await page.waitForURL(/\/(dashboard|profile|home)/, { timeout: 10000 });
    
    // Verify user is logged in
    const currentUrl = page.url();
    expect(currentUrl).not.toContain('/login');
    console.log('Login dengan kredensial dosen yang valid');
  });
});
