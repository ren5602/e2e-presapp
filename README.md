# 🎓 PresApp

**PresApp** adalah *Sistem Informasi Pencatatan Prestasi Mahasiswa dan Rekomendasi Peserta Lomba* berbasis web yang dirancang untuk membantu pengelolaan data prestasi mahasiswa dan memberikan rekomendasi peserta lomba secara objektif menggunakan metode pengambilan keputusan: **TOPSIS**, **SAW**, dan **PSI**.

---

## 🔍 Fitur Utama

- 📌 **Pencatatan Prestasi Mahasiswa**  
  Input dan manajemen data prestasi seperti lomba, sertifikat, jenis kejuaraan, tingkat, dan kategori.

- 🧠 **Rekomendasi Peserta Lomba**  
  Sistem cerdas yang memberikan rekomendasi peserta lomba berdasarkan data prestasi dengan menggunakan tiga metode pengambilan keputusan:
  - **TOPSIS (Technique for Order Preference by Similarity to Ideal Solution)**
  - **SAW (Simple Additive Weighting)**
  - **PSI (Preference Selection Index)**

- 📊 **Visualisasi & Laporan**  
  Menampilkan hasil perhitungan dan rekomendasi dalam bentuk tabel untuk mempermudah analisis.

- 👥 **Manajemen Pengguna**  
  Role berbasis akses: Admin, Dosen, dan Mahasiswa.

- 🧾 **Riwayat dan Validasi Prestasi**  
  Fitur validasi oleh admin serta riwayat prestasi yang tercatat otomatis.

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel (PHP)
- **Frontend**: Bootstrap & Tailwind CSS, JavaScript
- **Database**: MySQL
- **Library Perhitungan**: Custom implementation (SAW, TOPSIS, PSI)
- **Tools Tambahan**: DataTables, Select2, SweetAlert

---

## 🚀 Cara Menjalankan Aplikasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/ren5602/e2e-presapp.git

2. **Instalasi Dependensi**
    ```bash
    composer install
    npm install && npm run dev

3. **Konfigurasi Environment**
    - Copy file .env.example ke .env
    - Atur konfigurasi database, mail, dsb.

4. **Migrate dan Seed Database**
    ```bash
    php artisan migrate --seed

5. **Jalankan Server**
    ```bash
    php artisan serve

---

## 🧪 Testing

### 📝 Playwright E2E Testing

**Playwright** digunakan untuk melakukan automated testing pada frontend, khususnya untuk scenario login dan navigasi aplikasi.

#### Setup

Playwright sudah dikonfigurasi dalam `playwright.config.js`. Pastikan sudah menjalankan:
```bash
npm install
```

#### Menjalankan E2E Tests

**Mode Headless (Standar - tanpa UI browser)**
```bash
npm run test:e2e
```
Dijalankan di background, hasil ditampilkan di terminal.

**Mode UI (Interactive)**
```bash
npm run test:e2e:ui
```
Membuka UI interaktif Playwright untuk melihat eksekusi test secara real-time dan debug test.

**Mode Debug**
```bash
npm run test:e2e:debug
```
Mode debugging penuh dengan inspector untuk troubleshooting test cases.

#### Hasil Testing

Setelah test selesai, laporan HTML dapat diakses di:
```
playwright-report/index.html
```

Laporan mencakup:
- 📊 Summary hasil test (passed/failed)
- 🎬 Screenshots untuk setiap test step
- 🎥 Video recording dari test execution
- ⏱️ Duration dan timing information

**Test Cases yang Tersedia:**
- ✅ Login page displays correctly
- ✅ Valid login dengan kredensial Admin
- ✅ Valid login dengan kredensial Mahasiswa  
- ✅ Valid login dengan kredensial Dosen
- ❌ Invalid login (kredensial salah)
- ❌ Login tanpa username (validation error)
- ❌ Login tanpa password (validation error)

**Tips:**
- Pastikan server sudah berjalan: `npm run dev` di terminal terpisah
- Test berjalan di Chromium browser (konfigurasi dapat diubah di `playwright.config.js`)
- Jika test gagal, lihat screenshots dan videos di `test-results/` folder

---

### 🔐 Postman API Testing

#### Setup Postman

1. **Setup Environment Variables**
   - Di Postman, buat Environment baru atau gunakan yang ada
   - Tambahkan variables:
     ```
     base_url: http://localhost:8000
     api_token: (akan auto-populate saat login)
     ```
   - Pastikan environment terpilih di dropdown Environment

#### Testing API Endpoints

**1. Login Endpoint**
   - **Request**: `POST /api/auth/login`
   - **Body (form-data)**:
     ```
     username: admin (atau mahasiswa, dosen)
     password: password
     ```
   - **Expected Response**: 
     ```json
     {
       "message": "Login successful",
       "user": {...},
       "api_token": "abc123..."
     }
     ```
   - **Note**: API token otomatis disimpan dalam variable `api_token` untuk digunakan request selanjutnya

**2. Get User Endpoint**
   - **Request**: `GET /api/user`
   - **Headers**: Authorization: Bearer {{api_token}}
   - **Expected Response**: User data JSON
   - **Catatan**: Pastikan sudah login terlebih dahulu untuk mendapatkan valid token

**3. Logout Endpoint**
   - **Request**: `POST /api/auth/logout`
   - **Headers**: Authorization: Bearer {{api_token}}
   - **Expected Response**: 
     ```json
     {
       "message": "Logout successful"
     }
     ```

#### Testing Steps

1. **Positive Testing (Valid Credentials)**
   - Buka request "Login" di collection
   - Input username dan password yang valid
   - Klik **Send** → Lihat response 200 OK
   - Token otomatis tersimpan
   - Test "Get User" dan "Logout" dengan token tersebut

2. **Negative Testing (Invalid Credentials)**
   - Buka request "Login"
   - Input username/password yang salah
   - Klik **Send** → Lihat error response (401 Unauthorized)
   - Pastikan token tidak ter-generate

3. **Token Validation**
   - Login terlebih dahulu untuk mendapat token
   - Coba akses endpoint yang butuh auth dengan token
   - Modifikasi token → Coba akses → Lihat error (invalid token)

#### Dokumentasi API Lengkap

Lihat file `API_AUTH_GUIDE.md` untuk dokumentasi detail mengenai:
- Endpoint specifications
- Request/response examples
- Error handling
- Authentication flow

#### Menyimpan Bukti Testing

Ambil screenshot response dari Postman dan simpan di:
```
testing-evidence/postman-screenshots/
```

Contoh struktur:
```
testing-evidence/postman-screenshots/
├── login-endpoint/
│   ├── login-success-admin.png
│   ├── login-success-mahasiswa.png
│   └── login-invalid-credentials.png
├── get-user-endpoint/
│   ├── get-user-success.png
│   └── get-user-unauthorized.png
└── logout-endpoint/
    └── logout-success.png
```