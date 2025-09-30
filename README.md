Po pobraniu projektu na dysk należy wykonać kilka kroków
1. utworzyć w folderze głównym projektu plik .env i skopiować zawartość z pliku .env.example do pliku .env
2. W konsoli wykonać polecenia
    1. Aby postawić kontenery i uruchomić
    - docker compose up -d --build
    2. Wygenerować App Key
    - docker exec -it petstore-app php artisan key:generate
3. Teraz nasza aplikacja jest dostępna pod adresem http://localhost/
