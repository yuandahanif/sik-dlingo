<p align="center"><a href="https://dlingo-desa.my.id" target="_blank"><img src="public/images/logo-bantul-sid.png" width="400" alt="Logo Bantul"></a></p>

## Preview

<p align="center"><a href="https://dlingo-desa.my.id" target="_blank"><img src="public/images/readme/dashboard.png" width="800" alt="Dashboard"></a></p>
<p align="center"><a href="https://dlingo-desa.my.id" target="_blank"><img src="public/images/readme/penduduk.png" width="800" alt="Penduduk"></a></p>
<p align="center"><a href="https://dlingo-desa.my.id" target="_blank"><img src="public/images/readme/kk.png" width="800" alt="KK"></a></p>
<p align="center"><a href="https://dlingo-desa.my.id" target="_blank"><img src="public/images/readme/dusun.png" width="800" alt="Dusun"></a></p>
<p align="center"><a href="https://dlingo-desa.my.id" target="_blank"><img src="public/images/readme/rt.png" width="800" alt="RT"></a></p>

## Development

### Installation
```bash
composer install
npm install
```

### Setup sail
```bash
./vendor/bin/sail up
```

### Restore database
Aside form using migration, you can also restore database from sql file.
```bash
mysql -u root --host=127.0.0.1 --port=3306 -v --init-command="SET SESSION FOREIGN_KEY_CHECKS=0" database_name < filename.sql
```

```bash
php artisan serve
```

## Deployment
compress
```bash
tar -czvf dlingo-sik.tar.gz dlingo-sik
```
extract
```bash
tar -xzvf nama.tar.gz
```