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


## Code analyses and tests

* [cs-fixer](https://cs.symfony.com/) :
    * dry-run : `make cs-fixer-dry-run`
    * run : `make cs-fixer`
* [phpstan](https://phpstan.org/) : `make phpstan`
* [psalm](https://psalm.dev/) : `make psalm`
* [rector](https://getrector.com/) :
    * dry-run: `make rector-dry-run`
    * run: `make rector`
* [Behat](https://docs.behat.org/) : `make behat` 

## STEP 3

### For code quality, you can use some tools : which one and why (in a few words) ?
I use :
* cs-fixer to fix the code style.  
* phpstan and psalm for static analyses of code that help to anticipate some bugs.  
* rector to have code up to date according to last improvements from php and symfony.  
* phpunit for unit tests.

### you can consider to setup a ci/cd process : describe the necessary actions in a few words
I create a pipeline with 4 steps:
1) One wich can be runed manually to build the docker image for php dedicateted to dev and tests
2) Another with jobs to run the tests (unit, functional) and the static analyses
3) A third one to deploy to the preprod environment
4) A last one to deploy to the prod environment
