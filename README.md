## Мини магазин для продажи книг.

Полезные команды:<br>

Над фронтендом особо не думал потому, что сказали, что главное это запросы к бд

Генерация сваггера: docker compose exec -ti books_sale php artisan l5-swagger:generate
Запуск очереди: docker compose exec -ti books_sale php artisan queue:work<br>
Запуск сокетного сервера: docker compose exec -ti books_sale php artisan reverb:start --port=9001 --host=0.0.0.0 --debug<br>