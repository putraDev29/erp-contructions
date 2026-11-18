# PANDUAN DEPLOYMENT PRODUKSI: CONSTRUCTA ERP

Dokumen ini menjelaskan langkah-langkah implementasi dan pemeliharaan aplikasi **CONSTRUCTA ERP** di lingkungan produksi (VPS Linux Ubuntu/Debian/AlmaLinux dan Shared Hosting cPanel).

---

## 1. Persyaratan Server Produksi

- **Sistem Operasi**: Ubuntu 22.04 LTS / 24.04 LTS atau AlmaLinux 9
- **Web Server**: Nginx (direkomendasikan) atau Apache 2.4+
- **PHP**: Versi 8.2 atau 8.3 dengan ekstensi:
  - `php8.2-fpm`, `php8.2-cli`, `php8.2-mysql`, `php8.2-mbstring`, `php8.2-xml`, `php8.2-curl`, `php8.2-gd`, `php8.2-zip`, `php8.2-bcmath`, `php8.2-intl`
- **Database Server**: MariaDB 10.6+ atau MySQL 8.0+
- **Manajer Antrean & Penjadwal**: Supervisor & Cron

---

## 2. Panduan Deployment VPS (Nginx + PHP-FPM)

### A. Persiapan Repositori & Direktori
```bash
# Buat direktori aplikasi
mkdir -p /var/www/constructa-erp
cd /var/www/constructa-erp

# Clone repositori atau salin source code
git clone <URL_REPOSITORY> .

# Salin konfigurasi lingkungan
cp .env.example .env
```

### B. Konfigurasi File .env Produksi
Buka file `.env` dan pastikan nilai berikut disesuaikan:
```env
APP_NAME="CONSTRUCTA ERP"
APP_ENV=production
APP_KEY=base64:... (generate dengan php artisan key:generate)
APP_DEBUG=false
APP_URL=https://erp.perusahaananda.com
APP_TIMEZONE="Asia/Jakarta"

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=constructa_prod
DB_USERNAME=constructa_user
DB_PASSWORD="PasswordSangatKuatDanAcak123!"

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public
```

### C. Instalasi Dependensi & Build Asset
```bash
# Install dependensi PHP tanpa modul development
composer install --no-dev --optimize-autoloader

# Install dependensi Node.js dan kompilasi asset Tailwind & JS
npm ci
npm run build

# Buat symbolic link storage
php artisan storage:link

# Jalankan migrasi database dan seeder data awal
php artisan migrate --force --seed
```

### D. Konfigurasi Izin Direktori (File Permissions)
```bash
sudo chown -R www-data:www-data /var/www/constructa-erp
sudo chmod -R 775 /var/www/constructa-erp/storage
sudo chmod -R 775 /var/www/constructa-erp/bootstrap/cache
```

### E. Konfigurasi Virtual Host Nginx
Buat file konfigurasi `/etc/nginx/sites-available/constructa-erp`:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name erp.perusahaananda.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name erp.perusahaananda.com;
    root /var/www/constructa-erp/public;

    ssl_certificate /etc/letsencrypt/live/erp.perusahaananda.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/erp.perusahaananda.com/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    index index.php;
    charset utf-8;

    client_max_body_size 64M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
Aktifkan konfigurasi dan reload Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/constructa-erp /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 3. Penjadwal Tugas (Laravel Scheduler) & Worker Antrean

### A. Konfigurasi Crontab Sistem
Tambahkan jadwal cron Laravel untuk memproses notifikasi, jatuh tempo hutang/piutang, dan penutupan buku otomatis:
```bash
sudo crontab -u www-data -e
```
Tambahkan baris berikut:
```cron
* * * * * cd /var/www/constructa-erp && php artisan schedule:run >> /dev/null 2>&1
```

### B. Konfigurasi Supervisor (Queue Worker)
Buat file `/etc/supervisor/conf.d/constructa-worker.conf`:
```ini
[program:constructa-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/constructa-erp/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/constructa-erp/storage/logs/worker.log
stopwaitsecs=3600
```
Muat ulang supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start constructa-worker:*
```

---

## 4. Panduan Deployment Shared Hosting (cPanel)

1. **Struktur Direktori**:
   - Letakkan seluruh file proyek di luar direktori `public_html`, misalnya di `/home/username/constructa-erp`.
   - Salin isi folder `c:\project\contruction erp\public` ke dalam `/home/username/public_html`.
2. **Edit `public_html/index.php`**:
   Sesuaikan path autoloader dan bootstrap:
   ```php
   require __DIR__.'/../constructa-erp/vendor/autoload.php';
   $app = require_once __DIR__.'/../constructa-erp/bootstrap/app.php';
   ```
3. **Database di cPanel**:
   - Buat database MySQL baru dan user di menu **MySQL Database Wizard**.
   - Berikan hak akses penuh (*ALL PRIVILEGES*).
   - Masukkan nama DB, user, dan password ke file `.env` di `/home/username/constructa-erp/.env`.
4. **Terminal / SSH cPanel**:
   ```bash
   cd /home/username/constructa-erp
   php artisan migrate --force --seed
   php artisan storage:link
   php artisan optimize
   ```
5. **Cron Job di cPanel**:
   Buka menu **Cron Jobs** cPanel, tambahkan interval Setiap Menit (`* * * * *`):
   ```bash
   /usr/local/bin/php /home/username/constructa-erp/artisan schedule:run >> /dev/null 2>&1
   ```

---

## 5. Prosedur Backup & Pemulihan Database

### A. Backup Otomatis Harian (MySQL Dump)
Buat skrip backup `/usr/local/bin/backup-constructa.sh`:
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/constructa"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

mysqldump -u constructa_user -p"PasswordSangatKuatDanAcak123!" constructa_prod | gzip > "$BACKUP_DIR/db_constructa_$DATE.sql.gz"

# Hapus backup yang lebih tua dari 30 hari
find $BACKUP_DIR -type f -name "*.sql.gz" -mtime +30 -exec rm {} \;
```
Berikan izin eksekusi dan pasang di crontab root:
```bash
chmod +x /usr/local/bin/backup-constructa.sh
# Pasang cron pada pukul 01:00 pagi setiap hari
0 1 * * * /usr/local/bin/backup-constructa.sh
```

### B. Prosedur Restore Database
```bash
gunzip < /var/backups/constructa/db_constructa_YYYYMMDD_HHMMSS.sql.gz | mysql -u constructa_user -p constructa_prod
```

---

## 6. Checklist Sebelum Go-Live (Production Checklist)

- [ ] File `.env`: `APP_DEBUG=false` dan `APP_ENV=production`.
- [ ] Kredensial Administrator default (`admin@konstruksimaju.co.id`) telah diganti dengan password baru yang kuat.
- [ ] Sertifikat SSL/TLS aktif (HTTPS berlaku).
- [ ] Direktori `storage` dan `bootstrap/cache` memiliki izin tulis oleh web server (`www-data`).
- [ ] Menjalankan caching optimasi Laravel:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan event:cache
  ```
- [ ] Backup otomatis database berjalan lancar.
- [ ] Pengujian transaksi end-to-end (RAB &rarr; PO &rarr; Penerimaan Barang &rarr; SPK &rarr; Payroll &rarr; Tagihan Pelanggan &rarr; Jurnal Akuntansi) telah diverifikasi.
