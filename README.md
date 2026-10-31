Wikiportrait
============

## Installing
* In `common/` copy `config-sample.php` to `config.php` and change the appropiate values
* Run a `composer install` in `public/`. The libraries are installed in `private/vendor`, outside the webroot.

## Private files
Everything that must not be reachable through the browser lives in `private/`, next to the webroot
`public/`: the Composer libraries, the cron log, the Google credentials and other keys. The web server
(nginx) does not read `.htaccess`, so nothing inside `public/` is protected by it.

On the server the layout is:

```
websites/wikiportret.nl/
├── private/   # vendor/, cronlog.txt, credentials (*.json), keys
└── public/    # the contents of public/ from this repository
```

The location can be changed with `PRIVATE_DIR`, `VENDOR_DIR` and `CRON_LOGFILE` in `config.php`.