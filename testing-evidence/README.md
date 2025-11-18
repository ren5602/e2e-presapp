# 📸 Testing Evidence Documentation

Folder ini digunakan untuk menyimpan bukti testing dari Postman API testing dan dokumentasi lainnya terkait testing.

## 📂 Struktur Folder

```
testing-evidence/
├── postman-screenshots/          # Screenshot response dari Postman testing
│   ├── login-endpoint/
│   │   ├── login-success-admin.png
│   │   ├── login-success-mahasiswa.png
│   │   ├── login-success-dosen.png
│   │   └── login-invalid-credentials.png
│   ├── get-user-endpoint/
│   │   ├── get-user-success.png
│   │   └── get-user-unauthorized.png
│   └── logout-endpoint/
│       └── logout-success.png
└── README.md                      # File ini
```

## 📝 Panduan Menyimpan Screenshot Postman

### Naming Convention

Gunakan naming yang deskriptif dan konsisten:
- Format: `{endpoint}-{scenario}.png`
- Contoh: `login-success-admin.png`, `login-invalid-credentials.png`
- Gunakan huruf kecil dan garis penghubung (`-`) untuk spasi

### Screenshot Content

Ketika mengambil screenshot Postman response, pastikan menampilkan:
1. ✅ **Request URL** (bagian atas)
2. ✅ **Request Method** (GET, POST, etc)
3. ✅ **Request Body** (jika ada)
4. ✅ **Response Status Code** (200, 401, 404, etc)
5. ✅ **Response Body/JSON** (hasil response)
6. ✅ **Headers** (jika diperlukan)

### Best Practices

- 📌 Satu screenshot = satu test case
- 📌 Ambil screenshot setelah klik **Send** dan response diterima
- 📌 Gunakan zoom/resize agar seluruh response terlihat jelas
- 📌 Hindari data sensitif (password, token) - gunakan placeholder atau blur
- 📌 Organisir berdasarkan endpoint dan test scenario (positive/negative)

## 🔄 Struktur Test Scenarios

### Login Endpoint
- ✅ `login-success-admin.png` - Login berhasil dengan credential admin
- ✅ `login-success-mahasiswa.png` - Login berhasil dengan credential mahasiswa
- ✅ `login-success-dosen.png` - Login berhasil dengan credential dosen
- ❌ `login-invalid-credentials.png` - Login gagal (username/password salah)
- ❌ `login-missing-username.png` - Error: username field kosong
- ❌ `login-missing-password.png` - Error: password field kosong

### Get User Endpoint
- ✅ `get-user-success.png` - Berhasil retrieve user data dengan valid token
- ❌ `get-user-unauthorized.png` - Error: token tidak valid atau expired
- ❌ `get-user-no-token.png` - Error: request tanpa Authorization header

### Logout Endpoint
- ✅ `logout-success.png` - Logout berhasil
- ❌ `logout-unauthorized.png` - Error: token tidak valid

## ⚠️ Penting

**Folder ini tidak ter-track oleh Git** (didefinisikan di `.gitignore`), sehingga:
- ✅ File-file screenshot Anda tidak akan ter-push ke repository
- ✅ Folder ini hanya untuk dokumentasi lokal dan reference
- ✅ Tidak mempengaruhi program atau CI/CD pipeline

Namun, struktur folder `.gitkeep` dapat ditambahkan untuk memastikan folder tetap ada saat repository di-clone.

## 🔗 Referensi

- [Postman Collection Import Guide](../Auth_API_Postman.json)
- [API Authentication Documentation](../API_AUTH_GUIDE.md)
- [Main README - Testing Section](../README.md#-testing)
