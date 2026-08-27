# CI / CD

## CI — `.github/workflows/ci.yml`

Runs on every push and pull request to `main`. No configuration or secrets needed.

For PHP 8.2 / 8.3 / 8.4 it:

1. installs Composer dependencies (cached),
2. builds a fresh SQLite DB and runs `migrate --seed` (proves the 92-product seed works),
3. builds the production caches (`config:cache`, `route:cache`, `view:cache`) to catch
   things that only break when cached,
4. checks code style with **Pint**,
5. runs **PHPUnit**.

---

## CD — two options, pick one

The app writes uploads to `public/uploads/**` and keeps its own `.env` on the server,
so **both** deploy paths exclude those from being overwritten.

### Option A — GitHub Actions over SSH  (`.github/workflows/deploy.yml`)

Best if your cPanel plan has **SSH access** (cPanel → *SSH Access*). GitHub builds the
production `vendor/`, rsyncs the code to the server, then runs migrations + cache rebuild.

**One-time setup**

1. **cPanel → SSH Access → Manage SSH Keys → Generate a new key** (ed25519, no passphrase).
   Authorize the public key. Download the **private** key.
2. In cPanel, create the app folder once, e.g. `/home/USER/bedeck`, and put a real
   **`.env`** in it (copy `.env.example`, fill DB + mail + `ADMIN_*`, run
   `php artisan key:generate`). CI never touches `.env`.
3. Point the domain's **Document Root** at `/home/USER/bedeck/public`.
4. In GitHub → **Settings → Secrets and variables → Actions**:

   | Type      | Name          | Example |
   |-----------|---------------|---------|
   | Variable  | `DEPLOY_TARGET` | `cpanel-ssh` |
   | Secret    | `SSH_HOST`    | `123.45.67.89` or `server.host.com` |
   | Secret    | `SSH_USER`    | your cPanel username |
   | Secret    | `SSH_PORT`    | `22` (some hosts use a custom port) |
   | Secret    | `SSH_KEY`     | contents of the **private** key file |
   | Secret    | `DEPLOY_PATH` | `/home/USER/bedeck` |
   | Secret    | `PHP_BIN`     | `/opt/cpanel/ea-php83/root/usr/bin/php` (from cPanel MultiPHP) |

Until `DEPLOY_TARGET` is set the deploy job shows as **skipped**, not failed.
After that, every push to `main` that passes CI deploys automatically. You can also run it
by hand from the **Actions** tab (*Run workflow*).

### Option B — cPanel Git Version Control  (`.cpanel.yml`)

Best if you have **no SSH** but the host has Composer. cPanel pulls the repo itself.

1. cPanel → **Git™ Version Control → Create** → clone
   `https://github.com/ViralgaraJ/bedeck.git` (add a read-only **deploy key** in GitHub for
   a private repo, or make the repo public).
2. Edit `.cpanel.yml` — set `DEPLOYPATH` and the `PHP` path for your account — commit & push.
3. In cPanel's Git panel, click **Manage → Pull or Deploy → Deploy HEAD Commit** whenever
   you want to ship. (cPanel has no push-webhook, so this step is manual — or trigger it
   from a tiny Actions job that curls the cPanel API / SSHes in.)
4. First deploy also copies `.env.example` → `.env`; open it in File Manager and fill in
   the real DB / mail / admin values, then Deploy once more.

---

## Rollback

```bash
# on the server (SSH) or via .cpanel.yml on an older commit
git -C <repo> checkout <previous-good-sha>
php artisan migrate:rollback   # only if the bad deploy added migrations
php artisan optimize:clear && php artisan optimize
```
