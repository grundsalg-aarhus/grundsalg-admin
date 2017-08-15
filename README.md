# Grundsalg Fagsystem

[![CircleCI](https://circleci.com/gh/grundsalg-aarhus/grundsalg-admin.svg?style=svg&circle-token=de75551962f2199272098c112abf4a3bf0b4d674)](https://circleci.com/gh/grundsalg-aarhus/grundsalg-admin)

## Project Setup

See [Readme](https://github.com/aakb/vagrant/blob/development/grundsalg/README.md)

## Cron jobs

Set up a cron job to invoke the `app:cron` console command every hour:

```
0 * * * * /home/www/grundsalg/htdocs/bin/console --env=prod app:cron
```

Adapt the path to `console` to match your actual setup.
