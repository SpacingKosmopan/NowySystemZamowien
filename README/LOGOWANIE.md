# Dokumentacja modułu logowania

## Opis modułu

Moduł logowania odpowiada za:

- rejestrację użytkowników,
- logowanie do systemu,
- utrzymywanie sesji użytkownika,
- sprawdzanie autoryzacji,
- wylogowywanie użytkownika.

System został oparty o backend PHP oraz sesje serwerowe (`$_SESSION`), dzięki czemu dostęp do pozostałych modułów aplikacji możliwy jest wyłącznie po poprawnym uwierzytelnieniu.

---

## Struktura podstrony

Interfejs składa się z:

- panelu nawigacyjnego dashboardu,
- sekcji informacji o stanie logowania,
- formularza rejestracji,
- formularza logowania,
- przycisku wylogowania.

Widok formularzy zmienia się dynamicznie w zależności od stanu autoryzacji użytkownika.

---

## Mechanizm autoryzacji

Po wejściu na stronę automatycznie wykonywana jest funkcja:

```js
checkAuth();
```

Funkcja wysyła żądanie do endpointu:

```text
api/auth.php
```

Backend sprawdza:

- czy istnieje aktywna sesja,
- czy użytkownik jest zalogowany,
- czy konto posiada odpowiedni status.

---

## Rejestracja użytkownika

Proces rejestracji realizowany jest przez funkcję:

```js
register();
```

Dane użytkownika pobierane są z pól formularza:

- nazwa użytkownika,
- hasło.

Następnie przesyłane są metodą `POST` do:

```text
api/register.php
```

Do komunikacji wykorzystano:

```js
fetch();
```

oraz format:

```text
application/x-www-form-urlencoded
```

---

## Logowanie użytkownika

Proces logowania działa analogicznie do rejestracji.

Funkcja:

```js
login();
```

wysyła dane użytkownika do:

```text
api/login.php
```

Po poprawnym logowaniu backend tworzy sesję użytkownika.

Frontend sprawdza odpowiedź serwera i aktualizuje interfejs aplikacji.

---

## Sesje użytkowników

Autoryzacja oparta została o mechanizm sesji PHP.

Po poprawnym logowaniu backend zapisuje identyfikator użytkownika w:

```php
$_SESSION['user_id']
```

Pozostałe moduły aplikacji sprawdzają istnienie tej wartości przed wykonaniem operacji.

---

## Sprawdzanie stanu logowania

Funkcja:

```js
checkAuth();
```

umożliwia:

- weryfikację aktywnej sesji,
- pobranie nazwy zalogowanego użytkownika,
- zmianę widoku interfejsu.

Jeżeli użytkownik nie jest zalogowany:

- backend zwraca kod HTTP `401`,
- wyświetlany jest formularz logowania.

Jeżeli użytkownik posiada aktywną sesję:

- wyświetlana jest informacja o zalogowaniu,
- dostępny staje się przycisk wylogowania.

---

## Dynamiczne renderowanie interfejsu

Widok formularzy generowany jest dynamicznie przez JavaScript.

Funkcja:

```js
toggleLoginPage(show);
```

odpowiada za:

- wyświetlanie formularzy logowania i rejestracji,
- ukrywanie formularzy po zalogowaniu,
- renderowanie przycisku wylogowania.

Do modyfikacji DOM wykorzystano:

- `innerHTML`,
- JavaScript,
- jQuery.

---

## Wylogowywanie użytkownika

Wylogowanie realizowane jest przez funkcję:

```js
logout();
```

Funkcja wysyła żądanie do:

```text
api/logout.php
```

Backend usuwa sesję użytkownika i kończy autoryzację.

Po wylogowaniu interfejs wraca do formularza logowania.

---

## Komunikacja frontend ↔ backend

Frontend komunikuje się z backendem PHP poprzez endpointy API:

| Endpoint       | Funkcja                 |
| -------------- | ----------------------- |
| `register.php` | rejestracja użytkownika |
| `login.php`    | logowanie               |
| `auth.php`     | sprawdzenie sesji       |
| `logout.php`   | wylogowanie             |

Komunikacja realizowana jest asynchronicznie przy pomocy `fetch API`.

---

## Obsługa kodów HTTP

W module wykorzystano kody odpowiedzi HTTP:

| Kod   | Znaczenie             |
| ----- | --------------------- |
| `200` | poprawna operacja     |
| `401` | brak autoryzacji      |
| `403` | błędne dane logowania |

Dzięki temu frontend może odpowiednio reagować na stan użytkownika.

---

## Funkcjonalności modułu

Moduł umożliwia:

- tworzenie kont użytkowników,
- logowanie,
- utrzymywanie sesji,
- sprawdzanie autoryzacji,
- wylogowywanie,
- dynamiczne przełączanie widoków interfejsu,
- zabezpieczenie pozostałych modułów systemu.
