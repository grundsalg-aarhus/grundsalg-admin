# Grundsalg Fagsystem

## Project Setup

@TODO


## Import Legacy Data

On host, copy DB Dump from Dropbox

```
cp ~/Dropbox*/Projekter/Grundsalgsløsning/resources/SYM_GrundSalg.mysql .
```

To DROP and recreate database:  
In vagrant, run import script 

```
/vagrant/scripts/import_legacy_db.sh 
```