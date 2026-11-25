# Laravel Victos Status

Tenant-scoped refund/status records, a Laravel admin area, and a standalone JavaScript check-status widget.

## Requirements

- PHP 8.2 or 8.3
- Laravel 12

## Install

The package is auto-discovered by Laravel. Configure the authenticatable model in `config/victos-status.php` if it is not `App\Models\User`.

The configured user model/table must have `role`, `is_active`, `public_tenant_key`, and `allowed_domains` columns. Casting `is_active` to boolean and `allowed_domains` to array is recommended (the package can also read a raw JSON `allowed_domains` value). The package migrations add these columns to the conventional Laravel `users` table and backfill existing status data to the existing Victos super-admin (or first user).

```sh
php artisan vendor:publish --tag=victos-status-config
php artisan vendor:publish --tag=victos-status-assets
php artisan migrate
```

Create the first super-admin through the host application's existing user provisioning/seeder with `role = super_admin` and `is_active = true`. Do not ship a shared default password.

Routes default to `/victos/login`, `/victos`, and `/check-status`. Configure `admin_prefix`, `status_prefix`, and middleware in `config/victos-status.php`.

## Tenant setup

Each user/admin is a separate tenant. Card settings and status records are scoped by `user_id`. Only a super-admin can create admin users; regular admins cannot view or modify other tenants' records or settings.

After signing in, use **Profile & widget setup** to set allowed website hostnames. Add one host per line (for example `example.com` and `www.example.com`; use `localhost:5173` for local front-end development). The browser sends the requesting origin, which is checked before tenant data is returned.

The tenant key identifies which tenant the public widget requests; it is not a password or authorization credential. The public endpoint intentionally returns only data configured for that tenant and allowed origin.

## Embed the widget

Include a target wherever the button should appear. Use the tenant's public key and the package's public endpoint:

```html
<div data-victos-check-status
     data-tenant-key="YOUR_PUBLIC_TENANT_KEY"
     data-endpoint="https://status.example.com/check-status"></div>
<script src="https://status.example.com/vendor/victos-status/victos-check-status.js" defer></script>
<script>
  window.addEventListener('DOMContentLoaded', function () {
    window.VictosCheckStatus.init();
  }, { once: true });
</script>
```

The script is framework independent: it inserts the button into each matching target, places the accessible modal under `document.body`, fetches the status without a page reload, and renders the server-generated card. Mount elements may use the data attribute, or callers may pass a selector or DOM element:

```js
const widget = window.VictosCheckStatus.init({
  target: document.querySelector('#status-slot'),
  endpoint: 'https://status.example.com/check-status',
  tenantKey: 'YOUR_PUBLIC_TENANT_KEY'
});
// For Vue/React, call widget.destroy() during component unmount.
```

The package does not provide or control any Connect/wallet button.

## Development in this repository

This project consumes the package as a local Composer path dependency. The host-specific homepage is `resources/views/sparklo.blade.php`; no package route or script assumes a particular host page.

To publish the distributable JS after changing it:

```sh
php artisan vendor:publish --tag=victos-status-assets --force
```
