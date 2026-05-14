# CLAUDE.md — Laravel Project Instructions

Panduan ini dibaca otomatis oleh Claude Code di setiap sesi.
Ikuti semua instruksi di bawah secara konsisten tanpa perlu diingatkan ulang.

---

## 🧱 Tech Stack

- **Framework:** Laravel 12
- **PHP:** 8.3 (gunakan fitur modern: readonly, enums, typed properties, match expression, fibers)
- **Database:** MySQL / MariaDB
- **Package Manager:** Composer (PHP), npm (JS assets jika ada)
- **Testing:** Pest PHP (bukan PHPUnit langsung)
- **Linting:** Laravel Pint (PSR-12 + Laravel opinionated)

---

## 📁 Struktur Direktori

Ikuti konvensi Laravel standar:

```
app/
  Http/
    Controllers/     → Hanya HTTP handling, tidak ada business logic
    Requests/        → Form Request untuk validasi
    Resources/       → API Resource untuk transformasi response
    Middleware/
  Models/            → Eloquent models
  Services/          → Business logic utama (buat jika logika kompleks)
  Actions/           → Single-purpose action classes (opsional, untuk operasi atomik)
  Enums/             → PHP 8.1+ Enums
  Observers/         → Model observers
  Policies/          → Authorization policies
  Exceptions/        → Custom exceptions
database/
  migrations/
  seeders/
  factories/
resources/
  views/             → Blade templates
routes/
  web.php
  api.php
  console.php
tests/
  Feature/
  Unit/
```

---

## ✅ Konvensi Kode

### Umum
- Selalu gunakan **strict typing**: tambahkan `declare(strict_types=1);` di setiap file PHP
- Gunakan **typed properties** dan **return types** di semua method
- Gunakan **PHP 8.3 enums** untuk konstanta yang terdefinisi (status, role, tipe, dll)
- Prefer **readonly properties** untuk value objects dan DTOs
- Gunakan **match expression** daripada switch jika memungkinkan
- Hindari `array` untyped — gunakan typed array, Collection, atau DTO

### Naming
- Controller: `UserController`, `ProductController` (singular, PascalCase)
- Model: `User`, `Product` (singular, PascalCase)
- Migration: `create_users_table`, `add_status_to_orders_table` (snake_case)
- Route name: `users.index`, `products.show` (dot notation, plural resource)
- Method: `camelCase` — `getUserById()`, `sendWelcomeEmail()`
- Variable: `camelCase` — `$userId`, `$orderTotal`
- Env key: `UPPER_SNAKE_CASE` — `MAIL_HOST`, `DB_DATABASE`

### Controllers
- Gunakan **single-action controllers** (`__invoke`) untuk aksi yang kompleks
- Gunakan **resource controllers** untuk CRUD standar
- Controller hanya boleh: validasi request, panggil service/action, return response
- Jangan taruh query Eloquent langsung di controller — delegasikan ke repository atau service

```php
// ✅ Benar
public function store(StoreProductRequest $request, CreateProductAction $action): JsonResponse
{
    $product = $action->handle($request->validated());
    return response()->json(new ProductResource($product), 201);
}

// ❌ Hindari
public function store(Request $request): JsonResponse
{
    $validated = $request->validate([...]);
    $product = Product::create($validated);
    return response()->json($product);
}
```

### Models
- Selalu definisikan `$fillable` atau `$guarded`
- Definisikan **casts** untuk tipe data (terutama enums, datetime, json, boolean)
- Gunakan **scopes** untuk query yang sering diulang
- Definisikan **relationships** dengan return type yang jelas

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'status', 'user_id'];

    protected $casts = [
        'status' => ProductStatus::class,
        'price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query): void
    {
        $query->where('status', ProductStatus::Active);
    }
}
```

### Migrations
- Selalu tambahkan `->comment()` pada kolom yang tidak jelas tujuannya
- Gunakan `foreignIdFor(Model::class)` bukan `unsignedBigInteger`
- Selalu buat migration rollback yang benar di method `down()`
- Tambahkan index untuk kolom yang sering di-query atau di-filter

```php
// ✅ Benar
$table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
$table->string('status')->default('active')->comment('active|inactive|suspended');
$table->index(['status', 'created_at']);
```

### Validasi
- Selalu gunakan **Form Request** — jangan validasi di controller
- Gunakan **Rule objects** atau **closure rules** untuk validasi kompleks
- Kembalikan error yang informatif dengan `messages()` di Form Request

### Eloquent Query
- Hindari **N+1 query** — selalu eager load relasi dengan `with()`
- Gunakan `select()` untuk menghindari fetch kolom yang tidak perlu
- Gunakan `chunk()` atau `lazy()` untuk pemrosesan data besar
- Gunakan `firstOrCreate`, `updateOrCreate` bila sesuai

```php
// ✅ Selalu eager load
$orders = Order::with(['user', 'items.product'])->latest()->paginate(20);

