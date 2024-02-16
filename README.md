## Гостевая книга на Symfony 7
По книге **[Symfony: The Fast Track](https://symfony.com/doc/current/the-fast-track)**

### Используется

- PHP 8.3
- PostgreSQL (alpine)
- Nginx (alpine)
- Docker
- XDebug, APCu

### Настройка

- Установите docker and docker-compose
- Настроить файл .env
- Запустить `make init`

### Основные команды make

- `make init` - поднять проект
- `make test` - запустить тесты
- `make up` - поднять проект
- `make down` - остановить проект
- `make restart` - перезапустить проект
- `make clear` - очистить кеш


### Создание Администратора

1. Для генерации пароля запустите `make admin-password` и введите желаемый пароль
2. Утиллита Symfony Password Hash сгенерирует password hash вида `$2y$13$7JuJcu4Aywq9pY4aPmr3t.nRA/cSLQSxPoA3YZoIz0GcsMhZkIoqu`
3. Для добавляения администратора, используйте следующий SQL-запрос (заменив на свой сгенерированный password hash):
    - `docker-compose exec php symfony console dbal:run-sql "INSERT INTO admin (id, username, roles, password) \
      VALUES (nextval('admin_id_seq'), 'admin', '[\"ROLE_ADMIN\"]', \
      '\$2y\$13\$7JuJcu4Aywq9pY4aPmr3t.nRA/cSLQSxPoA3YZoIz0GcsMhZkIoqu')"`
    - Обратите внимание на экранирование знака $ в поле password; экранируйте их все!
