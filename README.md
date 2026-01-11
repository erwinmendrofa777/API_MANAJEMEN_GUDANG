### TEST PROGRAMMER BACKEND

### Langkah Instalasi

1. Clone Project
Download source code atau clone repositori ini ke folder server lokal Anda (misalnya `htdocs`).
```bash
git clone https://github.com/erwinmendrofa777/ManajemenGudang.git
cd ManajemenGudang
```

2. Install dependencies
Jalankan perintah berikut di terminal untuk mengunduh library CodeIgniter 4:
```bash
composer install
```

3. Konfigurasi Environment
Salin file konfigurasi environment:
```bash
cp env .env
# Jika di Windows (CMD), gunakan: copy env .env
```
Buka file .env di text editor dan sesuaikan konfigurasi berikut:
```bash
# Ubah ke mode development untuk debugging
CI_ENVIRONMENT = development

# Konfigurasi Database
database.default.hostname = localhost
database.default.database = db_gudang  # Pastikan nama ini sesuai langkah no 4
database.default.username = root       # User default XAMPP/Laragon biasanya root
database.default.password =            # Kosongkan jika tidak ada password
database.default.DBDriver = MySQLi
```

4. start server mysql di xampp dan Buat Database
Buka aplikasi database manager(phpmyadmin), lalu buat database baru dengan nama: "db_gudang"

5. Jalankan Migrasi Database
Jalankan perintah:
```bash
php spark migrate
```
Perintah ini akan otomatis membuat tabel items beserta kolom-kolomnya.

6. Jalankan Aplikasi
Jalankan local development server:
```bash
php spark serve
```
Akses aplikasi melalui browser di alamat: http://localhost:8080