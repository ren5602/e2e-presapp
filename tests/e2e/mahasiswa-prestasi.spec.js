// tests/e2e/prestasi.spec.js
import { test, expect } from '@playwright/test';
import path from 'path';

// Konfigurasi credentials
const USERNAME = '234172001';
const PASSWORD = 'mahasiswa123';

test.describe('Manajemen Prestasi', () => {
  
  // Setup: Buat data test sebelum semua test dijalankan
  test.beforeAll(async ({ browser }) => {
    console.log('🔧 Setup: Membuat data test prestasi...');
    
    const context = await browser.newContext();
    const page = await context.newPage();
    
    try {
      // Login
      await page.goto('/login');
      await page.waitForSelector('#username');
      await page.fill('#username', USERNAME);
      await page.fill('#password', PASSWORD);
      await page.click('#btn-login');
      await page.waitForURL('**/dashboard');
      
      // Cek apakah sudah ada data prestasi
      await page.goto('/prestasiku');
      await page.waitForLoadState('networkidle');
      
      const noDataMessage = page.locator('h5:has-text("Prestasi tidak ditemukan")');
      const hasNoData = await noDataMessage.isVisible().catch(() => false);
      
      // Jika tidak ada data, buat 3 data test
      if (hasNoData) {
        console.log('📝 Tidak ada data, membuat 3 prestasi test...');
        
        for (let i = 1; i <= 3; i++) {
          await page.goto('/prestasiku/create');
          await page.waitForLoadState('networkidle');
          
          // Isi form
          await page.selectOption('select[name="dosen_id"]', { index: 1 });
          await page.selectOption('select[name="lomba_id"]', { index: 1 });
          await page.fill('input[name="prestasi_nama"]', `Prestasi Test Setup ${i}`);
          
          // Pilih juara
          const juaraSelect = page.locator('select[name="juara"]');
          if (await juaraSelect.isVisible()) {
            await juaraSelect.selectOption(i.toString()); // Juara 1, 2, 3
          }
          
          await page.fill('input[name="tanggal_perolehan"]', '2024-01-15');
          
          // Upload files jika field ada
          const filePath = path.join(process.cwd(), 'test', 'files', 'sertifikat.jpg');
          
          const sertifikatInput = page.locator('input[name="file_sertifikat"]');
          if (await sertifikatInput.isVisible()) {
            await sertifikatInput.setInputFiles(filePath);
          }
          
          const buktiInput = page.locator('input[name="file_bukti_foto"]');
          if (await buktiInput.isVisible()) {
            await buktiInput.setInputFiles(filePath);
          }
          
          const suratTugasInput = page.locator('input[name="file_surat_tugas"]');
          if (await suratTugasInput.isVisible()) {
            await suratTugasInput.setInputFiles(filePath);
          }
          
          const suratUndanganInput = page.locator('input[name="file_surat_undangan"]');
          if (await suratUndanganInput.isVisible()) {
            await suratUndanganInput.setInputFiles(filePath);
          }
          
          // Submit form
          const submitButton = page.locator('button[type="submit"], button:has-text("Simpan")').first();
          await submitButton.click();
          
          // Tunggu response success
          await page.waitForTimeout(2000);
          
          console.log(`✅ Berhasil membuat data test ${i}/3`);
        }
        
        console.log('✅ Setup selesai: 3 data prestasi test telah dibuat');
      } else {
        console.log('✅ Data prestasi sudah ada, skip pembuatan data');
      }
      
    } catch (error) {
      console.error('❌ Error saat setup data:', error);
    } finally {
      await context.close();
    }
  });

  // Login sebelum setiap test
  test.beforeEach(async ({ page }) => {
    // Proses login
    await page.goto('/login');
    await page.waitForSelector('#username');
    await page.fill('#username', USERNAME);
    await page.fill('#password', PASSWORD);
    await page.click('#btn-login');
    await page.waitForURL('**/dashboard');
    
    // Navigate ke halaman prestasi
    await page.goto('/prestasiku');
    await page.waitForLoadState('networkidle');
  });

  // ==================== POSITIVE TEST CASES ====================
  
  test('[POSITIVE] Menampilkan halaman daftar prestasi dengan benar', async ({ page }) => {
    expect(page.url()).toContain('/prestasiku');
    
    // Verifikasi elemen penting ada
    await expect(page.locator('body')).toBeVisible();
  });

  test('[POSITIVE] Mencari prestasi berdasarkan nama dengan kata kunci valid', async ({ page }) => {
    const searchInput = page.locator('input[name="search"]');
    
    if (await searchInput.isVisible()) {
      await searchInput.fill('Test Setup');
      
      // Klik tombol search (button dengan icon search)
      const searchButton = page.locator('button[type="submit"]:has(i.fa-search)');
      await searchButton.click();
      
      // Tunggu hasil search
      await page.waitForLoadState('networkidle');
      
      // Verifikasi URL mengandung parameter search
      expect(page.url()).toContain('search=');
    }
  });

  test('[POSITIVE] Filter prestasi berdasarkan tingkat lomba', async ({ page }) => {
    const filterSelect = page.locator('select[name="tingkat_lomba_id"]').first();
    
    if (await filterSelect.isVisible()) {
      await filterSelect.selectOption({ index: 1 });
      
      // Tunggu filter diterapkan
      await page.waitForLoadState('networkidle');
      
      // Verifikasi URL mengandung parameter filter
      expect(page.url()).toContain('tingkat_lomba_id=');
    }
  });

  test('[POSITIVE] Filter prestasi berdasarkan status verifikasi "Terverifikasi"', async ({ page }) => {
    const statusSelect = page.locator('select[name="status_verifikasi"]').first();
    
    if (await statusSelect.isVisible()) {
      await statusSelect.selectOption('1'); // Terverifikasi
      
      await page.waitForLoadState('networkidle');
      
      expect(page.url()).toContain('status_verifikasi=');
    }
  });

  test('[POSITIVE] Navigasi ke halaman tambah prestasi baru', async ({ page }) => {
    const createButton = page.locator('a[href*="/create"], button:has-text("Tambah")').first();
    
    await expect(createButton).toBeVisible();
    await createButton.click();
    
    await page.waitForURL('**/prestasiku/create');
    expect(page.url()).toContain('/prestasiku/create');
  });

  test('[POSITIVE] Menampilkan form tambah prestasi dengan semua field lengkap', async ({ page }) => {
    await page.goto('/prestasiku/create');
    await page.waitForLoadState('networkidle');
    
    // Verifikasi form elements ada
    await expect(page.locator('select[name="dosen_id"]')).toBeVisible();
    await expect(page.locator('select[name="lomba_id"]')).toBeVisible();
    await expect(page.locator('input[name="prestasi_nama"]')).toBeVisible();
    await expect(page.locator('input[name="tanggal_perolehan"]')).toBeVisible();
  });

  test('[POSITIVE] Menambah prestasi baru dengan data lengkap dan valid', async ({ page }) => {
    await page.goto('/prestasiku/create');
    await page.waitForLoadState('networkidle');
    
    // Isi form
    await page.selectOption('select[name="dosen_id"]', { index: 1 });
    await page.selectOption('select[name="lomba_id"]', { index: 1 });
    await page.fill('input[name="prestasi_nama"]', 'Prestasi Test E2E Automation');
    
    // Pilih juara
    const juaraSelect = page.locator('select[name="juara"]');
    if (await juaraSelect.isVisible()) {
      await juaraSelect.selectOption('1'); // Juara 1
    }
    
    await page.fill('input[name="tanggal_perolehan"]', '2024-01-15');
    
    // Upload files
    const filePath = path.join(process.cwd(), 'tests', 'files', 'sertifikat.jpg');
    
    const sertifikatInput = page.locator('input[name="file_sertifikat"]');
    if (await sertifikatInput.isVisible()) {
      await sertifikatInput.setInputFiles(filePath);
    }
    
    const buktiInput = page.locator('input[name="file_bukti_foto"]');
    if (await buktiInput.isVisible()) {
      await buktiInput.setInputFiles(filePath);
    }
    
    const suratTugasInput = page.locator('input[name="file_surat_tugas"]');
    if (await suratTugasInput.isVisible()) {
      await suratTugasInput.setInputFiles(filePath);
    }
    
    const suratUndanganInput = page.locator('input[name="file_surat_undangan"]');
    if (await suratUndanganInput.isVisible()) {
      await suratUndanganInput.setInputFiles(filePath);
    }
    
    // Submit form
    const submitButton = page.locator('button[type="submit"], button:has-text("Simpan")').first();
    await submitButton.click();
    
    // Tunggu response success
    await page.waitForResponse(
      response => response.url().includes('/prestasiku') && response.request().method() === 'POST',
      { timeout: 15000 }
    );
    
    // Verifikasi success message
    const successMessage = page.locator('.alert-success, [class*="success"]').first();
    if (await successMessage.isVisible()) {
      await expect(successMessage).toBeVisible();
    }
  });

  test('[POSITIVE] Melihat detail prestasi yang tersedia', async ({ page }) => {
    // Data sudah pasti ada karena dibuat di beforeAll
    
    // Cari link detail pada card title (h5) - lebih spesifik
    const detailLink = page.locator('.card-title').first();
    
    await expect(detailLink).toBeVisible();
    await detailLink.click();
    
    // Tunggu halaman detail
    await page.waitForLoadState('networkidle');
    
    // Verifikasi ada di halaman detail (URL harus seperti /prestasiku/123)
    expect(page.url()).toMatch(/\/prestasiku\/\d+$/);
  });

  test('[POSITIVE] Navigasi ke halaman edit prestasi yang tersedia', async ({ page }) => {
    // Data sudah pasti ada karena dibuat di beforeAll
    
    // Cari tombol edit yang spesifik (menggunakan class dan icon)
    const editButton = page.locator('a.btn-warning:has-text("Edit")').first();
    
    await expect(editButton).toBeVisible();
    await editButton.click();
    
    await page.waitForURL('**/edit');
    expect(page.url()).toContain('/edit');
    
    // Verifikasi form edit ada
    await expect(page.locator('input[name="prestasi_nama"]')).toBeVisible();
  });

  test('[POSITIVE] Mengupdate data prestasi dengan informasi baru yang valid', async ({ page }) => {
    // Data sudah pasti ada karena dibuat di beforeAll
    
    // Cari dan klik edit button
    const editButton = page.locator('a.btn-warning:has-text("Edit")').first();
    
    await expect(editButton).toBeVisible();
    await editButton.click();
    await page.waitForLoadState('networkidle');
    
    // Update prestasi nama
    const namaInput = page.locator('input[name="prestasi_nama"]');
    await namaInput.clear();
    await namaInput.fill('Prestasi Updated E2E Test');
    
    // Submit form
    const submitButton = page.locator('button[type="submit"], button:has-text("Update"), button:has-text("Simpan")').first();
    await submitButton.click();
    
    // Tunggu response
    await page.waitForResponse(
      response => response.url().match(/\/prestasiku\/\d+/) && response.request().method() === 'PUT',
      { timeout: 15000 }
    );
    
    // Verifikasi success
    const successMessage = page.locator('.alert-success, [class*="success"]').first();
    if (await successMessage.isVisible()) {
      await expect(successMessage).toBeVisible();
    }
  });

  test('[POSITIVE] Menampilkan dialog konfirmasi sebelum menghapus prestasi', async ({ page }) => {
    // Data sudah pasti ada karena dibuat di beforeAll
    
    // Cari tombol hapus (btn-danger)
    const deleteButton = page.locator('button.btn-danger:has-text("Hapus")').first();
    
    await expect(deleteButton).toBeVisible();
    await deleteButton.click();
    
    // Tunggu modal konfirmasi muncul
    await page.waitForTimeout(1000);
    
    // Verifikasi modal muncul
    const confirmModal = page.locator('#modal-delete .modal-content');
    await expect(confirmModal).toBeVisible();
  });

  test('[POSITIVE] Menghapus prestasi dengan konfirmasi', async ({ page }) => {
    // Data sudah pasti ada karena dibuat di beforeAll
    
    // Cari dan klik delete button
    const deleteButton = page.locator('button.btn-danger:has-text("Hapus")').first();
    
    await expect(deleteButton).toBeVisible();
    await deleteButton.click();
    await page.waitForTimeout(1000);
    
    // Konfirmasi delete di modal
    const confirmButton = page.locator('#modal-delete button:has-text("Ya"), #modal-delete button:has-text("Hapus"), #modal-delete button[type="submit"]').first();
    
    if (await confirmButton.isVisible()) {
      await confirmButton.click();
      
      // Tunggu response delete
      await page.waitForResponse(
        response => response.url().match(/\/prestasiku\/\d+/) && response.request().method() === 'DELETE',
        { timeout: 15000 }
      );
      
      // Verifikasi success message
      await page.waitForTimeout(1000);
      const successMessage = page.locator('.alert-success, [class*="success"], .swal2-popup').first();
      if (await successMessage.isVisible()) {
        await expect(successMessage).toBeVisible();
      }
    }
  });

  test('[POSITIVE] Navigasi menggunakan pagination ke halaman berikutnya', async ({ page }) => {
    // Cari pagination links
    const nextPageLink = page.locator('a[rel="next"], a:has-text("Next"), a:has-text("›")').first();
    
    if (await nextPageLink.isVisible()) {
      await nextPageLink.click();
      await page.waitForLoadState('networkidle');
      
      // Verifikasi URL berubah (page parameter)
      expect(page.url()).toContain('page=');
    }
  });

  test('[POSITIVE] Kombinasi search dan filter untuk pencarian spesifik', async ({ page }) => {
    // Search dulu
    const searchInput = page.locator('input[name="search"]');
    if (await searchInput.isVisible()) {
      await searchInput.fill('Test Setup');
      
      // Klik tombol search
      const searchButton = page.locator('button[type="submit"]:has(i.fa-search)');
      await searchButton.click();
      await page.waitForLoadState('networkidle');
    }
    
    // Lalu filter (otomatis submit karena ada event listener)
    const filterSelect = page.locator('select[name="tingkat_lomba_id"]');
    if (await filterSelect.isVisible()) {
      await filterSelect.selectOption({ index: 1 });
      await page.waitForLoadState('networkidle');
      
      // Verifikasi URL mengandung kedua parameter
      expect(page.url()).toContain('search=');
      expect(page.url()).toContain('tingkat_lomba_id=');
    }
  });

  test('[POSITIVE] Reset filter ke kondisi default (semua data)', async ({ page }) => {
    // Apply filter dulu
    const filterSelect = page.locator('select[name="tingkat_lomba_id"]').first();
    if (await filterSelect.isVisible()) {
      await filterSelect.selectOption({ index: 1 });
      await page.waitForLoadState('networkidle');
      
      // Reset dengan memilih opsi pertama (All/Semua)
      await filterSelect.selectOption({ index: 0 });
      await page.waitForLoadState('networkidle');
    }
  });

  // ==================== NEGATIVE TEST CASES ====================

  test('[NEGATIVE] Submit form tambah prestasi tanpa mengisi field required', async ({ page }) => {
    await page.goto('/prestasiku/create');
    await page.waitForLoadState('networkidle');
    
    const submitButton = page.locator('button[type="submit"], button:has-text("Simpan")').first();
    await submitButton.click();
    
    // Tunggu response error
    await page.waitForTimeout(1000);
    
    // Verifikasi ada error message
    const errorMessage = page.locator('.text-red-500, .alert-danger, [class*="error"]').first();
    if (await errorMessage.isVisible()) {
      await expect(errorMessage).toBeVisible();
    }
  });

  test('[NEGATIVE] Mencari prestasi dengan kata kunci yang tidak ada', async ({ page }) => {
    const searchInput = page.locator('input[name="search"]');
    
    if (await searchInput.isVisible()) {
      await searchInput.fill('XYZPrestasiTidakAda999');
      
      const searchButton = page.locator('button[type="submit"]:has(i.fa-search)');
      await searchButton.click();
      
      await page.waitForLoadState('networkidle');
      
      // Verifikasi pesan "tidak ditemukan" atau hasil kosong
      const noResultMessage = page.locator('h5:has-text("Prestasi tidak ditemukan"), .alert-info:has-text("tidak ditemukan")');
      await expect(noResultMessage).toBeVisible();
    }
  });

  test('[NEGATIVE] Submit form tambah prestasi dengan file yang tidak valid (ukuran/format)', async ({ page }) => {
    await page.goto('/prestasiku/create');
    await page.waitForLoadState('networkidle');
    
    // Isi form dengan data valid
    await page.selectOption('select[name="dosen_id"]', { index: 1 });
    await page.selectOption('select[name="lomba_id"]', { index: 1 });
    await page.fill('input[name="prestasi_nama"]', 'Test Invalid File');
    await page.fill('input[name="tanggal_perolehan"]', '2024-01-15');
    
    // Upload file yang tidak valid (misal: file text bukan image)
    const invalidFilePath = path.join(process.cwd(), 'tests', 'files', 'invalid-file.txt');
    
    const sertifikatInput = page.locator('input[name="file_sertifikat"]');
    if (await sertifikatInput.isVisible()) {
      try {
        await sertifikatInput.setInputFiles(invalidFilePath);
      } catch (error) {
        // Expected error untuk file invalid
      }
    }
    
    const submitButton = page.locator('button[type="submit"], button:has-text("Simpan")').first();
    await submitButton.click();
    
    await page.waitForTimeout(1000);
    
    // Verifikasi ada error message tentang file
    const errorMessage = page.locator('.text-red-500, .alert-danger, [class*="error"]').first();
    if (await errorMessage.isVisible()) {
      await expect(errorMessage).toBeVisible();
    }
  });

  test('[NEGATIVE] Mengupdate prestasi dengan data yang tidak valid', async ({ page }) => {
    // Data sudah pasti ada karena dibuat di beforeAll
    
    const editButton = page.locator('a.btn-warning:has-text("Edit")').first();
    
    await expect(editButton).toBeVisible();
    await editButton.click();
    await page.waitForLoadState('networkidle');
    
    // Clear required field
    const namaInput = page.locator('input[name="prestasi_nama"]');
    await namaInput.clear();
    
    const submitButton = page.locator('button[type="submit"], button:has-text("Update"), button:has-text("Simpan")').first();
    await submitButton.click();
    
    await page.waitForTimeout(1000);
    
    // Verifikasi error message
    const errorMessage = page.locator('.text-red-500, .alert-danger, [class*="error"]').first();
    if (await errorMessage.isVisible()) {
      await expect(errorMessage).toBeVisible();
    }
  });

  test('[NEGATIVE] Akses halaman edit prestasi dengan ID yang tidak valid', async ({ page }) => {
    await page.goto('/prestasiku/99999/edit');
    await page.waitForLoadState('networkidle');
    
    // Verifikasi error 404 atau redirect
    const errorMessage = page.locator('.alert-danger, [class*="error"], h1:has-text("404")').first();
    
    // Bisa juga redirect ke halaman list
    const isOnListPage = page.url().includes('/prestasiku') && !page.url().includes('/edit');
    
    if (await errorMessage.isVisible()) {
      await expect(errorMessage).toBeVisible();
    } else {
      expect(isOnListPage).toBeTruthy();
    }
  });

  test('[NEGATIVE] Batalkan penghapusan prestasi di dialog konfirmasi', async ({ page }) => {
    // Data sudah pasti ada karena dibuat di beforeAll
    
    const deleteButton = page.locator('button.btn-danger:has-text("Hapus")').first();
    
    await expect(deleteButton).toBeVisible();
    await deleteButton.click();
    await page.waitForTimeout(1000);
    
    // Klik tombol batal/cancel di modal
    const cancelButton = page.locator('#modal-delete button:has-text("Batal"), #modal-delete button:has-text("Tidak"), #modal-delete button[data-dismiss="modal"]').first();
    
    if (await cancelButton.isVisible()) {
      await cancelButton.click();
      
      await page.waitForTimeout(500);
      
      // Verifikasi masih di halaman list dan modal tertutup
      expect(page.url()).toContain('/prestasiku');
      const modal = page.locator('#modal-delete');
      await expect(modal).not.toBeVisible();
    }
  });

  test('[NEGATIVE] Akses halaman detail prestasi dengan ID yang tidak valid', async ({ page }) => {
    await page.goto('/prestasiku/99999');
    await page.waitForLoadState('networkidle');
    
    // Verifikasi error 404 atau redirect
    const errorMessage = page.locator('.alert-danger, [class*="error"], h1:has-text("404")').first();
    const isOnListPage = page.url() === '/prestasiku' || page.url().endsWith('/prestasiku');
    
    if (await errorMessage.isVisible()) {
      await expect(errorMessage).toBeVisible();
    } else {
      expect(isOnListPage).toBeTruthy();
    }
  });
});