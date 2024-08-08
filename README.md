# Backend PHP

| App           | version |
|:--------------|---------|
| PHP           | 8.3     |
| Symfony       | 7.1.*   |
| Mysql         | 8.3.*   |

## Start

The project runs on docker. (https://docs.docker.com/engine/install/)

1) Install make  : `sudo apt-get install make`
2) Load fixtures : `make fixtures`
3) Start shell in php container : `make shell-php`

## Console commands

```bash
bin/console fulll:fleet-create <userId>                                   # Create a new fleet for a user
bin/console fulll:fleet-register-vehicle <fleetId> <vehiclePlateNumber>   # Register a vehicle to a fleet
bin/console fulll:fleet-localize-vehicle <fleetId> <vehiclePlateNumber>   # Localize a vehicle that is part of a fleet
```

## Behat

To run scenarios under behat :
```bash
make behat
```
