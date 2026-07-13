# Oppasabuy Render Deployment Package

Compatible target:

- Laravel 12.x
- PHP 8.2
- Render Docker Web Service
- Render PostgreSQL

## 1. Copy these files into your Laravel project root

```text
Dockerfile
.dockerignore
render.yaml
docker/
scripts/
```

Do not place them inside `public`.

## 2. Update AppServiceProvider

Merge the HTTPS logic from:

```text
examples/AppServiceProvider.php
```

into:

```text
app/Providers/AppServiceProvider.php
```

The important part is:

```php
use Illuminate\Routing\UrlGenerator;

public function boot(UrlGenerator $url): void
{
    if ($this->app->environment('production')) {
        $url->forceScheme('https');
    }
}
```

## 3. Generate your Laravel application key locally

```powershell
php artisan key:generate --show
```

Copy the complete `base64:...` value.

## 4. Commit and push

```powershell
git add Dockerfile .dockerignore render.yaml docker scripts app/Providers/AppServiceProvider.php
git commit -m "Add Render deployment configuration"
git push
```

## 5. Create the services on Render

In Render:

1. Click **New +**
2. Choose **Blueprint**
3. Connect `shhhhxc/oppasabuy`
4. Select `render.yaml`
5. Create the Blueprint

Render will create:

- `oppasabuy` Docker web service
- `oppasabuy-db` PostgreSQL database

## 6. Add required secret values

Open the `oppasabuy` web service and set:

```text
APP_KEY=base64:YOUR_GENERATED_KEY
APP_URL=https://YOUR-SERVICE-NAME.onrender.com
ASSET_URL=https://YOUR-SERVICE-NAME.onrender.com
```

After setting them, trigger **Manual Deploy → Deploy latest commit**.

## 7. Important database note

Your local database is MySQL, but this package uses Render PostgreSQL.

Laravel migrations that use ordinary schema methods usually work on both databases. Raw MySQL SQL, MySQL-only column definitions, and imports created specifically for MySQL may need adjustment.

The startup script automatically runs:

```text
php artisan migrate --force
```

Do not add `migrate:fresh`, because it deletes all data.

## 8. Important upload-storage note

A free Render web service has an ephemeral filesystem. Product images, rider documents, banners, and other files uploaded after deployment can disappear after a restart, redeploy, or spin-down.

For a temporary client demo, upload sample public assets through Git. For reliable runtime uploads, move files to Cloudinary, Amazon S3, or another object-storage service.

## 9. Free-plan behavior

The site may sleep after inactivity. The first visit after sleeping can take around one minute.

A free Render PostgreSQL database is temporary and currently expires after 30 days, so export important data before that deadline.
