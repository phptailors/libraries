# PhpTailors Libraries

General-purpose libraries from PhpTailors.

## Online documentation

TODO: link to the online documentation

## Notes for users

### Runtime Requirements

PHP>=8.0 is required.

## Notes for developers

### Development requirements

- [docker](https://docker.com/)
- [composer](https://getcomposer.org/)

### Initial preparations

After you've just cloned

```shell
php scripts/bootstrap-dev
composer up
composer bin all up
php docker/initialize
```

### Running all tests

```shell
composer tests
```

### Running unit tests

```shell
composer phpunit
```

or (with docker-compose)

```shell
docker/php run --rm php vendor/bin/phpunit
```

### Running sphinx examples tests

```shell
composer doctests
```

or (with docker-compose)

```shell
docker/php run --rm php vendor-bin/behat/vendor/bin/behat -c docs/behat.yml
```

### Running interactive PHP shell

```shell
composer psysh
```

or (with docker-compose)

```shell
docker/php run --rm php vendor-bin/psysh/vendor/bin/psysh vendor/autoload.php
```

### Running CodeClimate

```shell
docker/codeclimate run --rm codeclimate analyze
```

### Generating API documentation

```shell
docker/docs run --rm sami build
```

The generated API docs go to ``docs/build/html/api/``.

### Generating API documentation continuously and serving via HTTP

```shell
docker/docs up sami
```

The generated API docs go to ``docs/build/html/api/`` and get exposed at

  - ``https://localhost:8001``.

### Generating sphinx documentation continuously and serving via HTTP

```shell
docker/docs up sphinx
```

The generated docs go to ``docs/build/html`` and get exposed at

  - ``http://localhost:8002``.
