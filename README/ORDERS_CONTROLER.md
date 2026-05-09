> [Strona Główna](../README.md)

# `orders_controller.js`

## Opis pliku

Plik odpowiada za obsługę formularza tworzenia, edycji oraz usuwania zamówień.

Moduł zarządza:

- otwieraniem formularza,
- walidacją danych,
- zapisem nowych zamówień,
- aktualizacją istniejących zamówień,
- usuwaniem zamówień,
- resetowaniem formularza,
- przygotowaniem danych do wysłania do backendu.

Jest to jeden z głównych kontrolerów systemu zamówień.

---

## Funkcja `loadOrdersController()`

#### Opis

Funkcja inicjalizująca wszystkie event listenery związane z formularzem zamówienia.

Wywoływana podczas startu strony `zamowienia/index.html`.

---

## Otwieranie formularza nowego zamówienia

```js
$("#add-order").on("click", function ()
```

#### Działanie

Po kliknięciu przycisku:

1. wyświetlany jest modal formularza,
2. ładowane są typy zamówień,
3. ustawiana jest aktualna data utworzenia.

---

### Wyświetlenie modala

```js
$("#new-order-overlay").removeClass("hidden");
```

Usuwana jest klasa ukrywająca formularz.

---

### Ładowanie typów zamówień

```js
loadOrderTypes();
```

Pobierane są typy zamówień z backendu.

---

### Ustawienie daty utworzenia

```js
setCreationDateFromNow();
```

W formularzu ustawiana jest aktualna data i godzina.

---

## Anulowanie formularza

```js id
$("#btn-cancel").on("click", function ()
```

#### Działanie

Po kliknięciu:

1. formularz zostaje zresetowany,
2. modal zostaje ukryty,
3. resetowany jest tryb edycji,
4. ukrywane są komunikaty błędów,
5. czyszczone są dane pomocnicze.

---

### Reset formularza

```js
$("#new-order-form")[0].reset();
```

Przywraca domyślne wartości pól formularza.

---

### Wyłączenie trybu edycji

```js
editingOrderId = null;
```

Oznacza przejście z trybu edycji do trybu tworzenia nowego zamówienia.

---

## Usuwanie zamówienia

```js
$("#btn-delete").on("click", function ()
```

#### Działanie

1. użytkownik otrzymuje okno potwierdzenia,
2. wykonywany jest request do backendu,
3. zamówienie usuwane jest z lokalnej tablicy,
4. formularz zostaje wyczyszczony,
5. użytkownik wraca do listy zamówień.

---

### Potwierdzenie usunięcia

```js
confirm(...)
```

Chroni przed przypadkowym usunięciem danych.

---

### Endpoint usuwania

```js
api / delete_order.php;
```

Do backendu wysyłane jest ID usuwanego zamówienia.

---

### Aktualizacja danych lokalnych

```js
ordersData = ordersData.filter((o) => o.id != editingOrderId);
```

Usunięte zamówienie znika z lokalnej tablicy bez konieczności pełnego reloadu.

---

## Zapisywanie zamówienia

```js
$("#btn-save").on("click", function (e)
```

#### Opis

Najważniejsza część modułu.

Odpowiada za:

- walidację formularza,
- budowę payloadu,
- wybór endpointu,
- wysłanie danych do backendu.

---

## Walidacja formularza

### Walidowane pola

#### Klient

```js
$("#client-id").val();
```

---

#### Typ zamówienia

```js
$("#order-type").val();
```

---

#### Tytuł

```js
$("#order-title").val();
```

---

#### Termin realizacji

```js
$("#order-date").val();
```

---

### Obsługa błędów

Dla każdego błędnego pola:

```js
.removeClass("hidden")
```

pokazywany jest tooltip błędu.

---

## Tworzenie payloadu

### Obiekt danych

```js
const payload = {
  client_id,
  opis,
  kwota,
  termin,
  typ_id,
  tytul,
  tagi,
  zalaczniki,
  zdjecia,
};
```

---

### Dane przesyłane do backendu

#### Dane podstawowe

- klient,
- opis,
- cena,
- termin,
- typ zamówienia,
- tytuł.

---

#### Dane dodatkowe

- tagi (`selectedTags`),
- linki (`links`),
- zdjęcia (`selectedPhotoIds`).

---

## Tryb dodawania i edycji

### Wybór endpointu

```js
const url = editingOrderId ? "api/update_order.php" : "api/add_order.php";
```

---

### Dodawanie nowego zamówienia

Jeżeli:

```js
editingOrderId === null;
```

wykorzystywany jest endpoint:

```txt
api/add_order.php
```

---

### Edycja zamówienia

Jeżeli istnieje `editingOrderId`:

- payload otrzymuje dodatkowe pole `id`,
- wykorzystywany jest endpoint aktualizacji.

---

## Wysyłanie danych

```js
fetch(url, {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify(payload),
});
```

Dane wysyłane są jako JSON.

---

## Czyszczenie formularza po zapisie

Po poprawnym zapisie:

1. wywoływane jest `clearInputs()`,
2. użytkownik zostaje przekierowany na listę zamówień.

---

## Zmienna `editingOrderId`

```js
let editingOrderId = null;
```

#### Opis

Przechowuje ID aktualnie edytowanego zamówienia.

- `null` → nowe zamówienie,
- liczba → edycja istniejącego zamówienia.

---

## Funkcja `clearInputs()`

#### Opis

Resetuje cały formularz oraz dane pomocnicze.

---

## Reset pól formularza

Czyszczone są m.in.:

- klient,
- typ,
- opis,
- cena,
- data,
- tytuł.

---

## Reset danych pomocniczych

### Tagi

```js
selectedTags = [];
```

---

### Zdjęcia

```js
selectedPhotoIds = [];
```

---

### Linki

```js
links = [];
```

---

## Reset widoku

Czyszczone są kontenery HTML:

- tagów,
- linków,
- zdjęć.

---

## Funkcja `setCreationDate()`

#### Opis

Ustawia datę utworzenia zamówienia w formularzu.

---

### Formatowanie

Wykorzystuje funkcję:

```js
formatDatePL(date);
```

---

### Aktualizacja pola

```js
$("#creation-date").val(formatted);
```

Pole jest tylko do odczytu.

---

## Powiązania z innymi modułami

Plik współpracuje z:

- `clients_controller.js`
- `tags_controller.js`
- `links_controller.js`
- `photos.js`
- `order_types_controller.js`
- backend API zamówień

---

## Zastosowane technologie

- JavaScript
- jQuery
- Fetch API
- REST API
- dynamiczna manipulacja DOM
- modal forms

---

## Logika działania modułu

### Tworzenie zamówienia

1. Użytkownik otwiera formularz
2. Wypełnia dane
3. Wykonywana jest walidacja
4. Tworzony jest payload
5. Dane wysyłane są do backendu
6. Formularz zostaje wyczyszczony
7. Następuje powrót do listy zamówień

---

### Edycja zamówienia

1. Formularz ładowany jest danymi zamówienia
2. `editingOrderId` otrzymuje ID rekordu
3. Po zapisie wykonywany jest update zamiast add

---

### Usuwanie zamówienia

1. Użytkownik potwierdza operację
2. Backend usuwa rekord
3. Dane lokalne są aktualizowane
4. Formularz zostaje zamknięty
