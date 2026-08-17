# Gmail Banding

Web aplikasi untuk mengajukan banding (appeal) akun Gmail yang dinonaktifkan
dalam **1 klik untuk banyak email** sekaligus. Setiap email membuka halaman
appeal resmi Google secara berurutan, dan riwayat banding tercatat di database.

> **Penting:** Appeal resmi Google hanya dapat dimulai setelah login ke akun
> yang bersangkutan. Aplikasi ini membuka halaman resmi
> `accounts.google.com/Login` — bukan form pihak ketiga. Google menyarankan
> untuk tidak mengajukan banding berulang pada akun yang sama.

## Fitur

- Login admin sederhana (kredensial dari `.env`)
- Input banyak email (satu per baris) dengan validasi format + whitelist domain
- 1 klik "Banding Sekarang" → tab appeal resmi Google dibuka berurutan dengan jeda
- Riwayat banding: status (`pending` / `opened` / `failed`), IP, waktu
- Keamanan: PDO prepared statements (anti SQLi), CSRF token, `htmlspecialchars`
  (anti XSS), `.env` untuk semua data sensitif

## Struktur

```
public/          root dokumen (index, login, riwayat, banding API, assets)
app/             bootstrap, config, database (PDO), auth, csrf, validate, views
database/        schema.sql (tabel emails & appeals)
scripts/         migrate.php (jalankan schema)
deploy/          contoh konfigurasi Nginx
.github/workflows/ CI/CD deploy ke VPS (push ke main)
```

## Setup Lokal

```bash
cp .env.example .env          # isi kredensial DB & password admin
php scripts/migrate.php       # buat tabel
php -S 127.0.0.1:8080 -t public
```

Buka `http://127.0.0.1:8080`, login, lalu paste email-email.

## Deployment (CI/CD)

`git push origin main` → GitHub Actions menjalankan PHP lint, lalu deploy ke
VPS via rsync/SSH. Siapkan secrets di repo:

| Secret           | Deskripsi                          |
| ---------------- | ---------------------------------- |
| `VPS_SSH_KEY`    | Private key SSH untuk deploy       |
| `VPS_HOST`       | IP/hostname VPS                    |
| `VPS_USER`       | User SSH di VPS                    |
| `VPS_PATH`       | Path tujuan deploy (contoh `/var/www/banding`) |

Setelah deploy pertama: jalankan `php scripts/migrate.php` di VPS dan salin
`.env` dari `.env.example`, lalu buat symlink config Nginx
(`deploy/nginx.conf.example`) dengan `root` mengarah ke `<VPS_PATH>/public`.
