# Grundsalg Fagsystem

## Project Setup

Clone repo
```
git clone git@github.com:aakb/grundsalg-admin.git htdocs
```

Boot and SSH to the vagrant box

```
vagrant up
vagrant ssh
```

Install project  
_(Use defaults for all options except for 'database_passwod', use 'vagrant')_

```
cd /vagrant/htdocs
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## Users

Create: `php bin/console fos:user:create`  
Change password: `php bin/console fos:user:change-password`


## Import Legacy Data

To DROP and recreate database:  
In vagrant, run import script 

```
/vagrant/scripts/import_legacy_db.sh 
```

_(If this fails with node errors validate the node export app install in /vagrant/scripts/legacy)_
