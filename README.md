# Hotel

Учебно-демонстрационный сайт отеля с административной панелью и логикой бронирования. Запуск и инфраструктура — через Docker Compose (PHP 8.2 + Apache, Nginx как reverse proxy, MySQL 8).

## Технологии

- PHP 8.2 (Apache внутри контейнера).
- Nginx (reverse proxy, самоподписанный TLS).
- MySQL 8.
- Docker + Docker Compose.
- Composer, PDO.
- PHPStan, PHP-CS-Fixer.
- HTML/CSS/JS (готовая тема, статические ассеты в `public/`).

## Запуск

1) Создайте файл окружения:

```
cp .env.example .env
```

2) Установите зависимости PHP (по желанию, для разработки):

```
docker compose run --rm web composer install
```

3) Соберите и запустите сервисы:

```
docker compose up -d --build
```

4) Откройте сайт и админку:

- http://localhost/
- https://localhost/ (самоподписанный сертификат)
- https://hotel.localhost/ (самоподписанный сертификат)
- Админка: http://localhost/admin/login.php

## Конфигурация окружения

Файл `.env`:

```
DB_HOST=db
DB_NAME=hotel
DB_USER=hotel
DB_PASS=hotel
DB_PORT=3306
DB_ROOT_PASS=root
ADMIN_USER=hotel_admin
ADMIN_PASS=HotelDemo2024
```

- `DB_*` — параметры подключения к MySQL.
- `ADMIN_USER`/`ADMIN_PASS` — учетные данные первого администратора, которые создаются при первом входе, если таблица `users` пустая.

## Вход в админку

Адрес: `http://localhost/admin/login.php`

Логин/пароль по умолчанию берутся из `.env` (`ADMIN_USER` / `ADMIN_PASS`). Если нужно изменить учетные данные:

- обновите запись в таблице `users`, либо
- удалите всех пользователей из `users` и зайдите снова (будет создан новый админ из `.env`).

## Функционал

- Публичные страницы: главная, список номеров, карточка номера, контакты.
- Админка: категории, типы номеров, клиенты, сотрудники, бронирования, пользователи.
- Бронирования:
  - статусы `reserved` / `confirmed` / `cancelled`;
  - проверка пересечений по датам;
  - учет доступности по количеству (`quantity`) у типа номера;
  - сумма бронирования считается автоматически, если `total = 0`.

## Как редактировать проект

- Тексты и верстка публичных страниц: `public/index.php`, `public/rooms.php`, `public/rooms-single.php`, `public/contact.php`.
- Общие блоки сайта: `templates/site/header.php`, `templates/site/footer.php`.
- Админка и ее страницы: `public/admin/*.php`.
- Общие блоки админки: `templates/admin/header.php`, `templates/admin/footer.php`.
- PHP-логика: `src/`, конфигурация и bootstrap: `config/`.
- Статические ассеты: `public/css`, `public/js`, `public/images`, `public/fonts`.
- Исходники SCSS: `resources/scss/` (сборщик не подключен, правьте CSS напрямую или подключите сборку отдельно).
- Схема и начальные данные БД: `database/init.sql`.

## Структура проекта

- `public/` — публичный веб‑корень.
- `public/admin/` — админка.
- `src/` — PHP‑классы и логика.
- `config/` — bootstrap и окружение.
- `database/` — схема и сиды.
- `docker/` — настройки контейнеров и Nginx.
- `templates/` — общие шаблоны.
- `resources/` — исходники стилей (SCSS).
- `storage/` — служебные файлы (логи и т.п.).
- `composer.json`, `phpstan.neon`, `.php-cs-fixer.php` — инструменты качества кода.

## Где хранятся данные пользователей

- Данные лежат в MySQL внутри Docker volume `db-data`.
- Администраторы хранятся в таблице `users` с `password_hash` (используется `password_hash()`).
- Данные клиентов и сотрудников — таблицы `clients` и `associates`.
- Сессии — стандартные PHP-сессии (по умолчанию файловое хранение в контейнере).

## Миграции для существующей БД

Если база уже была создана раньше, добавьте новые поля/индексы/ограничения:

```
docker compose exec -T db mysql -uroot -proot hotel -e "ALTER TABLE rooms ADD COLUMN quantity INT UNSIGNED NOT NULL DEFAULT 1 AFTER price;"
docker compose exec -T db mysql -uroot -proot hotel -e "ALTER TABLE bookings ADD COLUMN status ENUM('reserved','confirmed','cancelled') NOT NULL DEFAULT 'reserved' AFTER date_out;"
docker compose exec -T db mysql -uroot -proot hotel -e "CREATE INDEX bookings_room_status_dates_idx ON bookings (room_id, status, date_in, date_out);"
docker compose exec -T db mysql -uroot -proot hotel -e "ALTER TABLE rooms ADD CONSTRAINT rooms_quantity_chk CHECK (quantity >= 0);"
```

Если в БД уже есть проверка дат `date_out >= date_in`, замените на строгую:

```
docker compose exec -T db mysql -uroot -proot hotel -e "ALTER TABLE bookings DROP CHECK bookings_dates_chk;"
docker compose exec -T db mysql -uroot -proot hotel -e "ALTER TABLE bookings ADD CONSTRAINT bookings_dates_chk CHECK (date_out > date_in);"
```

## Сброс базы данных

Полный сброс (удалит данные):

```
docker compose down -v
docker compose up -d --build
```

## Качество кода

```
docker compose run --rm web composer stan
docker compose run --rm web composer cs
docker compose run --rm web composer cs:fix
```

## Примечания

- База инициализируется из `database/init.sql` при первом запуске (пустой volume).
- HTTPS использует самоподписанный сертификат — браузер покажет предупреждение.
