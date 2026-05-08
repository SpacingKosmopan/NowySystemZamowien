# Wykorzystane technologie

## JavaScript (Frontend)

Frontend systemu został napisany w czystym JavaScript z wykorzystaniem biblioteki jQuery. Kod odpowiada za obsługę interfejsu użytkownika, komunikację z backendem oraz dynamiczne renderowanie danych bez przeładowywania strony.

### Fetch API

Podstawową metodą komunikacji z backendem jest `fetch()`.

Wykorzystywany jest do:

- pobierania danych z API,
- wysyłania formularzy,
- aktualizacji statusów,
- usuwania rekordów,
- pobierania konfiguracji,
- obsługi plików JSON.

Przykładowe zastosowania:

```js
fetch("api/fetch_orders.php");
```

```js
fetch("api/update_status.php", {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
  },
  body: JSON.stringify(data),
});
```

W projekcie stosowane są zarówno:

- żądania `GET` – do pobierania danych,
- żądania `POST` – do zapisu, edycji i usuwania danych.

Dane przesyłane są najczęściej w formacie JSON.

---

## jQuery

Biblioteka jQuery wykorzystywana jest do:

- obsługi zdarzeń,
- manipulacji DOM,
- renderowania tabel,
- dynamicznego tworzenia elementów,
- pobierania wartości formularzy,
- zarządzania modalami i overlayami.

Najczęściej używane funkcje:

```js
$("#element");
```

```js
.on("click", ...)
```

```js
.append(...)
```

```js
.val()
```

```js
.html()
```

```js
.empty()
```

Kod korzysta również z delegacji zdarzeń:

```js
$("#table").on("click", ".edit-btn", function () {});
```

Dzięki temu możliwa jest obsługa elementów generowanych dynamicznie.

---

## Dynamiczny rendering danych

Większość elementów tabel i list tworzona jest dynamicznie po stronie JavaScript.

Przykłady:

- tabela zamówień,
- lista klientów,
- lista tagów,
- lista zdjęć,
- załączniki,
- filtry.

Dane pobierane z backendu są renderowane do HTML przy użyciu template stringów:

```js
tbody.append(`
  <tr>
    <td>${client.id}</td>
  </tr>
`);
```

---

## Obsługa formularzy

Formularze obsługiwane są całkowicie po stronie JavaScript.

System realizuje:

- walidację pól,
- blokowanie formularza,
- resetowanie danych,
- tryb tylko do odczytu,
- dynamiczne ładowanie danych do formularza.

Walidacja wykonywana jest przed wysłaniem danych do backendu.

---

## JSON

JSON jest podstawowym formatem wymiany danych pomiędzy frontendem a backendem.

Wykorzystywany jest do:

- przesyłania danych formularzy,
- odpowiedzi API,
- przechowywania konfiguracji,
- zapisu tagów,
- załączników,
- zdjęć.

Przykłady:

```js
JSON.stringify(payload);
```

```js
const data = await res.json();
```

---

## Async / Await

Kod wykorzystuje programowanie asynchroniczne przy pomocy:

```js
async
await
```

Dzięki temu możliwe jest:

- oczekiwanie na odpowiedzi API,
- sekwencyjne ładowanie danych,
- uproszczenie obsługi Promise.

Przykład:

```js
const res = await fetch("api/fetch_orders.php");
const data = await res.json();
```

---

## PHP (Backend)

Backend systemu napisany został w PHP.

Każdy endpoint API odpowiada za konkretną operację, np.:

- pobieranie danych,
- dodawanie rekordów,
- aktualizację danych,
- usuwanie rekordów,
- autoryzację użytkownika,
- obsługę plików.

Backend zwraca odpowiedzi w formacie JSON.

---

## Połączenie z bazą danych MySQL

W projekcie wykorzystywana jest baza danych MySQL oraz rozszerzenie `mysqli`.

Centralny plik połączenia:

