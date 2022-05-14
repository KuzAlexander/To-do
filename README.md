# to_do

### Установка пакетов
```
composer install
```

### Настройка БД
```
cp .env .env.local
```

### Создать БД
```
php bin/console doctrine:database:create
```

### Установка миграций
```
php bin/console doctrine:migrations:migrate
```

### Запуск проекта
```
symfony server:start
```
### To_do
```
http://localhost:8000/task
```