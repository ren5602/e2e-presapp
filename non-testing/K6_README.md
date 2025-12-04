# K6 Load Test untuk Presma

## Apa itu K6?

K6 adalah **open-source load testing tool** yang digunakan untuk menguji performa website/API di bawah beban traffic yang tinggi. K6 memungkinkan Anda untuk:

- Simulasi multiple concurrent users mengakses website secara bersamaan
- Mengidentifikasi bottleneck dan performance issues
- Validasi bahwa aplikasi dapat handle expected traffic volume
- Measure response times, error rates, dan metrics penting lainnya

## Prerequisites

### 1. Install K6

**Windows (menggunakan Chocolatey):**
```powershell
choco install k6
```

**Windows (menggunakan MSI installer):**
- Download dari https://dl.k6.io/msi/k6-latest-amd64.msi
- Jalankan installer

**macOS:**
```bash
brew install k6
```

**Linux (Ubuntu/Debian):**
```bash
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C5AD17C747E3232A+
echo "deb [signed-by=/usr/share/keyrings/k6-archive-keyring.gpg] https://dl.k6.io/deb stable main" | sudo tee /etc/apt/sources.list.d/k6-list.list
sudo apt-get update
sudo apt-get install k6
```

### 2. Verifikasi Instalasi

```powershell
k6 version
```

## Cara Menjalankan Script

### Basic Run
```powershell
k6 run non-testing/k6-load-test.js
```

### Run dengan Output ke File JSON
```powershell
k6 run non-testing/k6-load-test.js --out json=results.json
```

### Run dengan Output ke File CSV
```powershell
k6 run non-testing/k6-load-test.js --out csv=results.csv
```

### Run dengan InfluxDB Integration (Advanced)
```powershell
k6 run non-testing/k6-load-test.js --out influxdb=http://localhost:8086/k6
```

## Script Configuration

### Test Duration
- **Ramp-up:** 30 detik (meningkat dari 0 menjadi 10 virtual users)
- **Sustained Load:** 5 menit (mempertahankan 10 virtual users)
- **Ramp-down:** 30 detik (menurun dari 10 menjadi 0 virtual users)
- **Total Duration:** ~6 menit

### Virtual Users (VU)
- **Maximum VU:** 10 concurrent users
- **Scenario:** Multiple users melakukan login → get user data → get prestasi list secara bersamaan

### Performance Thresholds
- **P95 Response Time:** < 2 detik (95% request harus respond dalam waktu kurang dari 2 detik)
- **Error Rate:** < 1% (maksimal 1% request yang gagal)

## Interpretasi Results/Output

Setelah script selesai, K6 akan menampilkan summary output seperti:

```
     checks.........................: 100% ✓ 450 ✗ 0
     data_received..................: 150 kB
     data_sent.......................: 45 kB
     http_req_blocked...............: avg=10ms    min=5ms     med=8ms      max=50ms    p(90)=20ms   p(95)=25ms
     http_req_connecting............: avg=8ms     min=3ms     med=7ms      max=45ms    p(90)=18ms   p(95)=22ms
     http_req_duration..............: avg=350ms   min=100ms   med=280ms    max=1800ms  p(90)=600ms  p(95)=1200ms ✓
     http_req_failed................: 0.00% ✓
     http_req_receiving.............: avg=50ms    min=10ms    med=40ms     max=200ms   p(90)=100ms  p(95)=150ms
     http_req_sending...............: avg=20ms    min=5ms     med=15ms     max=100ms   p(90)=40ms   p(95)=50ms
     http_req_tls_handshaking.......: avg=120ms   min=50ms    med=100ms    max=300ms   p(90)=200ms  p(95)=250ms
     http_req_waiting...............: avg=280ms   min=80ms    med=220ms    max=1600ms  p(90)=500ms  p(95)=1000ms
     http_reqs......................: 450 in 6m0s
     iteration_duration.............: avg=8.5s    min=6.2s    med=8.3s     max=12.4s
     iterations.....................: 150 in 6m0s
     vus............................: 0     min=0     max=10
     vus_max........................: 10
```

