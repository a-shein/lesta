## Установка

* склонировать проект git clone git@github.com:ovod93/lesta.git

### Последовательно запустить команды в директории /lesta

* ```make compose-build``` сборка приложения
* ```make compose-up``` запускаем приложение
* ```make init``` устанавливаем зависимости и копируем .env.example в .env.
* ```make test``` для запуска тестов (опционально)

## Задание

Реализовать сервис с единственным API методом, возвращающим срез последовательности чисел
из ряда Фибоначчи и простым UI для проверок.

Пример запроса: API GET http://example.com/fibonacci?from=x&to=y


### Требования
* Сервис не должен вычислять повторно числа из ряда Фибоначчи. Значения необходимо
хранить в кеше (напр. Redis)
* Код должен быть выложен в репозиторий (напр. github.com)
* Необходимо продумать развертку/запуск сервиса на другом компьютере (напр. с
помощью docker-compose)
* Описание сервиса в README.md


## Функционал

для проверки задания:
* в директории /lesta зайти в контейнер php: ```make compose-bash```
* перейти на страницу в браузере: ```http://localhost/fibonacci```
* ввести значения (есть валидация).  
* при первом выполнении команды, по указанному диапазону создастся ряд чисел Фибоначчи
  * для проверки занесения ряда Фибоначчи в кэш в директории /lesta зайти в контейнер с Redis командой: ```docker-compose exec redis sh```
    * внутри контейнера воспользуемся командой ```redis-cli```
    * теперь проверим интересующий нас ключ Redis командой ```get fibonacci```
    * видим диапазаон чисел Фибоначчи, которые попали туда при первой итерации приложения
    * если при повторной проверке ряда Фибоначчи вы укажите меньший диапазон, чем в предыдущий раз - то существующее значение по ключу в кэше не изменится, а если укажете больший - то значение в кэше по ключу поменяется и в него запишется увеличенный ряд чисел Фибоначчи
