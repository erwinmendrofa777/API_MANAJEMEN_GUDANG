### PROGRAM BACKEND API

### Langkah Instalasi

1. Clone Project
Download source code atau clone repositori ini ke folder server lokal Anda.
```bash
git clone https://github.com/erwinmendrofa777/API_MANAJEMEN_GUDANG.git
cd API_MANAJEMEN_GUDANG
```

2. Install dependencies
Jalankan perintah berikut di terminal untuk mengunduh library CodeIgniter 4:
```bash
composer install
```

3. Konfigurasi Environment 
rename file "env" menjadi ".env":

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

4. start server mysql di xampp

5. buka folder "db_manajemen_gudang" dan import file "db_gudang.sql" di phpmyadmin atau Buka aplikasi database manager(phpmyadmin), lalu buat database baru dengan nama: "db_gudang"

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

## 📌 Informasi Dasar

- **Base URL (Local):** `http://localhost:8080/api`
- **Format Request:** JSON
- **Format Response:** JSON

### Global Headers
Setiap request yang mengirim data (`POST`, `PUT`) **wajib** menyertakan headers berikut:

```http
Content-Type: application/json
Accept: application/json
```

## Dokumentasi Endpoint
1. Tampilkan Semua Barang (List)
- **Method:** GET
- **URL:** http://localhost:8080/api/items
- **Response Status 200 OK:** muncul daftar array JSON semua barang.

2. detail satu barang berdasarkan ID
- **Method:** GET
- **URL:** http://localhost:8080/api/items/{id}
- **contoh Response sukses (200 OK):**
```JSON
{
    "id": "3",
    "nama_barang": "Laptop Gaming Update",
    "sku": "SKU-BARU-001",
    "stok": "14",
    "created_at": {
        "date": "2026-01-06 13:06:44.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "updated_at": {
        "date": "2026-01-11 17:43:25.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    }
}
```
- **Response (404 Not Found):**
```JSON
{
    "status": 404,
    "error": 404,
    "messages": {
        "error": "Data barang tidak ditemukan."
    }
}
```

3. Tambah Barang Baru
- **Method:** POST
- **URL:** http://localhost:8080/api/items
- **contoh request:**
```JSON
{
    "nama_barang": "Keyboard Mekanik", //string
    "sku": "KEY-MECH-001", //string (kode unik tidak boleh duplikat)
    "stok": 20 //integer (minimal 0 atau tidak boleh minus)
}
```
- **contoh Response (201 Created):**
```JSON
{
    "message": "Barang berhasil ditambahkan",
    "data": {
        "id": "6",
        "nama_barang": "Keyboard Mekanik",
        "sku": "KEY-MECH-001",
        "stok": "20",
        "created_at": {
            "date": "2026-01-11 20:25:56.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "updated_at": {
            "date": "2026-01-11 20:25:56.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        }
    }
}
```

4. Update Data Barang (Endpoint ini tidak mengubah jumlah stok.)
- **Method:** PUT
- **URL:** http://localhost:8080/api/items/{id}
- **contoh request:**
```JSON
{
    "nama_barang": "Keyboard Mekanik RGB", //string
    "sku": "KEY-MECH-001-V2" //string (optional kode unik baru)
}
```
- **contoh Response (200 OK):**
```JSON
{
    "message": "Data barang diperbarui",
    "data": {
        "id": "6",
        "nama_barang": "Keyboard Mekanik RGB",
        "sku": "KEY-MECH-001-V2",
        "stok": "20",
        "created_at": {
            "date": "2026-01-11 20:25:56.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "updated_at": {
            "date": "2026-01-11 20:25:56.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        }
    }
}
```

5. Hapus Barang
- **Method:** DELETE
- **URL:** http://localhost:8080/api/items/{id}
- **Response Sukses (200 OK):**
```JSON
{
    "message": "Barang berhasil dihapus"
}
```
- **Response (404 Not Found):**
```JSON
{
    "status": 404,
    "error": 404,
    "messages": {
        "error": "Barang tidak ditemukan."
    }
}
```

6. Manajemen Stok: Menambah Stok (Stock In)
- **Method:** POST
- **URL:** http://localhost:8080/api/items/stock/{id}
- **contoh request:**
```JSON
{
    "type": "in",
    "qty": 10
}
```
- **Response Sukses (200 OK):**
```JSON
{
    "message": "Stok berhasil diperbarui",
    "current_stock": 30,
    "item": {
        "id": "6",
        "nama_barang": "Keyboard Mekanik RGB",
        "sku": "KEY-MECH-001-V2",
        "stok": 30,
        "created_at": {
            "date": "2026-01-11 20:25:56.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "updated_at": {
            "date": "2026-01-11 20:29:58.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        }
    }
}
```
- **Response (400 Bad Request):**
```JSON
{
    "status": 400,
    "error": 400,
    "messages": {
        "error": "Jumlah qty harus lebih dari 0."
    }
}
```

7. Manajemen Stok: Mengurangi Stok (Stock Out) & Validasi
- **Method:** POST
- **URL:** http://localhost:8080/api/items/stock/{id}
- **contoh request:**
```JSON
{
    "type": "out",
    "qty": 5
}
```
- **Response Sukses (200 OK):**
```JSON
{
    "message": "Stok berhasil diperbarui",
    "current_stock": 25,
    "item": {
        "id": "6",
        "nama_barang": "Keyboard Mekanik RGB",
        "sku": "KEY-MECH-001-V2",
        "stok": 25,
        "created_at": {
            "date": "2026-01-11 20:25:56.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "updated_at": {
            "date": "2026-01-11 20:36:44.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        }
    }
}
```
- **Response Jika stok saat ini 2, tapi diminta keluar 5.(400 Bad Request):**
```JSON
{
    "status": 400,
    "error": 400,
    "messages": {
        "error": "Stok tidak mencukupi untuk pengurangan ini."
    }
}
```

## Kode Status HTTP
- **kode 200:** Request berhasil diproses.
- **kode 201:** Data baru berhasil dibuat.
- **kode 400:** Input tidak valid, JSON salah format, atau Validasi Gagal (Stok minus).
- **kode 404:** URL salah atau ID Barang tidak ditemukan.
- **kode 500:** Terjadi kesalahan pada server/kode (Cek log).