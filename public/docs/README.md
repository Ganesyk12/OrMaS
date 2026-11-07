# Cameleon — Organize Management System (Karang Taruna)

Sistem administrasi **Karang Taruna** (organisasi kemasyarakatan pemuda) berbasis **Modular Monolith** dengan Laravel 12 + SQLite.

## Ringkasan Project

| Item | Detail |
|------|--------|
| Nama Project | Cameleon |
| Tujuan | Administrasi Karang Taruna (RT/RW/Kelurahan/Kecamatan) |
| Framework | Laravel 12 |
| PHP | 8.2+ |
| Database | SQLite (default), dukungan MySQL/PostgreSQL via `.env` |
| Arsitektur | Modular Monolith |
| Status | Siap pengembangan (MVP teruji) |

## Arsitektur Modular Monolith

Semua kode berada dalam satu repositori (satu Composer project), tapi diorganisir per **modul / bounded context** di bawah `app/Modules/`. Setiap modul mandiri memiliki model, migration, seeder, controller, resource, dan route-nya sendiri. Nantinya setiap modul dapat diekstrak menjadi layanan terpisah tanpa menulis ulang logika inti.

```
app/
├── Models/User.php                 # Model autentikasi (Laravel Authenticatable)
├── Modules/
│   ├── Shared/                     # Kernel bersama (exception, trait, antarmuka)
│   │   ├── Exceptions/ModuleException.php
│   │   ├── Traits/
│   │   └── Interfaces/
│   ├── Organization/               # Organisasi (RT/RW/Kelurahan/Kecamatan)
│   ├── Member/                     # Anggota organisasi
│   ├── Role/                       # Jabatan / Role kepengurusan
│   ├── UserAccess/                 # Hak akses user per modul & izin
│   ├── User/                       # Manajemen pengguna sistem
│   ├── Activity/                   # Kegiatan / Program kerja
│   ├── Meeting/                    # Rapat
│   └── Finance/                    # Transaksi keuangan
├── Http/Controllers/
└── Providers/
```

### Daftar Modul

| Modul | Tabel | Deskripsi |
|-------|-------|-----------|
| Organization | `organizations` | Organisasi Karang Taruna (rt, rw, kelurahan, kecamatan) |
| Role | `roles` | Jabatan kepengurusan (Ketua, Sekretaris, Bendahara, Anggota, dst) |
| Member | `organization_members` | Anggota organisasi (terkait user & role) |
| UserAccess | `user_accesses` | Hak akses user per modul & izin (view/create/edit/delete) |
| User | `users` | Pengguna sistem (autentikasi) |
| Activity | `activities` | Kegiatan / program kerja |
| Meeting | `meetings` | Rapat |
| Finance | `finances` | Transaksi keuangan (pemasukan / pengeluaran) |

## Instalasi

```bash
# 1. Masuk ke direktori project
cd cameleon

# 2. Install dependensi PHP
composer install

# 3. Salin dan konfigurasikan environment
cp .env.example .env
# (default sudah menggunakan SQLite di database/database.sqlite)

# 4. Generate application key
php artisan key:generate

# 5. Jalankan migrasi dan seeder
php artisan migrate --seed

# 6. (Opsional) Install dan build aset frontend
npm install && npm run build

# 7. Jalankan server development
php artisan serve
```

### PostgreSQL dengan Schema Custom

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nama_database
DB_USERNAME=postgres
DB_PASSWORD=password
DB_SCHEMA=ORMS
```

`DB_SCHEMA` dipakai sebagai `search_path` PostgreSQL. Schema custom dibuat otomatis saat migrasi jika belum ada. Gunakan `DB_SCHEMA="ORMS,public"` jika perlu fallback ke schema `public`.

### Akun Admin Default (dari seeder)

- Email: `admin@karangtaruna.id`
- Password: `password`

## Route (API REST JSON)

Semua endpoint mengembalikan JSON. Contoh:

```http
# Organization
GET    /organizations          # daftar organisasi
POST   /organizations          # buat organisasi
GET    /organizations/{id}     # detail organisasi
PUT    /organizations/{id}     # perbarui
DELETE /organizations/{id}     # hapus (soft delete)

# Role
GET    /roles
POST   /roles
GET    /roles/{id}
PUT    /roles/{id}
DELETE /roles/{id}

# Member
GET    /members
POST   /members
GET    /members/{id}
PUT    /members/{id}
DELETE /members/{id}

# Activity
GET    /activities
POST   /activities
GET    /activities/{id}
PUT    /activities/{id}
DELETE /activities/{id}

# Meeting
GET    /meetings
POST   /meetings
GET    /meetings/{id}
PUT    /meetings/{id}
DELETE /meetings/{id}

# Finance
GET    /finances
POST   /finances
GET    /finances/{id}
PUT    /finances/{id}
DELETE /finances/{id}

# User
GET    /users
POST   /users
GET    /users/{id}
PUT    /users/{id}
DELETE /users/{id}

# UserAccess
GET    /user-accesses
POST   /user-accesses
GET    /user-accesses/{id}
PUT    /user-accesses/{id}
DELETE /user-accesses/{id}
```

## Pendaftaran Modul

Semua `*ServiceProvider` didaftarkan di `bootstrap/providers.php` (gaya Laravel 12). Autoload namespace `Modules\` dipetakan ke `app/Modules/` di `composer.json`.

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Modules\\": "app/Modules/"
    }
}
```

Setiap `ModuleServiceProvider` memuat route dan migration miliknya:

```php
$this->loadRoutesFrom(__DIR__.'/Routes/web.php');
$this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
```

## Pengujian

```bash
# Jalankan seluruh test suite
php artisan test

# Hasilkan laporan coverage (jika Xdebug tersedia)
php artisan test --coverage
```

Tes mencakup CRUD lengkap (index, store, show, update, destroy, soft delete) untuk setiap modul di `tests/Feature/`. Saat ini **47 tes lulus (92 assertions)**.

## Catatan Implementasi

- Semua tabel domain pakai `softDeletes()` (kecuali `users` yang dihapus permanen dalam contoh ini).
- Model menggunakan `HasFactory` dengan factory di `database/factories/`.
- Response API diformat melalui `JsonResource` per modul.
- Validasi request ditulis inline di controller.
- Implicit route model binding diganti dengan `findOrFail()` eksplisit untuk deterministik.
- Foreign key menghubungkan modul satu sama lain (misal `organization_members` → `organizations`, `users`, `roles`).

## Diagram Alur Kerja

Diagram workflow pendaftaran anggota tersedia di:

- [Pendaftaran Anggota Karang Taruna — Workflow Diagram](.archify/workflow-member-registration-20261101-063000/member-registration.html)

## Lisensi

MIT