# Dokumentacja techniczna – System Zarządzania Zamówieniami

## 1. Dashboard

![Dashboard](images/dashboard.png)

Podstrona pełni rolę głównego panelu administracyjnego aplikacji do zarządzania zamówieniami. Dashboard agreguje najważniejsze informacje systemowe oraz umożliwia szybki dostęp do kluczowych funkcji aplikacji.

Użytkownik po zalogowaniu może:

- sprawdzić status zamówień,
- wyświetlić najbliższe terminy realizacji,
- szybko dodać nowe zamówienie,
- przejść do innych modułów systemu,
- sprawdzić aktualną datę i godzinę,
- monitorować stan autoryzacji użytkownika.

---

### 2. Struktura HTML

#### 2.1 Sekcja `<head>`

W sekcji `head` znajdują się:

##### Import biblioteki ikon Bootstrap Icons

```html
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
/>
```

Biblioteka odpowiada za wyświetlanie ikon używanych w interfejsie użytkownika.

##### Import pliku CSS

```html
<link rel="stylesheet" href="style.css" />
```

Plik zawiera style całego dashboardu.

##### Import fontu Rubik

```html
<link
  href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
  rel="stylesheet"
/>
```

Font Rubik jest używany jako główna czcionka aplikacji.

---

#### 3. Nawigacja boczna (`aside`)

Sekcja:

```html
<aside id="dashboard-navbar"></aside>
```

odpowiada za menu boczne aplikacji.

##### Funkcje menu:

- przejście do strony głównej,
- przejście do modułu zamówień,
- przejście do kalendarza,
- przejście do klientów,
- przejście do typów zamówień i tagów,
- przejście do tekstów,
- przejście do galerii.

##### Mechanizm zwijania menu

Element:

```html
<i
  class="bi bi-chevron-double-left"
  id="dashboard-toggle-visibility"
  onclick="toggleDashboardNavbar()"
></i>
```

wywołuje funkcję `toggleDashboardNavbar()`, która odpowiada za ukrywanie lub pokazywanie panelu bocznego.

Funkcja została zaimplementowana w pliku `script.js`.

---

#### 4. Główna zawartość strony (`main`)

##### 4.1 Sekcja użytkownika

```html
<div id="user-section"></div>
```

Wyświetla:

- status użytkownika,
- avatar użytkownika.

Kliknięcie avatara:

```javascript
$("#user-avatar").on("click", function () {
  window.location.href = "logowanie/index.html";
});
```

powoduje przejście do modułu logowania.

---

#### 5. Mechanizm autoryzacji użytkownika

##### Funkcja `checkAuth()`

```javascript
async function checkAuth() {
```

Funkcja sprawdza stan autoryzacji użytkownika poprzez żądanie:

```javascript
fetch("logowanie/api/auth.php");
```

###### Możliwe odpowiedzi serwera:

| Kod HTTP | Znaczenie                |
| -------- | ------------------------ |
| 401      | użytkownik niezalogowany |
| 403      | konto zablokowane        |
| 200      | użytkownik zalogowany    |

##### Funkcja `init()`

Funkcja uruchamiana podczas startu aplikacji.

Odpowiada za:

- sprawdzenie autoryzacji,
- przekierowanie do logowania,
- blokadę dostępu dla nieaktywnych kont,
- inicjalizację danych dashboardu.

###### Fragment odpowiedzialny za przekierowanie:

```javascript
if (res.status === 401) {
  window.location.href = "logowanie/index.html";
  return;
}
```

---

#### 6. Zegar analogowy Canvas

Dashboard zawiera zegar analogowy renderowany przy pomocy elementu:

```html
<canvas id="clock" width="300" height="300"></canvas>
```

##### Funkcja `drawClock()`

Funkcja odpowiada za:

- rysowanie tarczy,
- generowanie cyfr 1–12,
- rysowanie wskazówek,
- aktualizację czasu.

###### Aktualizacja zegara

```javascript
setInterval(drawClock, 1000);
```

Zegar odświeżany jest co 1 sekundę.

##### Wykorzystane elementy Canvas API

- `ctx.arc()` – rysowanie okręgu,
- `ctx.fill()` – wypełnienie tarczy,
- `ctx.stroke()` – rysowanie obramowań,
- `ctx.rotate()` – obracanie elementów,
- `ctx.fillText()` – wyświetlanie numerów godzin.

