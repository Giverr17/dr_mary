# Dr. Uhunoma M. Isibor — Portfolio Website

**Stack:** Laravel 13 · Livewire v4 · Tailwind CSS v4

## Setup

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Database
php artisan migrate:fresh --seed

# 4. Storage symlink (required for images/uploads)
php artisan storage:link

# 5. Run development server
php artisan serve
# In a separate terminal:
npm run dev
```

## Admin Access

Default admin credentials (seeded):

- **Email:** admin@druhunoma.com
- **Password:** password

To promote an existing user to admin:

```bash
php artisan make:admin user@example.com
```

## Project Structure

| Directory | Purpose |
|---|---|
| `app/Models/` | 12 Eloquent models with PHPDoc blocks |
| `app/Http/Controllers/Pages/` | Invokable page controllers |
| `resources/views/pages/` | Public Blade views |
| `resources/views/manage/` | Admin dashboard views |
| `resources/views/components/` | Layouts, partials, Livewire single-file components (`⚡`) |
| `database/seeders/data/` | JSON seed data files |

## Admin Panel

Access at `/manage` (requires login at `/login`).

Manages: Profile, Publications, Events, Services, Messages.
