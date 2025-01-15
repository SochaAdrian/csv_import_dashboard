# System przetwarzania danych użytkowników

## Rejestracja i import użytkowników:
- [x] Umożliwia zaimportowanie listy użytkowników z pliku CSV za pomocą formularza. Każdy wiersz pliku CSV zawiera dane użytkownika: imię, nazwisko, email.
- [x] Weryfikuje dane podczas importu (np. poprawność formatu emaila).
- [x] Dodaje poprawne rekordy do bazy danych.

## Przetwarzanie w tle:
- [x] Proces importowania użytkowników ma działać asynchronicznie, przy użyciu kolejek Laravel (np. Redis jako driver).
- [x] Każdy rekord powinien być przetwarzany jako osobne zadanie w kolejce.

## Powiadomienia:
- [x] Po zakończeniu przetwarzania użytkownika wysyłaj powiadomienie email do administratora z podsumowaniem wyniku (np. które rekordy zostały poprawnie dodane, a które zawierały błędy).
- [x] Obsłuż sytuację, gdy email nie zostanie wysłany (np. zapisz informację o błędzie do logów).

## Panel administracyjny:
- [x] Udostępnij widok w panelu administracyjnym, w którym administrator widzi historię wszystkich importów wraz ze statusem (np. zakończone, w trakcie, błędy).

## Rozszerzenie (opcjonalne):
- [x] Dodaj możliwość eksportowania listy użytkowników do pliku CSV.
- [x] Obsłuż ograniczenie ilości wierszy w pliku eksportu (np. maks. 1000 użytkowników na plik).

## Uruchomienie projektu (DOCKER):

1. Sklonuj repozytorium oraz uzupełnij .env.example ( serwer smtp np. mailtrap)
2. Zbuduj obraz dockera:
   ```bash
   docker compose build
   ```
3. Uruchom kontener dockera:
   ```bash
    docker compose up
    ```

4. Aplikacja będzie dostępna pod adresem `http://localhost`. Ze względu na to że aplikacja sama się podnosi i wykonują się migracje, a mysql zamyka tymczasowy serwer po inicjalizacji do skryptu startowego dodany został sleep który sprawdza czy mysql działa konkretna ilosc czasu i wtedy odpala kolejki serwer i migracje, stad proces startu moze trwać do około minuty (tutaj to tylko kwestia wygody odpalenia aplikacji)
