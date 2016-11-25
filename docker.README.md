# Create database

```
docker-compose exec web bin/console doctrine:database:create
docker-compose exec web bin/console doctrine:migrations:migrate --no-interaction
```

# Create admin user

```
docker-compose exec web bin/console fos:user:create admin admin@example.com password
docker-compose exec web bin/console fos:user:promote admin ROLE_SUPER_ADMIN
```

# Load existing data

@TODO: Make this work …

```
docker cp ../SYM_GrundSalg.mysql $(docker-compose ps -q database):/tmp
docker-compose exec database /bin/bash
```

*Warning*: This takes a bit of time …

```
mysql --user=grundsalg --password=grundsalg grundsalg < /tmp/SYM_GrundSalg.mysql
exit
```

*Note*: This is a nicer solution, but it's very slow:

```
docker-compose exec database mysql --user=grundsalg --password=grundsalg --verbose grundsalg < ../SYM_GrundSalg.mysql
```