// ❌ Hindari N+1
$orders = Order::all(); // lalu $order->user di dalam loop
```

---

## 🔐 Security

- Selalu gunakan **mass assignment protection** (`$fillable` / `$guarded`)
- Gunakan **Policy** untuk authorization — jangan hardcode role check di controller
- Gunakan `$request->validated()` — bukan `$request->all()`
- Sanitasi output di Blade dengan `{{ }}` (bukan `{!! !!}` kecuali benar-benar perlu)
- Simpan secret di `.env` — tidak pernah hardcode credential
- Gunakan `Hash::make()` untuk password — tidak pernah simpan plain text
- Rate limit endpoint sensitif dengan `throttle` middleware

---

## 🧪 Testing (Pest PHP)

- Semua fitur baru **wajib** disertai test
- Gunakan **Feature Test** untuk endpoint HTTP dan alur bisnis
- Gunakan **Unit Test** untuk service, action, dan helper
- Gunakan `RefreshDatabase` trait untuk test yang butuh database
- Gunakan factories untuk generate data test
- Penamaan test: deskriptif dalam Bahasa Inggris

```php
it('creates a product successfully', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/products', [
            'name' => 'Test Product',
            'price' => 99.99,
        ]);

    $response->assertCreated()
        ->assertJsonPath('data.name', 'Test Product');

    $this->assertDatabaseHas('products', ['name' => 'Test Product']);
});
```

---

## 🚀 Perintah yang Sering Dipakai

```bash
# Development
php artisan serve                          # Jalankan dev server
php artisan tinker                         # REPL interaktif

# Generate
php artisan make:model Product -mfsc       # Model + migration + factory + seeder + controller
php artisan make:request StoreProductRequest
php artisan make:resource ProductResource
php artisan make:policy ProductPolicy --model=Product
php artisan make:enum ProductStatus

# Database
php artisan migrate                        # Jalankan migration
php artisan migrate:fresh --seed          # Reset + seed (HATI-HATI di production!)
php artisan db:seed --class=ProductSeeder

# Testing
php artisan test                           # Jalankan semua test
php artisan test --filter="product"        # Filter test spesifik
./vendor/bin/pest --coverage              # Test dengan coverage report

# Maintenance
php artisan pint                          # Format kode (Laravel Pint)
php artisan optimize:clear               # Clear semua cache
php artisan route:list --name=api        # Lihat daftar route
```

---

## ⚠️ Aturan Tambahan untuk Claude

1. **Jangan pernah** gunakan `DB::statement` raw kecuali benar-benar tidak ada cara Eloquent
2. **Selalu** sertakan `declare(strict_types=1)` di file PHP baru
3. **Selalu** tanya dulu sebelum menjalankan `migrate:fresh` — bisa hapus data
4. **Selalu** buat atau update test saat membuat fitur baru
5. **Gunakan** Laravel helper (`collect()`, `now()`, `config()`) daripada PHP native bila ada
6. **Jangan** install package baru tanpa konfirmasi terlebih dahulu
7. Ketika membuat migration, **selalu** cek apakah tabel sudah ada sebelumnya
8. Gunakan `php artisan pint` setelah menulis kode baru untuk konsistensi style
9. Jika ada ambiguitas dalam requirement, **tanya dulu** sebelum mengimplementasi
10. Untuk perubahan yang mempengaruhi banyak file, **buat rencana dulu** sebelum eksekusi

---

## 📝 Gaya Respons

- Berikan penjelasan singkat **sebelum** menulis kode (apa yang akan dilakukan dan mengapa)
- Jika ada beberapa pendekatan, **sebutkan trade-off**-nya secara ringkas
- Gunakan **komentar inline** di kode untuk bagian yang tidak obvious
- Jika menemukan potensi bug atau masalah di kode yang ada, **sebutkan** meski tidak ditanya
