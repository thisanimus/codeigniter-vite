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

## CSS and JS

Vite streams from the `/src/main.js` file. As long as you write your code there, or import it there, vite will know about it and will stream those changes to the browser.

## Gotchas

Both CodeIgniter and Vite have a special folder called `/public`, but they behave differently. I have reconfigured the vite public folder to `vite_public`.
