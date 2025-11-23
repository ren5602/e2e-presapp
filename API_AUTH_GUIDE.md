# API Authentication Documentation

Panduan untuk testing API Authentication dengan Postman.

## Setup

### 1. Pastikan Sanctum sudah ter-install
Jika belum, jalankan:
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 2. Import Postman Collection
1. Buka Postman
2. Klik "Import" → pilih file `Auth_API_Postman.json`
3. Atau gunakan environment variable:
   - `base_url`: http://localhost:8000
   - `api_token`: akan otomatis diisi setelah login

## API Endpoints

### 1. Login
**POST** `/api/auth/login`

**Request Body:**
```json
{
    "username": "admin",
    "password": "password"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": {
            "user_id": 1,
            "username": "admin",
            "level_id": 1,
            "nama": "Administrator",
            "role": "ADM",
            "role_name": "Administrator"
        },
        "token": "1|abc123..."
    }
}
```

**Response Error (401):**
```json
{
    "success": false,
    "message": "Username atau password salah"
}
```

---

### 2. Get User (yang sedang login)
**GET** `/api/user`

**Headers Required:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "User berhasil diambil",
    "data": {
        "user_id": 1,
        "username": "admin",
        "level_id": 1,
        "nama": "Administrator",
        "role": "ADM",
        "role_name": "Administrator",
        "level": {
            "level_id": 1,
            "level_kode": "ADM",
            "level_nama": "Administrator"
        }
    }
}
```

**Response Error (401):**
```json
{
    "success": false,
    "message": "User tidak terautentikasi"
}
```

---

### 3. Logout (Semua Token)
**POST** `/api/auth/logout`

**Headers Required:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

---

### 4. Logout Current Device
**POST** `/api/auth/logout-current`

**Headers Required:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Logout dari device ini berhasil"
}
```

---

### 5. Get Daftar Prestasi Mahasiswa
**GET** `/api/prestasi`

**Headers Required:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters (Optional):**
```
search=nama_prestasi          // Cari berdasarkan nama prestasi atau nama lomba
tingkat_lomba_id=1            // Filter berdasarkan tingkat lomba
status_verifikasi=0           // Filter status (0=terverifikasi, 1=ditolak, 2=pending)
page=1                        // Halaman pagination (default: 1)
per_page=10                   // Items per page (default: 10)
```

**Example Request:**
```
GET /api/prestasi?search=kompetisi&tingkat_lomba_id=1&page=1&per_page=5
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Daftar prestasi berhasil diambil",
    "data": [
        {
            "prestasi_id": 1,
            "mahasiswa_id": 1,
            "dosen_id": 1,
            "prestasi_nama": "Juara Kompetisi Programming",
            "lomba_id": 5,
            "juara": 1,
            "nama_juara": "Juara 1",
            "tanggal_perolehan": "2024-11-15",
            "file_sertifikat": "mahasiswa/12345/prestasi/sertifikat/1234567890_cert.jpg",
            "file_bukti_foto": "mahasiswa/12345/prestasi/bukti_foto/1234567890_photo.jpg",
            "file_surat_tugas": "mahasiswa/12345/prestasi/surat_tugas/1234567890_task.jpg",
            "file_surat_undangan": "mahasiswa/12345/prestasi/surat_undangan/1234567890_invite.jpg",
            "file_proposal": "mahasiswa/12345/prestasi/proposal/1234567890_proposal.pdf",
            "poin": 100,
            "status_verifikasi": null,
            "created_at": "2024-11-20T10:30:00.000000Z",
            "updated_at": "2024-11-20T10:30:00.000000Z",
            "mahasiswa": {...},
            "dosen": {...},
            "lomba": {
                "lomba_id": 5,
                "lomba_nama": "National Programming Competition",
                "tingkat": {
                    "tingkat_lomba_id": 2,
                    "tingkat_lomba_kode": "NAS",
                    "tingkat_lomba_nama": "Nasional"
                },
                "penyelenggara": {...}
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 10,
        "total": 25,
        "last_page": 3
    }
}
```

---

### 6. Get Detail Prestasi
**GET** `/api/prestasi/{prestasi_id}`

