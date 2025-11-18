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

## Cara Testing di Postman

### Step 1: Login
1. Buka request "Login" di Postman Collection
2. Ubah username/password sesuai data user di database
3. Klik "Send"
4. Tokenotomatis tersimpan di variable `api_token`

### Step 2: Get User
1. Buka request "Get User"
2. Token sudah otomatis di header (dari variable `api_token`)
3. Klik "Send"

### Step 3: Logout
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

## Troubleshooting

### "CORS error" atau "Connection refused"
- Pastikan aplikasi Laravel sudah running: `php artisan serve`
- Check URL di `base_url` variable

### "Unauthenticated" / Token error
- Pastikan Bearer token ada di header request
- Generate ulang token dengan login kembali
- Check jika token sudah expired

### "Username atau password salah"
- Verifikasi credentials di database
- Pastikan password di database sudah ter-hash dengan bcrypt
