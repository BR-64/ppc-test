# CLAUDE.md

Guidance for Claude Code when working in this repository.

## Project

PPC / **Prempracha** e-commerce shop. Laravel 9 (PHP 8.1) storefront + admin.

- **Stack:** Laravel 9, Livewire 2, Filament 2 (admin), Vite + Tailwind (front-end), Blade views.
- **Payments:** KBank + credit card, handled in `app/Http/Controllers/kCheckoutController.php`.
- **Live site:** https://shop-prempracha.com

## Local development (Windows + XAMPP)

- DB engine: XAMPP **MariaDB** on `127.0.0.1:3306`, user `root`, empty password.
- **Local database is `db_nov3`** (set in `.env` as `DB_DATABASE=db_nov3`). It holds the real working data and matches the app schema.
- First-time setup: `composer install`, copy `.env.example` → `.env`, `php artisan key:generate`, `npm install`.
- Run: `npm run dev` (terminal 1, Vite) + `php artisan serve` (terminal 2) → http://localhost:8000
- The app queries the DB **at boot** (`AppServiceProvider` runs `select distinct collection from p_products`), so `.env` DB config must be valid or *every* `artisan` command fails.

### ⚠️ Do NOT run `php artisan migrate` locally
Several migrations show as "Pending" (`banners`, `p_collections`, `product_uploads`, `announcements`) but those tables **already exist** in `db_nov3` with real data. Running migrate risks "table already exists" errors or data loss. The app runs fine without them.

## Deployment (git push-to-deploy)

Two git remotes:
- `origin` → GitHub (`github.com/BR-64/ppc-test.git`) — backup/history only, does **not** deploy.
- `live` → `ssh://root@27.254.144.62/var/www/ourrepos/ourapp` — a **bare repo on the server** that deploys.

Deploy = **`git push live adminv2`** (or `git push live HEAD` while on `adminv2`).
The bare repo's `post-receive` hook runs:
`git --work-tree=/var/www/ourapp --git-dir=/var/www/ourrepos/ourapp checkout adminv2 -f`

**Key facts:**
- The hook **only** checks out the `adminv2` branch. Pushing any other branch to `live` does nothing to the site.
- A bare `git push` goes to `origin` (GitHub), **not** the server — it does NOT deploy.
- The hook copies **tracked files only**. New/untracked files (e.g. images) must be committed first, or they won't deploy.
- The hook does **NOT** run build steps. Depending on the change, SSH into `/var/www/ourapp` and also run:
  - New composer packages → `composer install --no-dev`
  - Front-end asset changes (Vite CSS/JS) → `npm install && npm run build` (hook won't rebuild `public/build`)
  - DB schema changes → `php artisan migrate` (against **prod** DB)
  - Cached config/routes/views → `php artisan config:clear && route:clear && view:clear`

## Server

- Host: `root@27.254.144.62` (Ubuntu, hostname `smoootsv1`), key-based SSH (no password).
- Web root: `/var/www/ourapp/public`, served by **nginx** → PHP 8.1-FPM.
- **Production database is `db_22oct`** (different from local `db_nov3`). Never assume a migration run locally has run on prod — check separately.

## Conventions

- Local static assets go in `public/` and are referenced with `asset()` (e.g. `asset('css/style3.css')`). Logos live in `public/images/` and are referenced as `asset('images/<file>.png')` (migrated off the old external `smoootstudio.com` URLs).
- Header/nav markup + logo: `resources/views/layouts/navigation.blade.php`.