**Headers Required:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Detail prestasi berhasil diambil",
    "data": {
        "prestasi_id": 1,
        "mahasiswa_id": 1,
        "dosen_id": 1,
        "prestasi_nama": "Juara Kompetisi Programming",
        "lomba_id": 5,
        "juara": 1,
        "nama_juara": "Juara 1",
        "tanggal_perolehan": "2024-11-15",
        "file_sertifikat": "mahasiswa/12345/prestasi/sertifikat/1234567890_cert.jpg",
        "file_bukti_foto": "mahasiswa/12345/prestasi/bukti_foto/1234567890_photo.jpg",
        "file_surat_tugas": "mahasiswa/12345/prestasi/surat_tugas/1234567890_task.jpg",
        "file_surat_undangan": "mahasiswa/12345/prestasi/surat_undangan/1234567890_invite.jpg",
        "file_proposal": "mahasiswa/12345/prestasi/proposal/1234567890_proposal.pdf",
        "poin": 100,
        "status_verifikasi": null,
        "created_at": "2024-11-20T10:30:00.000000Z",
        "updated_at": "2024-11-20T10:30:00.000000Z",
        "mahasiswa": {...},
        "dosen": {...},
        "lomba": {...}
    }
}
```

**Response Error (403 - Unauthorized):**
```json
{
    "success": false,
    "message": "Anda tidak diizinkan mengakses prestasi ini"
}
```

**Response Error (404 - Not Found):**
```json
{
    "success": false,
    "message": "Prestasi tidak ditemukan"
}
```

---

### 7. Tambah Prestasi Baru
**POST** `/api/prestasi`

**Headers Required:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

**Request Body (form-data):**

| Key | Type | Required | Value/Example | Keterangan |
|-----|------|----------|---------------|-----------|
| `dosen_id` | number | Ya | 1 | ID dosen pembimbing |
| `lomba_id` | number | Ya | 5 | ID lomba |
| `prestasi_nama` | text | Ya | Juara Kompetisi Programming | Nama prestasi |
| `juara` | number | Ya | 1 | Juara ke (1, 2, 3, atau 4) |
| `nama_juara` | text | Kondisional | Juara Harapan 1 | **Wajib jika juara=4** |
| `tanggal_perolehan` | text | Ya | 2024-11-15 | Format: YYYY-MM-DD |
| `file_sertifikat` | file | Ya | certificate.jpg | Mimes: jpg, jpeg, png (max 2MB) |
| `file_bukti_foto` | file | Ya | photo.jpg | Mimes: jpg, jpeg, png (max 2MB) |
| `file_surat_tugas` | file | Ya | task_letter.jpg | Mimes: jpg, jpeg, png (max 2MB) |
| `file_surat_undangan` | file | Ya | invitation.jpg | Mimes: jpg, jpeg, png (max 2MB) |
| `file_proposal` | file | Tidak | proposal.pdf | Mimes: pdf (max 4MB) |

**Response Success (201):**
```json
{
    "success": true,
    "message": "Prestasi berhasil ditambahkan",
    "data": {
        "prestasi_id": 1,
        "mahasiswa_id": 1,
        "dosen_id": 1,
        "prestasi_nama": "Juara Kompetisi Programming",
        "lomba_id": 5,
        "juara": 1,
        "nama_juara": "Juara 1",
        "tanggal_perolehan": "2024-11-15",
        "file_sertifikat": "mahasiswa/12345/prestasi/sertifikat/1234567890_cert.jpg",
        "file_bukti_foto": "mahasiswa/12345/prestasi/bukti_foto/1234567890_photo.jpg",
        "file_surat_tugas": "mahasiswa/12345/prestasi/surat_tugas/1234567890_task.jpg",
        "file_surat_undangan": "mahasiswa/12345/prestasi/surat_undangan/1234567890_invite.jpg",
        "file_proposal": "mahasiswa/12345/prestasi/proposal/1234567890_proposal.pdf",
        "poin": 100,
        "status_verifikasi": null,
        "created_at": "2024-11-20T10:30:00.000000Z",
        "updated_at": "2024-11-20T10:30:00.000000Z",
        "mahasiswa": {...},
        "dosen": {...},
        "lomba": {...}
    }
}
```

**Response Error (422 - Validation Failed):**
```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "dosen_id": ["The dosen_id field is required"],
        "file_sertifikat": ["The file_sertifikat must be a file of type: jpg, jpeg, png"]
    }
}
```

---

## Cara Testing di Postman

### Step 1: Login
1. Buka request "Login" di Postman Collection
2. Ubah username/password sesuai data user di database (gunakan user dengan role Mahasiswa)
3. Klik "Send"
4. Token otomatis tersimpan di variable `api_token`

### Step 2: Get User
1. Buka request "Get User"
2. Token sudah otomatis di header (dari variable `api_token`)
3. Klik "Send"

### Step 3: Testing Prestasi API

#### 3a. Get Daftar Prestasi
1. Buat request baru dengan method `GET`
2. URL: `{{base_url}}/api/prestasi`
3. Headers:
   - `Authorization: Bearer {{api_token}}`
   - `Accept: application/json`
4. (Optional) Tambahkan query parameters:
   ```
   ?search=kompetisi&tingkat_lomba_id=1&page=1&per_page=5
   ```
5. Klik "Send"

#### 3b. Get Detail Prestasi
1. Buat request baru dengan method `GET`
2. URL: `{{base_url}}/api/prestasi/1` (ganti 1 dengan ID prestasi yang ada)
3. Headers:
   - `Authorization: Bearer {{api_token}}`
   - `Accept: application/json`
4. Klik "Send"

#### 3c. Tambah Prestasi Baru
1. Buat request baru dengan method `POST`
2. URL: `{{base_url}}/api/prestasi`
3. Headers:
   - `Authorization: Bearer {{api_token}}`
   - `Content-Type: multipart/form-data` (auto-set oleh Postman saat menggunakan form-data)
   - `Accept: application/json`
4. Tab "Body" → pilih `form-data`
5. Isi field-field berikut:
   | Key | Type | Value |
   |-----|------|-------|
   | `dosen_id` | text | 1 |
   | `lomba_id` | text | 5 |
   | `prestasi_nama` | text | Juara Kompetisi Programming 2024 |
   | `juara` | text | 1 |
   | `tanggal_perolehan` | text | 2024-11-15 |
   | `file_sertifikat` | file | (pilih file jpg/jpeg/png, max 2MB) |
   | `file_bukti_foto` | file | (pilih file jpg/jpeg/png, max 2MB) |
   | `file_surat_tugas` | file | (pilih file jpg/jpeg/png, max 2MB) |
   | `file_surat_undangan` | file | (pilih file jpg/jpeg/png, max 2MB) |
   | `file_proposal` | file | (pilih file pdf, max 4MB) - Optional |

6. **Catatan penting untuk juara = 4:**
   - Jika `juara` = 4, tambahkan field `nama_juara` dengan value custom (contoh: "Juara Harapan 1")
   - Field ini **wajib** jika `juara` = 4

7. Klik "Send"

#### Testing Tips
- **Sebelum POST Prestasi**: Pastikan `dosen_id` dan `lomba_id` yang digunakan sudah ada di database
- **File uploads**: Di Postman, klik "Select Files" pada field file untuk memilih file dari komputer
- **Validation errors**: Jika ada error 422, cek response body untuk detail error di field mana
- **Authorization error**: Jika error 403, pastikan Anda login dengan akun Mahasiswa yang tepat

### Step 4: Logout
1. Buka request "Logout"
2. Klik "Send"
3. Token akan dihapus

## Testing Credentials

Gunakan salah satu user yang ada di database:
- **Admin**: username = `admin`, password = `password`
- **Mahasiswa/Dosen**: sesuai data di database

## Environment Variables di Postman

| Variable | Nilai Default | Keterangan |
|----------|--------------|-----------|
| `base_url` | http://localhost:8000 | URL aplikasi |
| `api_token` | (kosong) | Auto-filled setelah login |

## Notes

- Token berlaku untuk authentication semua API endpoints
- Token otomatis dihapus saat logout
- Setiap login akan generate token baru
- Gunakan "Logout Current Device" jika ingin logout dari satu device saja tanpa logout dari device lain
- Endpoint Prestasi hanya bisa diakses oleh user dengan role Mahasiswa
- File uploads menggunakan `multipart/form-data`, bukan JSON
- Status verifikasi prestasi otomatis set ke `null` (pending) saat dibuat
- Poin prestasi dihitung otomatis berdasarkan tingkat lomba dan juara yang diraih

## Troubleshooting

### "CORS error" atau "Connection refused"
- Pastikan aplikasi Laravel sudah running: `php artisan serve`
- Check URL di `base_url` variable

### "Unauthenticated" / Token error
- Pastikan Bearer token ada di header request
- Generate ulang token dengan login kembali
- Check jika token sudah expired
- Pastikan sedang login dengan akun yang memiliki data mahasiswa (user harus terhubung ke m_mahasiswa)

### "Username atau password salah"
- Verifikasi credentials di database
- Pastikan password di database sudah ter-hash dengan bcrypt

### "Validation error" saat POST Prestasi
- Pastikan semua file yang di-upload memiliki format yang sesuai:
  - Sertifikat, bukti foto, surat tugas, surat undangan: `jpg, jpeg, png` (max 2MB)
  - Proposal: `pdf` (max 4MB)
- Pastikan `dosen_id` dan `lomba_id` ada di database
- Jika `juara` = 4, jangan lupa menambah field `nama_juara`

### "403 Anda tidak diizinkan mengakses prestasi ini"
- Prestasi hanya bisa diakses oleh mahasiswa yang memilikinya
- Pastikan Anda login dengan akun mahasiswa yang tepat
- Jangan coba mengakses prestasi mahasiswa lain

### File tidak ter-upload
- Pastikan Postman set `Content-Type: multipart/form-data` (auto-set saat pakai form-data)
- Jangan gunakan JSON untuk request dengan file
- Ukuran file sudah sesuai dengan limit yang ditentukan
