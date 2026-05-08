# `order_types_controller.js`

## Opis pliku

Plik odpowiada za obsługę typów zamówień w systemie.

Moduł realizuje:

- pobieranie typów zamówień z backendu,
- generowanie listy typów w formularzu zamówienia,
- generowanie filtrów typów w widoku tabeli zamówień,
- cache danych po stronie klienta.

Typy zamówień są wykorzystywane podczas:

- tworzenia zamówienia,
- edycji zamówienia,
- filtrowania listy zamówień.

---

## Zmienne globalne

```js
let orderTypes = [];
```

#### Opis

Tablica przechowująca wszystkie pobrane typy zamówień.

Przykładowy obiekt:

```js
{
  id: 1,
  tytul: "Projekt graficzny"
}
```

---

## Funkcja `loadOrderTypesController()`

```js
function loadOrderTypesController() {}
```

#### Opis

Funkcja inicjalizacyjna kontrolera.

Obecnie nie zawiera logiki.

Może zostać wykorzystana w przyszłości do:

- inicjalizacji event listenerów,
- preloadingu danych,
- konfiguracji modułu.

---

## Sekcja formularza zamówienia

### Funkcja `loadOrderTypes(selectedId = null)`

#### Opis

Funkcja pobiera typy zamówień z backendu i generuje listę `<select>` w formularzu zamówienia.

---

### Parametry

#### `selectedId`

```js
selectedId = null;
```

ID typu zamówienia, który ma zostać automatycznie zaznaczony.

Wykorzystywane głównie podczas edycji istniejącego zamówienia.

---

### Mechanizm cache

```js
if (orderTypes.length > 0)
```

Jeżeli dane zostały już wcześniej pobrane:

- nie wykonywany jest kolejny request HTTP,
- wykorzystywana jest lokalna tablica `orderTypes`.

Zmniejsza to liczbę zapytań do backendu.

---

### Pobieranie danych

```js
fetch("api/fetch_order_types.php");
```

Endpoint zwraca listę wszystkich typów zamówień w formacie JSON.

---

### Przetwarzanie danych

Po pobraniu:

1. dane zapisywane są do `orderTypes`,
2. wywoływana jest funkcja renderująca formularz.

---

## Funkcja `renderOrderTypesForm(selectedId = null)`

#### Opis

Funkcja generuje elementy `<option>` w formularzu zamówienia.

---

### Generowanie domyślnej opcji

```js
<select>
  <option value="">-- wybierz --</option>
</select>
```

Pusta opcja wymusza świadomy wybór typu zamówienia przez użytkownika.

---

### Generowanie listy typów

Dla każdego typu tworzony jest:

```html
<option value="ID">NAZWA</option>
```

---

### Automatyczne zaznaczenie wartości

```js
const selected = selectedId == t.id ? "selected" : "";
```

Jeżeli `selectedId` odpowiada aktualnemu elementowi:

- opcja zostaje automatycznie zaznaczona.

Mechanizm używany przy edycji zamówień.

---

## Sekcja filtrowania tabeli

### Funkcja `loadOrderTypesFilter()`

#### Opis

Pobiera typy zamówień z backendu i generuje filtr typów w tabeli zamówień.

---

### Pobieranie danych

```js
fetch("api/fetch_order_types.php");
```

Wykorzystywany jest ten sam endpoint co w formularzu zamówienia.

---

### Renderowanie

Po pobraniu danych wykonywana jest funkcja:

```js
renderOrderTypesFilter(data);
```

---

## Funkcja `renderOrderTypesFilter(data)`

#### Opis

Generuje listę typów zamówień w filtrze tabeli.

---

### Element docelowy

```js
const select = $("#title-sort");
```

Filtr odpowiada za wybór typu zamówienia.

---

### Opcja domyślna

```html
<option value="">-- wszystkie --</option>
```

Pozwala wyświetlić wszystkie typy zamówień bez filtrowania.

---

### Generowanie opcji

Dla każdego typu zamówienia tworzony jest:

```html
<option value="ID">NAZWA</option>
```

---

## Powiązania z innymi modułami

Plik współpracuje z:

- `orders_controller.js`
- formularzem tworzenia zamówienia,
- formularzem edycji zamówienia,
- systemem filtrowania tabeli.

---

## Zastosowane technologie

- JavaScript
- jQuery
- Fetch API
- dynamiczna manipulacja DOM
- REST API

---

## Logika działania modułu

### Formularz zamówienia

1. Wywołanie `loadOrderTypes()`
2. Pobranie danych z backendu
3. Zapis danych w `orderTypes`
4. Wygenerowanie listy `<option>`
5. Wyświetlenie danych użytkownikowi

---

### Filtrowanie tabeli

1. Wywołanie `loadOrderTypesFilter()`
2. Pobranie listy typów
3. Wygenerowanie filtra
4. Użytkownik wybiera typ
5. Tabela zamówień zostaje przefiltrowana
