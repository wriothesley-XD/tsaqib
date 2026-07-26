# API Pendaftaran Tsaqib Island — Kontrak Framer ↔ Laravel

Ini buat Galang (backend) supaya bisa langsung kerja tanpa nunggu Framer
selesai duluan. Kontraknya sudah fix di bawah ini — kalau Framer ganti
struktur data nanti, koordinasikan dulu sebelum ubah.

## Endpoint

```
POST /api/submit
Content-Type: application/json
```

### Request body yang dikirim dari Framer

```json
{
  "komunitas": "tahfidz",
  "role": "hafidz",
  "nama_lengkap": "Nama Lengkap",
  "nama_panggilan": "Panggilan",
  "instagram": "@username",
  "alasan": "Alasan mendaftar..."
}
```

- `komunitas` dan `role` itu **slug**, harus sama persis dengan key yang
  ada di `config/komunitas.php` (contoh: `tahfidz`, bukan `Tahfidz`).

### Response sukses (200)
```json
{ "success": true, "message": "Pendaftaran berhasil diterima." }
```

### Response gagal validasi (422)
```json
{ "success": false, "message": "..." }
```
atau kalau gagal dari Laravel FormRequest bawaan:
```json
{ "message": "...", "errors": { "nama_lengkap": ["Nama lengkap wajib diisi."] } }
```

## Cara pasang ke repo Laravel

1. Copy semua file ke lokasi yang sama:
   - `routes/api.php` → **gabungkan** isinya ke `routes/api.php` yang
     sudah ada (jangan timpa, tinggal tambahkan baris Route::post-nya)
   - `app/Http/Requests/StorePendaftaranRequest.php`
   - `app/Http/Controllers/Api/PendaftaranController.php`
   - `app/Mail/PendaftaranMasuk.php`
   - `resources/views/emails/pendaftaran-masuk.blade.php`
   - `config/cors.php` — **cek dulu** apakah file ini sudah ada di project,
     kalau sudah ada tinggal sesuaikan bagian `allowed_origins` saja

2. Pastikan `config/komunitas.php` (yang sudah dibuat sebelumnya untuk
   bagian map) ada di project yang sama, karena controller ini
   membacanya untuk validasi slug komunitas/role.

3. Set alamat email tujuan di `.env`:
   ```
   MAIL_ADMIN_ADDRESS=fsi@sekolah.sch.id
   ```
   lalu di `config/mail.php` tambahkan:
   ```php
   'admin_address' => env('MAIL_ADMIN_ADDRESS', 'fsi@example.com'),
   ```

4. Testing tanpa SMTP dulu (biar nggak keblok jaringan sekolah):
   set `.env`:
   ```
   MAIL_MAILER=log
   ```
   Nanti isi email muncul di `storage/logs/laravel.log`, bukan
   benar-benar terkirim — cukup buat mastiin data & format email-nya
   benar dulu.

## Alamat API tergantung situasi (untuk kode fetch di Framer)

| Situasi | URL |
|---|---|
| Testing di laptop sendiri | `http://127.0.0.1:8000/api/submit` |
| Testing online sementara | URL ngrok yang lagi aktif |
| Sudah live (production) | domain asli, misal `https://api.tsaqibisland.com/api/submit` |

Contoh kode fetch lengkap ada di `framer-submit-example.js` — tinggal
sesuaikan `apiUrl`-nya dan cara ambil data dari form Framer kalian
(`formData.komunitasSlug` dst itu placeholder, sesuaikan dengan cara
kalian simpan state di Framer).

## Yang perlu dicek bareng-bareng sebelum dianggap "selesai"
- [ ] CORS sudah di-set benar (`allowed_origins` di `config/cors.php`)
- [ ] Test kirim dari Framer beneran (bukan cuma Postman) — dulu sempat
      berhasil pakai ngrok, pastikan masih jalan setelah kode di atas
      dipasang
- [ ] Format email yang diterima admin sudah sesuai kebutuhan (isi,
      subject, dst)
- [ ] Apa yang terjadi di sisi Framer kalau response-nya gagal
      (422/500) — pastikan ada pesan error yang muncul ke user, jangan
      diam saja
