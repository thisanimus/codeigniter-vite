# CodeIgniter 4 + Vite

## Helper

The vite helper is at: `/app/Helpers/vite_helper.php`

### Helper usage

use the following functions to output the css/js. The helper function will handle all the logic for checking if vite is running. If it is, it will serve the vite script, if not, it will serve the production assets.

```php
<?= vite_css(); ?>
```

```php
<?= vite_js(); ?>
```

## Gotchas

Both CodeIgniter and Vite have a special folder called `/public`, but they behave differently. I have reconfigured the vite public folder to `vite_public`.
