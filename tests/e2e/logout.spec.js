import { expect, test } from '@playwright/test';

async function login(page, username, password) {
  await page.goto('/login');
  await page.fill('input[name="username"], input[type="email"]', username);
  await page.fill('input[name="password"]', password);
  await page.click('button[type="submit"]');
  await page.waitForURL(/\/(dashboard|profile|home)/, { timeout: 15000 });
}

async function openProfileDropdown(page) {
  const trigger = page.locator('a[data-toggle="dropdown"]');
  await trigger.waitFor({ state: 'visible', timeout: 5000 });
  await trigger.click();
  const logoutBtn = page.locator('button:has-text("Logout")').first();
  try {
    await logoutBtn.waitFor({ state: 'visible', timeout: 3000 });
  } catch (e) {
    await trigger.click({ force: true });
    await logoutBtn.waitFor({ state: 'visible', timeout: 5000 });
  }
  return logoutBtn;
}

test.describe('Tes Logout', () => {
  test('[POSITIVE] logout sebagai mahasiswa', async ({ page }) => {
    await login(page, '234172001', 'mahasiswa123');
    const dropdownLogoutBtn = await openProfileDropdown(page);
    await dropdownLogoutBtn.click();
    await expect(page.getByRole('heading', { name: /konfirmasi logout/i })).toBeVisible({ timeout: 10000 });
    await page.locator('#form_logout button:has-text("Logout")').click();
    await page.waitForURL(/\/$|\/login/, { timeout: 10000 });
  });

  test('[POSITIVE] logout sebagai admin', async ({ page }) => {
    await login(page, 'admin', 'admin123');
    const dropdownLogoutBtn = await openProfileDropdown(page);
    await dropdownLogoutBtn.click();
    await expect(page.getByRole('heading', { name: /konfirmasi logout/i })).toBeVisible({ timeout: 10000 });
    await page.locator('#form_logout button:has-text("Logout")').click();
    await page.waitForURL(/\/$|\/login/, { timeout: 10000 });
  });

  test('[POSITIVE] logout sebagai dosen', async ({ page }) => {
    await login(page, 'NIDN0001', 'dosen123');
    const dropdownLogoutBtn = await openProfileDropdown(page);
    await dropdownLogoutBtn.click();
    await expect(page.getByRole('heading', { name: /konfirmasi logout/i })).toBeVisible({ timeout: 10000 });
    await page.locator('#form_logout button:has-text("Logout")').click();
    await page.waitForURL(/\/$|\/login/, { timeout: 10000 });
  });

  test('[NEGATIVE] batal logout (tetap login)', async ({ page }) => {
    await login(page, '234172001', 'mahasiswa123');
    const dropdownLogoutBtn = await openProfileDropdown(page);
    await dropdownLogoutBtn.click();
    await expect(page.getByRole('heading', { name: /konfirmasi logout/i })).toBeVisible({ timeout: 10000 });
    await page.getByRole('button', { name: /batal/i }).click();
    await page.waitForURL(/\/dashboard/, { timeout: 10000 });
  });

  test('[NEGATIVE] akses /logout tanpa login harus redirect ke /login', async ({ page }) => {
    await page.goto('/logout');
    await page.waitForURL(/\/login/, { timeout: 10000 });
    await expect(page.getByRole('button', { name: /Masuk/i })).toBeVisible();
  });
});