```php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
```

Połączenie konfigurowane jest w pliku:

```php
config.php
```

Kod ustawia również kodowanie UTF-8:

```php
$conn->set_charset("utf8mb4");
```

---

## Prepared Statements

Do komunikacji z bazą danych stosowane są prepared statements.

Przykład:

```php
$stmt = $conn->prepare("
    UPDATE zamowienia
    SET status = ?
    WHERE id = ?
");
```

oraz:

```php
$stmt->bind_param("si", $status, $order_id);
```

Prepared statements służą do:

- bezpiecznego przekazywania danych,
- wykonywania zapytań SQL,
- operacji INSERT,
- UPDATE,
- DELETE,
- SELECT.

---

## Obsługa sesji

System logowania opiera się na sesjach PHP.

Sesje uruchamiane są przy pomocy:

```php
session_start();
```

Autoryzacja użytkownika wykonywana jest przez sprawdzenie:

```php
$_SESSION['user_id']
```

Endpointy API sprawdzają, czy użytkownik jest zalogowany przed wykonaniem operacji.

---

## REST-like API

Backend działa w modelu prostego API.

Endpointy odpowiadają za konkretne akcje:

| Endpoint            | Funkcja              |
| ------------------- | -------------------- |
| `fetch_orders.php`  | pobieranie zamówień  |
| `add_order.php`     | dodawanie zamówienia |
| `update_order.php`  | edycja zamówienia    |
| `delete_order.php`  | usuwanie zamówienia  |
| `update_status.php` | zmiana statusu       |
| `auth.php`          | autoryzacja          |

Komunikacja odbywa się przez HTTP oraz JSON.

---

## Obsługa danych wejściowych

Dane przesyłane metodą POST pobierane są przez:

```php
file_get_contents("php://input")
```

a następnie dekodowane:

```php
json_decode(..., true)
```

Dzięki temu backend może odbierać dane JSON wysyłane z JavaScript.

---

## Operacje na plikach

System wykorzystuje również operacje na plikach przy pomocy PHP.

Funkcje:

```php
file_put_contents()
```

```php
file_get_contents()
```

służą do:

- zapisu konfiguracji,
- odczytu ustawień,
- tworzenia plików JSON,
- przechowywania danych konfiguracyjnych.

---

## MySQL

Baza danych MySQL przechowuje:

- użytkowników,
- klientów,
- zamówienia,
- typy zamówień,
- tagi,
- statusy,
- dane statystyczne.

Wykorzystywane są:

- relacje między tabelami,
- `JOIN`,
- `GROUP BY`,
- `COUNT`,
- `SUM`,
- sortowanie danych,
- filtrowanie rekordów.

Przykładowe zastosowania:

```sql
JOIN zamowienia AS o
```

```sql
GROUP BY k.id
```

```sql
SUM(kwota)
```

---

## HTML + CSS

Interfejs użytkownika oparty jest o:

- HTML5,
- własne style CSS,
- dynamiczne klasy,
- overlaye,
- modale,
- responsywny układ.

Dodatkowo wykorzystywane są:

### Bootstrap Icons

Ikony pobierane są z biblioteki:

```html
bootstrap-icons
```

---

## Marked.js

Do renderowania plików Markdown wykorzystywana jest biblioteka:

```js
marked.js;
```

Pozwala ona zamieniać pliki `.md` na HTML w przeglądarce.

---

## Architektura modułowa

Frontend podzielony został na osobne kontrolery:

| Plik                        | Odpowiedzialność   |
| --------------------------- | ------------------ |
| `orders.js`                 | tabela zamówień    |
| `orders_controller.js`      | formularz zamówień |
| `clients_controller.js`     | wybór klientów     |
| `tags_controller.js`        | obsługa tagów      |
| `photos.js`                 | zdjęcia            |
| `links_controller.js`       | załączniki         |
| `order_types_controller.js` | typy zamówień      |
