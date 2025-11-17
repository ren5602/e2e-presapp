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
   git clone https://github.com/miicheelll/PresApp.git
   cd PBL_Presma_Smt4

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