# Grundsalg Fagsystem

## Project Setup

See [Readme](https://github.com/aakb/vagrant/blob/development/grundsalg/README.md)

## Cron jobs

Set up a cron job to invoke the `app:cron` console command every hour:

```
0 * * * * /home/www/grundsalg/htdocs/bin/console --env=prod app:cron
```

Adapt the path to `console` to match your actual setup.
