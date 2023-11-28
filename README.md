To use Laravel Two-Factor.

Publish Vendor Files

    php artisan vendor:publish --provider="Northfire\TwoFactor\TwoFactorServiceProvider"

### Run Migrations

    php artisan migrate

### Add to your Route middleware

```
'twofactor' => \App\Http\Middleware\TwoFactor::class,