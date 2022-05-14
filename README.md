# run

```git clone git@github.com:tim31al/test-jsonrpc.git my_dir```

```cd my_dir```

Запустить контейнеры:

```docker-compose up```

дождаться пока загрузятся и в новом окне инициализировать проект (если заняты порты 8081, 8082, поменять в .env LANDING_PORT, ACTIVITY_PORT) 

```bash init.sh```

http://localhost:8081

после работы, остановить и удалить контейнеры

```docker-comspose down -v```