---

#### 7. Statystyki zamówień

Dashboard wyświetla liczbę:

- dzisiejszych zamówień,
- zamówień w realizacji,
- zamówień zakończonych,
- zaległych zamówień.

##### Pobieranie danych

Dane pobierane są metodą `fetch()` z endpointów API:

```javascript
api / get_today_orders_count.php;
api / get_orders_count.php;
api / get_old_orders_count.php;
```

##### Obsługa błędów

Każde zapytanie posiada obsługę błędów:

```javascript
.catch((err) => {
  console.error(err);
});
```

---

#### 8. Funkcja odmiany języka polskiego

##### Funkcja `getPolishEnding()`

Funkcja odpowiada za poprawną odmianę słowa „zamówienie”.

###### Przykłady:

| Liczba | Wynik      |
| ------ | ---------- |
| 1      | zamówienie |
| 2      | zamówienia |
| 5      | zamówień   |

##### Implementacja

```javascript
function getPolishEnding(number, originalEnding)
```

Funkcja wykorzystuje instrukcję `switch` oraz analizę wartości liczbowych.

---

#### 9. Najbliższe terminy realizacji

Sekcja:

```html
<div id="closest-orders-container"></div>
```

wyświetla zamówienia o najbliższym terminie realizacji.

##### Endpoint API

```javascript
api / get_closest_orders.php;
```

##### Dynamiczne generowanie elementów

Każde zamówienie jest dodawane metodą:

```javascript
closestOrdersDiv.append();
```

##### Obsługa kliknięcia

```javascript
$("#closest-orders-container").on(
  "click",
  ".closest-order",
  function () {
```

Po kliknięciu użytkownik zostaje przekierowany do widoku szczegółowego zamówienia.

---

#### 10. Szybkie dodawanie zamówienia

Sekcja umożliwia utworzenie zamówienia bez przechodzenia do pełnego formularza.

##### Formularz

```html
<form id="fast-add-order-form"></form>
```

##### Pola formularza

| Pole           | Opis              |
| -------------- | ----------------- |
| klient         | wybór klienta     |
| typ zamówienia | wybór typu        |
| tytuł          | nazwa zamówienia  |
| opis           | opis zamówienia   |
| data           | termin realizacji |

---

#### 11. Ładowanie danych formularza

##### Typy zamówień

Dane pobierane z endpointu:

```javascript
fetch("typy_tagi/api/get_all_types.php");
```

##### Lista klientów

Dane pobierane z:

```javascript
fetch("klienci/api/list.php");
```

###### Sortowanie klientów

```javascript
data.sort((a, b) => a.nazwisko.localeCompare(b.nazwisko, "pl"));
```

Sortowanie odbywa się alfabetycznie według nazwiska.

---

#### 12. Walidacja formularza

Przed wysłaniem formularza wykonywana jest walidacja:

##### Sprawdzane warunki

- czy wybrano klienta,
- czy podano tytuł,
- czy wybrano typ zamówienia,
- poprawność identyfikatorów liczbowych.

##### Przykład walidacji

```javascript
if (selectedClient === "") {
  $("#fast-order-error").text("Podaj klienta");
  return;
}
```

---

#### 13. Dodawanie zamówienia

##### Endpoint API

```javascript
api / fast_add_order.php;
```

##### Metoda HTTP

```javascript
POST;
```

##### Format danych

```javascript
body: JSON.stringify({
  clientId,
  typeId,
  description,
  date,
  title,
});
```

##### Po poprawnym dodaniu

Użytkownik zostaje przekierowany do modułu zamówień:

```javascript
window.location.href = "zamowienia/index.html";
```

---

#### 14. Zegar cyfrowy

Funkcja:

```javascript
function loadTimer()
```

odpowiada za wyświetlanie:

- aktualnej daty,
- aktualnej godziny.

##### Format daty

```text
DD.MM.RRRR HH:MM
```

##### Automatyczna aktualizacja

```javascript
setTimeout(() => loadTimer(), 10000);
```

Odświeżanie następuje co 10 sekund.

---

# 15. Architektura komunikacji

Frontend komunikuje się z backendem poprzez endpointy API zwracające dane JSON.

Schemat działania:

```text
Frontend → fetch() → API PHP → Baza danych → JSON → Frontend
```
