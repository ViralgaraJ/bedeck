# Bedeck International — Laravel website

Industrial engineering / product-catalogue site for **Bedeck International Engineering
Consultants** (Sri Lanka), rebuilt on **Laravel 12 + MySQL** with a backend admin for
managing products.

Content, copy and all product imagery were migrated from
`https://bedeck.tux-talks.space`.

## What's inside

| Area | Details |
|------|---------|
| **Public site** | Home, About, Services, Products (category index, per-category, product detail), Partners, Contact |
| **"Magical" homepage** | Dark cinematic hero with an animated glowing wireframe **globe** on `<canvas>` (ties to *"We bring you the best in the world"*), word-by-word headline reveal, cross-fading hero photos, a **scroll-scrubbed product showcase** (Apple-style pinned section), count-up statistics, and scroll-reveal on every section. Pure vanilla JS/CSS — no framework, no CDN. |
| **Admin** (`/admin`) | Session login, dashboard, full **Product CRUD** with enforced image dimensions, **Category CRUD**, **Enquiries** inbox (contact-form submissions), editable **Site Settings** (company details, hero copy, stats, contact info). |
| **Database** | MySQL. Tables: `categories`, `products`, `partners`, `services`, `enquiries`, `site_settings`, `users`. |
| **Seed data** | 92 products, 11 categories, 8 principal partners, 7 services, 1 admin user. |

## Local development

```bash
composer install
cp .env.example .env
# edit .env — set DB_* to a local MySQL database named "bedeck"
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Front site → http://127.0.0.1:8000
Admin → http://127.0.0.1:8000/admin/login (credentials from `ADMIN_EMAIL` / `ADMIN_PASSWORD` in `.env`)

## Deploying to cPanel

See **[DEPLOYMENT.md](DEPLOYMENT.md)** — step-by-step, including the "no SSH" path.

## Product image spec (enforced in the admin)

* JPG / PNG / WEBP, **square**, min **600×600**, recommended **1200×1200**, max 4 MB.
* Saved automatically as `1200×1200` + `600×600` WEBP in `public/uploads/products/`.

## Key files

```
app/Http/Controllers/            PageController, ProductController, Admin/*
app/Models/                      Product, Category, Partner, Service, Enquiry, SiteSetting
app/Services/ProductImageService.php   crop + WEBP resize pipeline
app/Http/Requests/Admin/ProductRequest.php   image dimension validation
database/seeders/                Category/Service/Partner/Product/Settings/AdminUser seeders
database/data/products.json      the 92 migrated products
resources/views/pages/           public Blade views (home = the animated one)
resources/views/admin/           admin Blade views
public/assets/css|js/            site.css, site.js, home.js, admin.css, admin.js
public/assets/images/products/   92 migrated product images (WEBP)
```
