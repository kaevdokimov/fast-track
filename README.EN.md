[![Symfony](https://github.com/kaevdokimov/fast-track/actions/workflows/symfony.yml/badge.svg?branch=main)](https://github.com/kaevdokimov/fast-track/actions/workflows/symfony.yml)
[![NodeJS with Webpack](https://github.com/kaevdokimov/fast-track/actions/workflows/webpack.yml/badge.svg?branch=main)](https://github.com/kaevdokimov/fast-track/actions/workflows/webpack.yml)

## Fast Track Symfony 7

Based on the book **[Symfony: The Fast Track](https://symfony.com/doc/current/the-fast-track)**

[Russian](https://github.com/kaevdokimov/fast-track/blob/main/README.md)

### Used

- PHP 8.3
- PostgreSQL (alpine)
- Nginx (alpine)
- Docker
- XDebug, APCu

### Settings

- Install docker and docker-compose
- Set up the .env file
- Set up an Akismet key (how to get a key is described in **_Connecting Akismet antispam_**)
- Run `make init`

### Basic make commands

- `make init` - init the project
- `make test` - run tests
- `make up` - raise a project
- `make down` - stop the project
- `make restart` - restart the project
- `make clear` - clear cache

### Creating an Administrator

1. To generate a password, run `make admin-password`and enter the desired password
2. The Symfony Password Hash utility will generate a password hash like
   this `$2y$13$7JuJcu4Aywq9pY4aPmr3t.nRA/cSLQSxPoA3YZoIz0GcsMhZkIoqu`
3. To add an administrator, use the following SQL query (replacing it with your generated password hash):
    - `docker-compose exec php symfony console dbal:run-sql "INSERT INTO admin (id, username, roles, password) \
      VALUES (nextval('admin_id_seq'), 'admin', '[\"ROLE_ADMIN\"]', \
      '\$2y\$13\$7JuJcu4Aywq9pY4aPmr3t.nRA/cSLQSxPoA3YZoIz0GcsMhZkIoqu')"`
    - Note the escaping of the `$` sign in the password field; screen them all!

### Connecting Akismet antispam

1. Register a free account on [akismet.com](https://akismet.com/) and get an Akismet API key
2. Save the Akismet API key in the Symfony confidential data store by running the
   command `docker-compose exec php symfony console secrets:set AKISMET_KEY`, where AKISMET_KEY is the name of the key,
   the command will ask for the value of the key

### Webmailer

1. To test sending and receiving mail, the mailer service is used, launched via docker-compose
2. Default address http://localhost:8025
