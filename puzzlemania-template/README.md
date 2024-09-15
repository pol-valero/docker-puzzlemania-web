# Summary

* `docker-compose.yaml` file with a
    * php-fpm image
    * mysql-image
    * nginx image
    * adminer image
    * barcode image template
* `docker-compose.intel.yaml` file with a
  * barcode image for Intel CPU's
* `docker-compose.arm.yaml` file with a
  * barcode image for ARM CPU's
* `docker-entrypoint-initdb.d/schema.sql` so when you run `docker compose up` a database is created following such
  schema
* The PHP code to start your application with
    * `composer.json` file
    * `templates` folder
    * `src` folder
    * `config` folder
    * `public` folder
* `.env` file
* `cypress` folder

## How to create and destroy the services

### INTEL Architecture (most Windows and Mac Operating Systems)
```bash
docker compose up
```

### ARM Architecture (Apple Silicon Mac)
```bash
docker compose -f docker-compose.yaml -f docker-compose.arm.yaml up
```
### Destroy

Use `docker compose down` to destroy them.

