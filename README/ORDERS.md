# `orders.js`

## Opis

Plik odpowiada za:

- pobieranie zamówień z backendu,
- renderowanie tabeli zamówień,
- filtrowanie danych,
- obsługę statusów,
- usuwanie zamówień,
- otwieranie zamówienia do podglądu lub edycji,
- obsługę deep-linków (`?id=...`),
- renderowanie tagów,
- generowanie filtra miesięcy.

Jest to główny plik logiki widoku listy zamówień.

---

## Globalne zmienne

### `ordersData`

```js
let ordersData = [];
```

Przechowuje wszystkie zamówienia pobrane z API.

Każdy element tablicy zawiera m.in.:

```js
{
  (id,
    imie,
    nazwisko,
    typ,
    typ_id,
    tytul,
    opis,
    status,
    kwota,
    termin_realizacji,
    data_utworzenia,
    klient_id,
    tagi,
    zalaczniki,
    zdjecia);
}
```

---

## Funkcja `loadOrders()`

### Opis

Główna funkcja inicjalizująca widok zamówień.

Uruchamia:

- render tabeli,
- filtrowanie,
- pobieranie danych,
- obsługę akcji użytkownika.

---

## Inicjalizacja

```js
$(document).ready(function () {
```

Cała logika startuje dopiero po załadowaniu DOM.

---

## Statusy zamówień

```js
const statuses = ["anulowane", "nowe", "w realizacji", "zrealizowane"];
```

Lista dostępnych statusów wykorzystywana podczas renderowania `<select>`.

---

## `loadOrderTypesFilter()`

Ładuje typy zamówień do filtra:

```js
loadOrderTypesFilter();
```

---

## `loadMonthFilter()`

Generuje listę miesięcy do filtrowania:

```js
loadMonthFilter();
```

---

## `normalizeStatus()`

### Opis

Konwertuje status CSS-friendly.

### Przykład

```js
"w realizacji"
↓
"w-realizacji"
```

### Kod

```js
function normalizeStatus(s) {
  return s.toLowerCase().replace(/\s+/g, "-");
}
```

---

## `renderTable(data)`

### Opis

Renderuje tabelę zamówień.

### Parametr

```js
data;
```

Tablica zamówień.

---

### Czyszczenie tabeli

```js
tbody.empty();
```

---

### Obsługa pustej listy

```js
if (!data.length)
```

Wyświetla:

```html
Brak zamówień
```

---

## Render pojedynczego wiersza

Każde zamówienie generuje:

```html
<tr data-id=""></tr>
```

Zawiera:

- ID,
- klienta,
- typ,
- tytuł,
- daty,
- status,
- akcje.

---

## Select statusu

```js
<select class="status-select">
```

Pozwala dynamicznie zmieniać status bez otwierania edycji.

---

## Klasa CSS statusu

```js
select.addClass(normalizeStatus(o.status));
```

Pozwala kolorować statusy przez CSS.

Przykład:

```css
.w-realizacji {
  background: blue;
}
```

---

## Akcje w tabeli

Renderowane są 3 przyciski:

```html
🔍 Edytuj Usuń
```

---

## `filterOrders()`

### Opis

Filtruje zamówienia według:

- statusu,
- typu,
- miesiąca,
- tytułu.

---

## Filtr statusu

```js
if (status !== "" && o.status !== status)
```

---

## Filtr tytułu

```js
o.tytul?.toLowerCase().includes(...)
```

Wyszukiwanie częściowe.

---

## Filtr typu

```js
if (type && type !== o.typ_id)
```

Filtrowanie po ID typu zamówienia.

---

## Filtr miesiąca

```js
const orderMonth = o.termin_realizacji?.slice(0, 7);
```

Przykład:

```txt
2025-07-13
↓
2025-07
```

---

## Event listenery filtrów

```js
$("#month-filter").on("change", filterOrders);
$("#title-sort").on("change", filterOrders);
$("#status-sort").on("change", filterOrders);
$("#order-title-filter").on("input", filterOrders);
```

Zmiana filtra automatycznie odświeża tabelę.

---

## Pobieranie zamówień

```js
fetch("api/fetch_orders.php");
```

Backend zwraca pełną listę zamówień.

Po pobraniu:

```js
ordersData = data;
renderTable(ordersData);
```

---

## `handleDeepLink()`

### Opis

Obsługuje parametry URL.

Przykład:

```txt
index.html?id=15
```

lub:

```txt
index.html?id=15&isViewOnly=true
```

---

## Tryb podglądu

```js
openOrder(order, isViewOnly);
```

Jeżeli istnieje:

```txt
isViewOnly=true
```

formularz zostanie zablokowany do edycji.

---

## Usuwanie zamówienia

### Event

```js
tbody.on("click", ".delete-btn");
```

---

## Potwierdzenie

```js
confirm(...)
```

---

## API

```js
api / delete_order.php;
```

Metoda:

```json
{
  "id": 15
}
```

---

## Aktualizacja statusu

### Event

```js
tbody.on("change", ".status-select");
```

---

## API

```js
api / update_status.php;
```

Payload:

```json
{
  "id": 15,
  "status": "zrealizowane"
}
```

---

## Aktualizacja lokalna

```js
order.status = status;
```

Następnie:

```js
renderTable(ordersData);
```

---

## Podgląd zamówienia

### Event

```js
.check-order
```

Uruchamia:

```js
openOrder(order, true);
```

Tryb tylko do odczytu.

---

## Edycja zamówienia

### Event

```js
.edit-btn
```

Uruchamia:

```js
openOrder(order, false);
```

---

## Reset filtrów

### Event

```js
#reset-sort
```

Resetuje:

- status,
- typ,
- miesiąc,
- tytuł.

Następnie renderuje pełną tabelę.

---

## `openOrder(order, readOnly)`

### Opis

Otwiera modal zamówienia.

Obsługuje:

- podgląd,
- edycję,
- render tagów,
- render zdjęć,
- render załączników.

---

## Tryb edycji

```js
editingOrderId = readOnly ? null : order.id;
```

---

## Wypełnianie formularza

Uzupełniane są:

- klient,
- opis,
- cena,
- tytuł,
- daty.

---

## Załączniki

```js
links = [...order.zalaczniki];
renderLinks(order.zalaczniki);
```

---

## Tagi

```js
await renderTags(order.tagi);
```

---

## Zdjęcia

```js
selectedPhotoIds = [...order.zdjecia];
renderSelectedPhotos();
```

---

## Dynamiczne przyciski

Lewa sekcja modala:

```js
#order-left-btns
```

Dodawane są:

- „Zobacz klienta”
- „Edytuj” (w trybie readonly)

---

## Przejście do klienta

```js
../klienci/zamowienia/index.html?id=...
```

---

## Otwieranie modala

```js
$("#new-order-overlay").removeClass("hidden");
```

---

## `renderTags(data)`

### Opis

Renderuje tagi przypisane do zamówienia.

---

## Pobieranie tagów

```js
fetch("api/fetch_tags.php");
```

---

## Render tagów

Dla każdego pasującego ID:

```html
<span class="tag">Nazwa</span>
```

---

## `setCreationDateFromNow()`

### Opis

Ustawia aktualną datę utworzenia zamówienia.

---

## `monthNames`

Tablica polskich nazw miesięcy.

---

## `loadMonthFilter()`

### Opis

Generuje zakres miesięcy:

```txt
2010 → 2040
```

---

## Generowany format

```txt
2025-07
```

Widoczny tekst:

```txt
Lipiec 2025
```
