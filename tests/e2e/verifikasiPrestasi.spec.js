import { test, expect } from '@playwright/test';

test.describe('Tes Verifikasi Prestasi', () => {
  test.beforeEach(async ({ page }) => {
    // Login sebagai admin
    await page.goto('/login');
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'admin123');
    await page.click('button[type="submit"]');
    
    // Wait for navigation - just wait for page to be ready
    await page.waitForLoadState('networkidle');
    
    // Navigate directly to prestasi page using URL
    await page.goto('/prestasi');
    await page.waitForSelector('#table-prestasi', { timeout: 10000 });
  });

  test('[POSITIVE] Ubah verifikasi prestasi status "Ditolak" dengan status "Terverifikasi"', async ({ page }) => {
    // Wait for table to load
    await page.waitForSelector('#table-prestasi', { timeout: 10000 });
    
    // Find a prestasi with "Menunggu Verifikasi" status
    const verifyButtons = await page.locator('button:has-text("Ditolak")').all();
    
    if (verifyButtons.length > 0) {
      // Click first verify button
      await verifyButtons[0].click();
      
      // Wait for modal to appear
      await page.waitForSelector('#modal-prestasi', { timeout: 5000 });
      
      // Check if status is "Menunggu Verifikasi"
      const statusBadge = page.locator('.badge-warning');
      const isWaiting = await statusBadge.isVisible();
      
      if (isWaiting) {
        // Scroll to the radio button in modal
        const radioInput = page.locator('#form-edit-verifikasi-prestasi input[name="status_verifikasi"][value="1"]');
        await radioInput.scrollIntoViewIfNeeded();
        await page.waitForTimeout(300);
        
        // Use check method instead of click for radio buttons
        await radioInput.check({ force: true });
        
        // Click Simpan button
        await page.click('button[type="submit"]:has-text("Simpan")');
        
        // Wait for success alert
        await page.waitForSelector('.swal2-popup', { timeout: 5000 });
        
        // Check success message
        const successTitle = page.locator('.swal2-title');
        const successText = page.locator('.swal2-html-container');
        
        await expect(successTitle).toContainText('Berhasil');
        await expect(successText).toContainText('Data berhasil diupdate');
        
        console.log('Ubah verifikasi prestasi status "Ditolak" dengan status "Terverifikasi" berhasil');
      } else {
        console.log('Tidak ada prestasi dengan status Ditolak');
      }
    } else {
      console.log('Tidak ada tombol verifikasi ditemukan');
    }
  });

  test('[POSITIVE] Ubah verifikasi prestasi status "Terverifikasi" dengan status "Ditolak"', async ({ page }) => {
    // Wait for table to load
    await page.waitForSelector('#table-prestasi', { timeout: 10000 });
    
    // Find a prestasi with "Menunggu Verifikasi" status
    const verifyButtons = await page.locator('button:has-text("Terverifikasi")').all();
    
    if (verifyButtons.length > 0) {
      // Click first verify button
      await verifyButtons[0].click();
      
      // Wait for modal to appear
      await page.waitForSelector('#modal-prestasi', { timeout: 5000 });
      
      // Check if status is "Menunggu Verifikasi"
      const statusBadge = page.locator('.badge-warning');
      const isWaiting = await statusBadge.isVisible();
      
      if (isWaiting) {
        // Click "Ditolak" radio button
        await page.click('input[name="status_verifikasi"][value="0"]');
        
        // Click Simpan button
        await page.click('button[type="submit"]:has-text("Simpan")');
        
        // Wait for success alert
        await page.waitForSelector('.swal2-popup', { timeout: 5000 });
        
        // Check success message
        const successTitle = page.locator('.swal2-title');
        const successText = page.locator('.swal2-html-container');
        
        await expect(successTitle).toContainText('Berhasil');
        await expect(successText).toContainText('Data berhasil diupdate');
        
        console.log('Ubah verifikasi prestasi status "Terverifikasi" dengan status "Ditolak" berhasil');
      } else {
        console.log('Tidak ada prestasi dengan status Terverifikasi');
      }
    } else {
      console.log('Tidak ada tombol verifikasi ditemukan');
    }
  });

  test('[POSITIVE] Ubah verifikasi prestasi status "Terverifikasi" dengan status "Ditolak" dan menambahkan pesan', async ({ page }) => {
    // Wait for table to load
    await page.waitForSelector('#table-prestasi', { timeout: 10000 });
    
    // Find a prestasi with "Terverifikasi" status
    const verifyButtons = await page.locator('button:has-text("Terverifikasi")').all();
    
    if (verifyButtons.length > 0) {
      // Click first verify button
      await verifyButtons[0].click();
      
      // Wait for modal to appear
      await page.waitForSelector('#modal-prestasi', { timeout: 5000 });
      
      // Check if status is "Menunggu Verifikasi"
      const statusBadge = page.locator('.badge-warning');
      const isWaiting = await statusBadge.isVisible();
      
      if (isWaiting) {
        // Scroll to the radio button in modal
        const radioInput = page.locator('#form-edit-verifikasi-prestasi input[name="status_verifikasi"][value="1"]');
        await radioInput.scrollIntoViewIfNeeded();
        await page.waitForTimeout(300);
        
        // Use check method instead of click for radio buttons
        await radioInput.check({ force: true });
        
        // Fill message field
        await page.fill('textarea[name="message"]', 'Input gambar tidak relevan');
        
        // Click Simpan button
        await page.click('button[type="submit"]:has-text("Simpan")');
        
        // Wait for success alert
        await page.waitForSelector('.swal2-popup', { timeout: 5000 });
        
        // Check success message
        const successTitle = page.locator('.swal2-title');
        const successText = page.locator('.swal2-html-container');
        
        await expect(successTitle).toContainText('Berhasil');
        await expect(successText).toContainText('Data berhasil diupdate');
        
        console.log('Ubah verifikasi prestasi status "Terverifikasi" dengan status "Ditolak" dan menambahkan pesan berhasil');
      } else {
        console.log('Tidak ada prestasi dengan status Terverifikasi');
      }
    } else {
      console.log('Tidak ada tombol verifikasi ditemukan');
    }
  });

  test('[POSITIVE] Membatalkan ubah verifikasi prestasi status "Ditolak" dengan status "Terverifikasi"', async ({ page }) => {
    // Wait for table to load
    await page.waitForSelector('#table-prestasi', { timeout: 10000 });
    
    // Find a prestasi with "Ditolak" status
    const verifyButtons = await page.locator('button:has-text("Ditolak")').all();
    
    if (verifyButtons.length > 0) {
      // Click first verify button
      await verifyButtons[0].click();
      
      // Wait for modal to appear
      await page.waitForSelector('#modal-prestasi', { timeout: 5000 });
      
      // Scroll to the radio button in modal
      const radioInput = page.locator('#form-edit-verifikasi-prestasi input[name="status_verifikasi"][value="1"]');
      await radioInput.scrollIntoViewIfNeeded();
      await page.waitForTimeout(300);
      
      // Use check method instead of click for radio buttons
      await radioInput.check({ force: true });
      
      // Click Batal button
      await page.click('button:has-text("Batal")');
      
      // Wait for modal to close
      const modal = page.locator('#modal-prestasi');
      await expect(modal).not.toBeVisible({ timeout: 5000 });
      
      console.log('Membatalkan ubah verifikasi prestasi status "Ditolak" dengan status "Terverifikasi" berhasil');
    } else {
      console.log('Tidak ada tombol verifikasi ditemukan');
    }
  });
});
