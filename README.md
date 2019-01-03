# ProstoTV API PHP client

[Документация по API](https://docs.api.prosto.tv)

## Создание нового проекта
Создадим новый проект. Для удобства управления зависимостями будем использовать `composer`.
```sh
mkdir prostotv
cd prostotv
composer init
```

## Добавление библиотеки в проект
Добавим пакет `utelecom/api-prostotv-php`
```sh
composer require utelecom/api-prostotv-php
```

## Инициализация объекта API
```php
require 'vendor/autoload.php';
$api = new UTG\ProstoTV('login', 'password');
```

## Выполнение запросов
Добавление пользователя и точки подключения
```php
$object = $api->post('/objects', ['first_name' => 'Иван', 'last_name' => 'Иванов']);
```
Пополнение счета
```php
$api->post('/objects/'.$object['id'].'/operations', ['operation_id' => 42, 'sum' => 100]);
```
Активация услуги
```php
$api->post('/objects/'.$object['id'].'/services', ['id' => 64]);
```
Добавление устройства
```php
$api->post('/objects/'.$object['id'].'/devices');
```
Добавление плейлиста
```php
$api->post('/objects/'.$object['id'].'/playlists');
```
Получение данных пользователя и точки подключения
```php
$object = $api->get('/objects/'.$object['id']);
```
Удаление плейлиста
```php
$api->delete('/objects/'.$object['id'].'/playlists'.$object['playlists'][0]['id']);
```
Удаление устройства
```php
$api->delete('/objects/'.$object['id'].'/devices'.$object['devices'][0]['id']);
```

## Обработка ошибок
Методы `get`, `post`, `put` и `delete` в случае ошибки возвращают значение `false`. Данные о последнем запросе можно получить из свойств `status` и `error`.
```php
if ( $object = $api->get('/objects/'.$id) ) {
    // ...
} else {
    echo "Ошибка!\nСтатус: " . $api->status . "\nОтвет: " . json_encode($api->error) . "\n";
}
```
