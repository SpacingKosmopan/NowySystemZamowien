## v1.6

> Data

### Zmiany i nowości

- teraz ustawienia są zapisywane do pliku
- zaktualizowano stopkę w Dashboardzie

### Naprawione błędy

- po pierwszym uruchomieniu strony niektóre kolory z ustawień domyślnie były czarne
- przy podglądaniu zamówienia z linku (w linku znajdowało się id zamówienia, np. zamowienia/index.html?id=n), po zapisie zamówienie otwierało się ponownie

## v1.5

> 04.05.2026

### Zmiany

- zaktualizowano zamówienia danego klienta i dodano przycisk Zobacz zamówienia klienta przy wyświetlaniu zamówienia
- kalendarz wyświetla teraz nie tylko klienta, ale i tytuł
- dodano możliwość edytowania zamówienia podczas wyświetlania (readonly)
- zaktualizowano wygląd formularza zamówienia oraz kilku innych miejsc, m.in. podglądu zamówień danego klienta
- dodano prymitywne ustawienia, dostęp do nich znajduje się w dolnej części Dashboardu
- dodano możliwość zmiany koloru wydarzeń w kalendarzu i statusu zamówień - funkcja ta będzie intensywnie rozwijana w przyszłości. Będzie obejmowała nie tylko zmianę kolorów

### Naprawione błędy

- typ zamówienia w zamówieniach klienta był niepoprawnie wyświetlany
- przycisk usunięcia zamówienia z widoku zamówienia nie działał

---

## v1.4

> 02.05.2026

### Zmiany

- dodano wyświetlanie zamówień złożonych przez konkretnego klienta (należy wejść w klientów -> zamówienia)
- teraz klienci są sortowani alfabetycznie po nazwisku w zakładce "Klienci"
- dodano sortowanie zamówień po Tytule

### Naprawione błędy

- naprawiono błąd związany z prawidłowym kodowaniem polskich znaków (znowu)
- informacja o nieuzupełneniu tytułu nie pojawiała się (tytuł zamówienia jest obowiązkowy)
- gdy wyświetlała się informacja o błędzie, po anulowaniu dodawania zamówienia nie znikała ona
- sortowanie po kliencie nie działało (właściwie to całe sortowanie nie działało, więc naprawiono wszystko + usunięto sortowanie po kliencie w głównej zakładce z zamówieniami)

---

## v1.3

> 30.04.2026

### Nowości

- dodano tytuł do zamówień - teraz nie tylko "Zaproszenie", ale i "dla Basi na 50tkę"
  (aby system działał prawidłowo, należy uaktualnić bazę danych poniższym zapytaniem)

  ```sql
  ALTER TABLE zamowienia ADD COLUMN tytul VARCHAR(255) NOT NULL DEFAULT 'brak tytułu';
  ```

### Zmiany

- zmniejszono zegar w Dashboard i zmieniono kolor jego tarczy na ciemnoszary
- obok zegara dodano dzisiejszą datę i godzinę

### Naprawione błędy

- naprawiono błędy związane z nieprawidłowym kodowaniem polskich znaków (szczególnie przy dodawaniu nowego klienta)
- powiadomienia o błędach w formularzu nie pokazywały się, przez co po niepoprawnym uzupełnieniu formularza resetował się on, a zamówienie nie dodawało
- w niektórych przeglądarkach formularz dodawania nowego zamówienia nie był resetowany po dodaniu
- usuwanie typów zamówień i tagów nie działało

---

## v1.2

> 29.04.2026

Dokonano nieznacznych poprawek i zmian

### Zmiany

- teraz raport zarobków z miesiąca liczy tylko te zamówienia, które są zrealizowane

### Naprawione błędy

- system sprawdzania wersji nie działał poprawnie
- szybkie dodawanie zamówienia nie przenosiło i wyświetlało błąd (mimo że błędu nie było i zamówienie było dodawane)

### Eksperymenty

Zauważono, że występuje duży problem z prawidłowym kodowaniem polskich znaków. W tej wersji deweloperzy wypuścili specjalne narzędzie do zweryfikowania przyczyny problemu. Występuje on nadal, więc prosimy o nie korzystanie z aplikacji do naprawienia tego błędu

---

## v1.1

> 29.04.2026

Naprawiono kilka krytycznych błędów

### Naprawione błędy

- przycisk dodawania klienta nie działał
- dodawanie typów zamówień i tagów nie działało
- usunięto zbędne informacje wypisywane do konsoli przeglądarki

---

## v1.0 - Oficjalne uruchomienie

> 29.04.2026

w dniu 29.04.2026 aplikacja Systemu Zamówień zostaje oficjalnie uruchomiona do użytku! Pierwszym klientem jest p. Alicja Starmach z _Na Polanie_.

### Co nowego?

- System Zamówień

  Dodawaj, usuwaj i przeglądaj! Teraz łatwo możesz zarządzać wszystkimi swoimi zamówieniami

- **Co oferuje aplikacja?**

  Kalendarz, dodawanie klientów, galeria ze zdjęciami, system folderowy z tekstami i wiele więcej!

### Aplikacja może zawierać błędy

Wszelkie błędy oraz nieprawidłowości, które ujawnią się podczas użytkowania aplikacji należy niezwłocznie zgłosić do dewelopera. To samo tyczy się propozycji ulepszeń oraz zmian.
