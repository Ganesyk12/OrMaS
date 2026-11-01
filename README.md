# Cameleon — Organize Management System (Karang Taruna)

Sistem administrasi **Karang Taruna** (organisasi kemasyarakatan pemuda) berbasis **Modular Monolith** dengan Laravel 12 + SQLite.

## Arsitektur: Modular Monolith

Semua kode berada dalam satu repositori (satu composer project), tapi diorganisir per **modul / bounded context** di bawah `app/Modules/`. Setiap modul mandiri memiliki model, migration, seeder, controller, resource, dan route-nya sendiri. Nantinya setiap modul dapat diekstrak menjadi layanan terpisah tanpa menulis ulang logika inti.

```
app/
├── Models/User.php                 # Model autentikasi (Laravel Authenticatable)
├── Modules/
│   ├── Shared/                     # Kernel bersama (exception, trait, antarmuka)
│   │   ├── Exceptions/ModuleException.php
│   │   ├── Traits/
│   │   └── Interfaces/
│   ├── Organization/               # Organisasi (RT/RW/Kelurahan/Kecamatan)
│   │   ├── Models/OrganizationModel.php
│   │   ├── Http/Controllers/OrganizationController.php
│   │   ├── Http/Resources/OrganizationResource.php
│   │   ├── Routes/web.php
│   │   ├── Database/Migrations/
│   │   ├── Database/Seeders/
│   │   └── OrganizationServiceProvider.php
│   ├── Member/                     # Anggota organisasi
│   ├── Role/                       # Jabatan / Role kepengurusan
│   ├── UserAccess/                 # Hak akses user per modul & izin
│   ├── User/                       # Manajemen pengguna sistem
│   ├── Agenda/                     # Agenda rapat (milik Meeting)
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
| Agenda | `agendas` | Agenda rapat (milik Meeting) |
| Activity | `activities` | Kegiatan / program kerja |
| Meeting | `meetings` | Rapat |
| Finance | `finances` | Transaksi keuangan (pemasukan / pengeluaran) |

## Persyaratan

- PHP 8.2+
- Composer
- SQLite (default) atau MySQL/PostgreSQL via `.env`

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

Akun admin default (dari seeder):
- Email: `admin@karangtaruna.id`
- Password: `password`

## Route (API REST JSON)

Semua endpoint mengembalikan JSON. Contoh:

```http
GET    /organizations          # daftar organisasi
POST   /organizations          # buat organisasi
GET    /organizations/{id}     # detail organisasi
PUT    /organizations/{id}     # perbarui
DELETE /organizations/{id}     # hapus (soft delete)

GET    /roles
POST   /roles
GET    /roles/{id}
PUT    /roles/{id}
DELETE /roles/{id}

GET    /members
POST   /members
GET    /members/{id}
PUT    /members/{id}
DELETE /members/{id}

GET    /activities
POST   /activities
GET    /activities/{id}
PUT    /activities/{id}
DELETE /activities/{id}

GET    /meetings
POST   /meetings
GET    /meetings/{id}
PUT    /meetings/{id}
DELETE /meetings/{id}

GET    /meetings/{meeting}/agendas          # daftar agenda rapat
POST   /meetings/{meeting}/agendas          # buat agenda
GET    /meetings/{meeting}/agendas/{agenda}
PUT    /meetings/{meeting}/agendas/{agenda}
DELETE /meetings/{meeting}/agendas/{agenda}

GET    /finances
POST   /finances
GET    /finances/{id}
PUT    /finances/{id}
DELETE /finances/{id}

GET    /users
POST   /users
GET    /users/{id}
PUT    /users/{id}
DELETE /users/{id}

GET    /user-accesses
POST   /user-accesses
GET    /user-accesses/{id}
PUT    /user-accesses/{id}
DELETE /user-accesses/{id}
```

## Pengujian

```bash
# Jalankan seluruh test suite
php artisan test

# Hasilkan laporan coverage (jika Xdebug tersedia)
php artisan test --coverage
```

Tes mencakup CRUD lengkap (index, store, show, update, destroy, soft delete) untuk setiap modul di `tests/Feature/`.

## Pendaftaran Modul

Semua `*ServiceProvider` didaftarkan di `bootstrap/providers.php` (Laravel 12 style). Autoload namespace `Modules\` dipetakan ke `app/Modules/` di `composer.json`.

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

## Catatan Implementasi

- Semua tabel pakai `softDeletes()` (kecuali `users` yang dihapus permanen dalam contoh ini).
- Model menggunakan `HasFactory` dengan factory di `database/factories/`.
- Response API diformat melalui `JsonResource` per modul.
- Validasi request ditulis inline di controller.

## Lisensi

MIT