### Penjelasan Metrics:

| Metric | Penjelasan | Target |
|--------|-----------|--------|
| `checks` | Jumlah validasi yang pass/fail | Semua harus pass (✓) |
| `http_req_duration` | Waktu response dari server | p(95) < 2000ms |
| `http_req_failed` | Persentase request yang gagal | < 1% |
| `http_reqs` | Total jumlah HTTP request yang dikirim | Semakin tinggi semakin baik |
| `iterations` | Jumlah kali loop utama dijalankan | Sesuai VU × duration |
| `vus` | Virtual users (concurrent users) | Harus mencapai target (10) |
| `http_req_blocked` | Waktu tunggu sebelum request dimulai | Semakin rendah semakin baik |
| `http_req_tls_handshaking` | Waktu untuk HTTPS handshake | Normal untuk HTTPS |

## Interpretasi Hasil

### ✅ PASSED (Performa Baik)
- `http_req_failed` = 0% 
- `http_req_duration[p(95)]` < 2000ms
- `checks` semuanya pass

**Kesimpulan:** Server dapat menangani 10 concurrent users dengan baik.

### ⚠️ WARNING (Performa Cukup)
- `http_req_failed` = 0.5-1%
- `http_req_duration[p(95)]` = 2000-3000ms
- Beberapa checks fail

**Kesimpulan:** Server mulai mengalami stress, pertimbangkan optimization.

### ❌ FAILED (Performa Buruk)
- `http_req_failed` > 1%
- `http_req_duration[p(95)]` > 3000ms
- Banyak checks yang fail

**Kesimpulan:** Server tidak dapat menangani load, perlu dilakukan optimization atau scaling.

## Tips Optimisasi jika Ada Bottleneck

### 1. Database Optimization
- Tambahkan indexing pada kolom yang sering di-query
- Gunakan eager loading untuk relation data
- Cache frequently accessed data

### 2. Server-side Optimization
- Implement response caching (HTTP Cache headers)
- Gunakan CDN untuk static assets
- Optimize database queries (profile dengan Laravel Debug Bar)
- Implementasikan pagination dengan size yang tepat

### 3. Horizontal Scaling
- Jalankan multiple server instances
- Gunakan load balancer (nginx, HAProxy)
- Distribusikan traffic ke beberapa server

### 4. Monitor & Profiling
```powershell
# Jalankan dengan verbose logging
k6 run non-testing/k6-load-test.js -v

# Jalankan dengan detail tracing
k6 run non-testing/k6-load-test.js -u 5 -d 30s --out json=results.json
```

### 5. Incremental Load Testing
Jika 10 VU sudah problematic, coba dari VU yang lebih kecil terlebih dahulu:
- Mulai dari 5 VU
- Naik ke 10 VU
- Naik ke 20 VU
- Dst...

Identifikasi pada VU berapa server mulai mengalami degradasi performa.

## Troubleshooting

### Error: "k6: command not found"
- **Solusi:** K6 belum di-install atau tidak ada di PATH. Ulang instalasi dan restart terminal.

### Error: "Connection refused" atau "ECONNREFUSED"
- **Solusi:** Pastikan website https://presma.dbsnetwork.my.id dapat diakses dan online.

### Error: "401 Unauthorized" setelah login
- **Solusi:** Verifikasi username/password di script (`admin` / `admin123`) masih valid.

### Error: "TLS certificate verification failed"
- **Solusi Temporary (Development):** 
  ```powershell
  k6 run non-testing/k6-load-test.js --insecure-skip-tls-verify
  ```

## Next Steps

1. Jalankan script dan catat hasil baseline
2. Identifikasi bottleneck dari metrics
3. Lakukan optimization berdasarkan tips di atas
4. Re-run script untuk compare hasil setelah optimization
5. Skalakan VU secara incremental untuk find breaking point
