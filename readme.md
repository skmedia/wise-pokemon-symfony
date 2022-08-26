## WisePokemon

Setup, see symfony server:
https://symfony.com/doc/current/setup/symfony_server.html

```
symfony proxy:domain:attach wise-pokemon
```

### Todo / remarks / extra things todo
* not all pokemon properties are mapped (currently only id, name, types, ...)
* advanced types search, currently: simple like query
* importId added to pokemon, id = string, importId = int from service
* add openapi docs to controllers -> auto generation of openapi yaml file

### Demo env

`https://wise-pokemon.herokuapp.com`

see `doc/WisePokemon.postman_collection.json` for postman collection

E.g.
https://wise-pokemon.herokuapp.com/api/v1/pokemons?sort=name-asc

### API routes

see folder `src/WisePokemon/Infrastructure/Controller/Api/`

![img.png](doc/img/controllers.png)

![img.png](doc/img/routes.png)


### Security

Protected route:

```
^/api/v1/teams/*
```

e.g.
```
curl --location --request GET 'https://wise-pokemon.test/api/v1/teams' \
--header 'Authorization: Bearer token'
```

see:
* config/security.yaml
* src/WisePokemon/Infrastructure/Security/ApiKeyAuthenticator.php

### Messages
Symfony messenger is used for all read & write operations.

* middleware can easily be added (validation, xss, audting, ...)
* async sync switching
* clear overiew of all application operations
* all operations can be developed/tested in a uniform way

![img.png](doc/img/message_listing.png)

### Commands
* load pokemons from json file 
  * ``symfony console pokemons:import:file``
* load single pokemon from api (id or name) 
  * ``symfony console pokemon:import:api pikachu``

![img.png](doc/img/commands.png)

### Code quality

#### Static analysis & auto fix

Packages used:
* php-cs-fixer
* psalm

See Makefile command:
```
make code_check
```

#### Run tests

Not all code is fully tested, see `tests` folder.

See Makefile command:
```
make run_tests_stop_on_error_failure
```
