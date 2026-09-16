# Deploying Bedeck International to cPanel

This is a standard **Laravel 12** application. It needs **PHP 8.2, 8.3 or 8.4** with the
extensions `bcmath, ctype, curl, dom, fileinfo, gd, mbstring, openssl, pdo_mysql, tokenizer, xml, zip`
(all standard on modern cPanel/CloudLinux). No Node.js build step is required — the CSS/JS
in `public/assets/` is plain, hand-written and committed.

---

## 1. Create the database (cPanel → MySQL® Databases)

1. Create a database, e.g. `cpaneluser_bedeck`.
2. Create a database user with a strong password.
3. Add the user to the database with **ALL PRIVILEGES**.
4. Note the final names — cPanel prefixes them with your account name.

## 2. Upload the code

Upload the **whole project folder** (everything in this directory) to your account,
**above** `public_html`, e.g. to `/home/cpaneluser/bedeck`.

Do **not** upload `node_modules` (there is none) or `.env`. Do upload `vendor/`
(or run `composer install --no-dev --optimize-autoloader` on the server if Composer/SSH
is available).

### Point the domain at `public/`

**Preferred — set the Document Root.**
In cPanel → *Domains* (or *Addon Domains*), set the domain's **Document Root** to
`/home/cpaneluser/bedeck/public`. Done.

**If you cannot change the Document Root** (domain is locked to `public_html`):
copy the contents of `public/` (`index.php`, `.htaccess`, `favicon.ico`, `assets/`)
into `public_html/`, keeping the rest of the project at `/home/cpaneluser/bedeck`.
Edit `public_html/index.php` and change the two `require` paths from
`__DIR__.'/../'` to `__DIR__.'/../bedeck/'`:

```php
require __DIR__.'/../bedeck/vendor/autoload.php';
$app = require_once __DIR__.'/../bedeck/bootstrap/app.php';
```

Then add to `.env`:

```
PUBLIC_PATH=/home/cpaneluser/public_html
```

This tells Laravel to treat `public_html` as its public path, so uploaded
product images, datasheets, hero slides and the `storage:link` symlink are
written straight into `public_html` (where Apache actually serves them from)
instead of the inaccessible `bedeck/public`. Without this, admin uploads would
land in a folder the web server never reads and silently not appear on the
live site. Re-copy `index.php` / `assets/` into `public_html` only when those
static files change — runtime uploads need no re-syncing once `PUBLIC_PATH` is set.

## 3. Configure the environment

Copy `.env.example` to `.env` and fill in:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_DATABASE=cpaneluser_bedeck
DB_USERNAME=cpaneluser_bedeck
DB_PASSWORD=...
MAIL_* = your cPanel mailbox
ADMIN_EMAIL / ADMIN_PASSWORD = your first admin login
```

Then generate the app key. If you have **Terminal / SSH**:

```bash
cd ~/bedeck
php artisan key:generate
php artisan migrate --force --seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
```

**No SSH / Terminal?** A one-time web installer is built in:

1. Add a line to `.env`:  `SETUP_TOKEN=<paste a long random string>`
2. Visit **`https://your-domain.com/__install/<that-same-string>`** once in your browser.
   It runs `key:generate`, `migrate --force --seed`, `storage:link`, `config:cache`
   and prints the output.
3. **Delete the `SETUP_TOKEN` line from `.env`.** With no token set, that URL just 404s.

(Many cPanel accounts do have **Terminal** under the *Advanced* section — check there first;
the SSH commands above are cleaner.)

## 4. Permissions

Make these writable by the web server (cPanel usually does this automatically):

```
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/uploads
```

## 5. Log in and manage content

* Front site: `https://your-domain.com/`
* Admin: `https://your-domain.com/admin/login`
  Use the `ADMIN_EMAIL` / `ADMIN_PASSWORD` from `.env`.
  **Change the password after first login is recommended — add a real user via
  `php artisan tinker` or re-run the seeder with new env values.**

### Product images

The admin **Add / Edit product** screen enforces the upload spec:

| Rule | Value |
|------|-------|
| Formats | JPG, PNG, WEBP |
| Shape | square (1:1) — anything between 4:5 and 5:4 is accepted and centre-cropped |
| Min size | 600 × 600 px |
| Recommended | **1200 × 1200 px** |
| Max file size | 4 MB |

On save the image is centre-cropped and re-encoded to **two WEBP files**:
`1200×1200` (detail page) and `600×600` (catalogue grid), stored in
`public/uploads/products/`. The 92 seeded products use the original images in
`public/assets/images/products/`.

## 6. Updating later

```bash
git pull            # or re-upload changed files
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Re-seeding / resetting the catalogue

```bash
php artisan migrate:fresh --seed      # wipes everything, re-imports the 92 products
php artisan db:seed --class=ProductSeeder   # re-import products only (updates by slug)
php artisan db:seed --class=AdminUserSeeder # (re)create the admin from .env
```
