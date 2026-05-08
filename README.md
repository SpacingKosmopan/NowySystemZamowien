<!--
Dashboard - linia 8
Zamówienia - linia 752
-->

# Dokumentacja techniczna – System Zarządzania Zamówieniami

- [Dashboard](README/DASHBOARD.md)

## `zamowienia/index.html`

### 1. Opis pliku

Plik `zamowienia/index.html` odpowiada za główny moduł zarządzania zamówieniami w systemie.

Moduł umożliwia:

- przeglądanie wszystkich zamówień,
- filtrowanie danych,
- dodawanie nowych zamówień,
- edycję istniejących zamówień,
- podgląd szczegółów,
- przypisywanie klientów,
- przypisywanie tagów,
- dodawanie załączników,
- przypisywanie zdjęć,
- zarządzanie statusem zamówień.

---

### 2. Struktura strony

Strona składa się z następujących sekcji:

| Sekcja               | Opis                              |
| -------------------- | --------------------------------- |
| `aside`              | panel nawigacyjny                 |
| `main`               | główna zawartość modułu           |
| `header`             | nagłówek oraz system filtrów      |
| `section#all-orders` | tabela zamówień                   |
| `overlay`            | modal dodawania/edycji zamówienia |
| `client-modal`       | modal wyboru klienta              |
| `photos-showbox`     | modal wyboru zdjęć                |

---

### 4. Tabela zamówień

#### Sekcja

```html
<section id="all-orders"></section>
```

odpowiada za prezentację wszystkich zamówień.

#### Struktura tabeli

Tabela zawiera kolumny:

| Kolumna         | Opis                              |
| --------------- | --------------------------------- |
| Numer           | identyfikator zamówienia          |
| Klient          | dane klienta                      |
| Typ zamówienia  | typ przypisany do zamówienia      |
| Tytuł           | nazwa zamówienia                  |
| Data utworzenia | data utworzenia wpisu             |
| Termin          | termin realizacji                 |
| Status          | aktualny status                   |
| Akcje           | operacje dostępne dla użytkownika |

---

### 5. System filtrowania

#### Filtry

System umożliwia filtrowanie po:

| Filtr             | Opis                    |
| ----------------- | ----------------------- |
| Status            | stan realizacji         |
| Typ               | typ zamówienia          |
| Termin realizacji | filtrowanie po miesiącu |
| Tytuł             | wyszukiwanie tekstowe   |

#### Reset filtrów

Przycisk:

```html
<button id="reset-sort"></button>
```

przywraca domyślne ustawienia filtrowania.

---

### 6. Modal dodawania i edycji zamówienia

#### Kontener

```html
<div class="overlay hidden" id="new-order-overlay"></div>
```

Modal obsługuje:

- tworzenie nowych zamówień,
- edycję istniejących,
- podgląd szczegółów.

---

### 7. Formularz zamówienia

#### Formularz

```html
<form id="new-order-form"></form>
```

zawiera wszystkie dane powiązane z zamówieniem.

---

### 8. Pola formularza

#### Dane podstawowe

| Pole              | Opis                     |
| ----------------- | ------------------------ |
| klient            | przypisanie klienta      |
| typ zamówienia    | wybór typu               |
| tytuł             | nazwa zamówienia         |
| opis              | opis szczegółowy         |
| cena              | koszt realizacji         |
| termin realizacji | planowany termin         |
| data utworzenia   | data wygenerowania wpisu |

---

### 9. System tagów

#### Sekcja tagów

```html
<div id="tags"></div>
```

umożliwia przypisywanie tagów do zamówienia.

#### Modal tagów

```html
<div id="tags-showbox" class="showbox"></div>
```

wyświetla listę dostępnych tagów.

#### Obsługa logiki

System zarządzania tagami został wydzielony do pliku:

```text
tags_controller.js
```

---

### 10. System załączników

#### Sekcja

```html
<ul id="links"></ul>
```

umożliwia dodawanie linków powiązanych z zamówieniem.

#### Modal edycji linków

```html
<div id="links-showbox" class="showbox"></div>
```

umożliwia:

- dodawanie,
- edycję,
- zapis linków.

#### Dane załącznika

| Pole   | Opis        |
| ------ | ----------- |
| Tytuł  | nazwa linku |
| Źródło | adres URL   |

#### Obsługa logiki

Za zarządzanie linkami odpowiada:

```text
links_controller.js
```

---

### 11. System zdjęć

#### Sekcja zdjęć

```html
<div id="photos-selected"></div>
```

przechowuje aktualnie przypisane zdjęcia.

#### Modal galerii

```html
<div id="photos-showbox"></div>
```

umożliwia wybór zdjęć z galerii systemowej.

#### Funkcje modułu

- wyszukiwanie zdjęć,
- wybór wielu pozycji,
- przypisywanie zdjęć do zamówienia.

#### Obsługa logiki

Za system zdjęć odpowiada:

```text
photos.js
```

---

### 12. Modal wyboru klienta

#### Sekcja

```html
<div id="client-modal"></div>
```

umożliwia wybór klienta z listy.

#### Funkcjonalności

- wyszukiwanie klientów,
- sortowanie,
- przypisywanie klienta,
- usuwanie wyboru.

#### Tabela klientów

Tabela prezentuje:

- ID klienta,
- imię i nazwisko,
- akcję wyboru.

#### Obsługa logiki

Za logikę klientów odpowiada:

```text
clients_controller.js
```

---

### 13. Podział logiki na kontrolery

System wykorzystuje modularną architekturę JavaScript.

#### Wykorzystane moduły

| Plik                        | Odpowiedzialność       |
| --------------------------- | ---------------------- |
| `orders.js`                 | główna logika modułu   |
| `clients_controller.js`     | obsługa klientów       |
| `tags_controller.js`        | obsługa tagów          |
| `links_controller.js`       | obsługa załączników    |
| `orders_controller.js`      | operacje CRUD zamówień |
| `order_types_controller.js` | typy zamówień          |
| `photos.js`                 | obsługa galerii        |

---

### 14. Mechanizm autoryzacji

#### Funkcja inicjalizacyjna

```javascript
async function init()
```

sprawdza autoryzację użytkownika.

#### Endpoint

```text
../logowanie/api/auth.php
```

#### Obsługiwane przypadki

| Kod HTTP | Działanie        |
| -------- | ---------------- |
| 401      | brak dostępu     |
| 403      | konto nieaktywne |
| 200      | dostęp przyznany |

---

### 15. Inicjalizacja modułów

Po poprawnej autoryzacji uruchamiane są moduły:

```javascript
loadLinksController();
loadPhotosController();
loadClientsController();
loadTagsController();
loadOrdersController();
```

Następnie ładowane są dane zamówień:

```javascript
await loadOrders();
```

---

### 16. Tryb podglądu (`view only`)

System obsługuje tryb tylko do odczytu.

#### Mechanizm

Tryb aktywowany jest parametrem URL:

```text
?isViewOnly=true
```

#### Funkcja

```javascript
applyViewMode();
```

sprawdza obecność parametru i blokuje formularz.

---

### 17. Blokowanie formularza

#### Funkcja

```javascript
setFormDisabled((disabled = true));
```

odpowiada za:

- blokowanie pól formularza,
- blokowanie przycisków,
- ukrywanie przycisku usuwania.

#### Wyjątki

Niektóre przyciski pozostają aktywne:

- anulowanie,
- zamknięcie modali.

---

### 18. Formatowanie dat

#### Funkcja

```javascript
formatDatePL(date);
```

konwertuje datę do polskiego formatu:

```text
DD-MM-RRRR HH:MM
```

### `clients_controller.js`

#### 1. Opis pliku

Plik `clients_controller.js` odpowiada za obsługę wyboru klientów w module zamówień.

Moduł realizuje:

- otwieranie modala wyboru klienta,
- pobieranie listy klientów,
- renderowanie tabeli klientów,
- filtrowanie klientów,
- przypisywanie klienta do formularza zamówienia,
- czyszczenie aktualnego wyboru.

---

#### 2. Funkcja inicjalizacyjna

##### Funkcja

```javascript
loadClientsController();
```

odpowiada za inicjalizację wszystkich event listenerów modułu.

---

#### 3. Otwieranie modala wyboru klienta

##### Obsługiwane elementy

```javascript
#select-client
.select-client
```

##### Mechanizm działania

Po kliknięciu:

- otwierany jest modal klienta,
- resetowane jest pole wyszukiwania,
- pobierana jest lista klientów.

##### Fragment kodu

```javascript
$("#client-modal").show();
```

---

#### 4. Wybór klienta

##### Obsługa przycisku wyboru

```javascript
.select-client-btn
```

##### Mechanizm działania

Po wybraniu klienta:

- ustawiany jest identyfikator klienta,
- ustawiana jest nazwa klienta,
- zamykany jest modal.

##### Aktualizowane pola

| Pole               | Opis                  |
| ------------------ | --------------------- |
| `#client-id`       | identyfikator klienta |
| `#selected-client` | nazwa klienta         |

---

#### 5. Integracja z filtrowaniem zamówień

Po wyborze klienta wykonywane jest:

```javascript
$("#client-selected").val(name).trigger("input");
```

Mechanizm umożliwia integrację z systemem filtrowania zamówień.

---

#### 6. Wyszukiwanie klientów

##### Pole wyszukiwania

```javascript
#client-search
```

##### Mechanizm działania

Podczas wpisywania tekstu:

- dane są filtrowane lokalnie,
- renderowana jest nowa lista klientów.

##### Algorytm filtrowania

Filtrowanie odbywa się metodą:

```javascript
includes();
```

na połączonych danych:

```javascript
imie + nazwisko;
```

---

#### 7. Zamykanie modala

##### Przycisk

```javascript
#client-modal-close
```

##### Działanie

Po zamknięciu:

- modal zostaje ukryty,
- resetowane jest wyszukiwanie,
- przywracana jest pełna lista klientów.

---

#### 8. Czyszczenie wybranego klienta

##### Przycisk

```javascript
#clear-client
```

##### Mechanizm działania

System:

- usuwa identyfikator klienta,
- usuwa nazwę klienta,
- resetuje filtrowanie,
- zamyka modal.

---

#### 9. Przechowywanie danych

##### Zmienne globalne

```javascript
let clients = [];
let clientsLoaded = false;
```

##### Znaczenie

| Zmienna         | Opis                            |
| --------------- | ------------------------------- |
| `clients`       | lista klientów                  |
| `clientsLoaded` | informacja o załadowaniu danych |

---

#### 10. Mechanizm cache danych

System wykorzystuje prosty cache frontendowy.

##### Mechanizm działania

Jeżeli dane zostały wcześniej pobrane:

```javascript
if (clientsLoaded)
```

lista klientów renderowana jest bez ponownego wykonywania zapytania HTTP.

Pozwala to:

- zmniejszyć liczbę requestów,
- przyspieszyć działanie modala,
- ograniczyć obciążenie backendu.

---

#### 11. Pobieranie klientów

##### Funkcja

```javascript
loadClients();
```

odpowiada za pobranie danych klientów z backendu.

##### Endpoint API

```text
api/fetch_clients.php
```

##### Format odpowiedzi

Backend zwraca dane JSON zawierające listę klientów.

---

#### 12. Renderowanie tabeli klientów

##### Funkcja

```javascript
renderClients(data);
```

odpowiada za generowanie tabeli klientów.

##### Parametr funkcji

| Parametr | Opis           |
| -------- | -------------- |
| `data`   | lista klientów |

---

#### 13. Obsługa pustych wyników

Jeżeli lista klientów jest pusta:

```javascript
if (data.length === 0)
```

system wyświetla komunikat:

```text
Brak wyników
```

---

#### 14. Dynamiczne generowanie tabeli

Tabela generowana jest dynamicznie metodą:

```javascript
tbody.append();
```

Każdy rekord zawiera:

- ID klienta,
- imię i nazwisko,
- przycisk wyboru.

---

#### 15. Struktura danych klienta

Przykładowy obiekt klienta:

```json
{
  "id": 1,
  "imie": "Jan",
  "nazwisko": "Kowalski"
}
```

---

#### 16. Technologie wykorzystane w module

| Technologia | Zastosowanie            |
| ----------- | ----------------------- |
| JavaScript  | logika aplikacji        |
| jQuery      | obsługa DOM             |
| Fetch API   | komunikacja z backendem |
| JSON        | format danych           |

---

#### 17. Architektura działania

Schemat działania modułu:

```text
Modal → Fetch API → Backend PHP → JSON → Render tabeli
```

---

#### 18. Integracja z modułem zamówień

Moduł współpracuje z formularzem zamówień poprzez:

| Element            | Funkcja                    |
| ------------------ | -------------------------- |
| `#client-id`       | przechowywanie ID klienta  |
| `#selected-client` | wyświetlanie nazwy klienta |
| `#client-selected` | integracja z filtrowaniem  |

### `links_controller.js`

#### Opis pliku

Plik odpowiada za obsługę załączników w formie linków przypisanych do zamówienia.
Umożliwia:

- dodawanie linków,
- edytowanie istniejących linków,
- usuwanie linków,
- renderowanie listy linków w formularzu zamówienia.

Dane przechowywane są tymczasowo w tablicy JavaScript `links`.

---

#### Główne elementy

##### Zmienne globalne

```js
let links = [];
let editingIndex = null;
```

###### `links`

Tablica przechowująca wszystkie linki przypisane do aktualnie edytowanego zamówienia.

Przykładowy element:

```js
{
  title: "Projekt logo",
  href: "https://example.com/logo.png"
}
```

---

###### `editingIndex`

Przechowuje indeks aktualnie edytowanego linku.

- `null` → dodawanie nowego linku,
- liczba → edycja istniejącego linku.

---

#### Funkcja `loadLinksController()`

###### Opis

Funkcja inicjalizująca wszystkie event listenery związane z obsługą linków.

Wywoływana podczas inicjalizacji strony zamówień.

---

#### Dodawanie nowego linku

```js
$("#add-link").on("click", function (e)
```

###### Działanie

Po kliknięciu przycisku:

1. resetowany jest `editingIndex`,
2. czyszczone są pola formularza,
3. otwierany jest modal `#links-showbox`.

---

#### Zapisywanie linku

```js
$("#links-showbox-save-close-button").on("click", function (e)
```

###### Działanie

Po kliknięciu przycisku:

1. pobierany jest tytuł i adres URL,
2. wykonywana jest podstawowa walidacja,
3. tworzony jest obiekt linku,
4. link zostaje:
   - dodany do tablicy,
   - lub nadpisany podczas edycji,

5. wykonywany jest ponowny render listy,
6. modal zostaje zamknięty.

---

##### Walidacja

```js
if (!title || !href) return;
```

Sprawdzane jest jedynie, czy pola nie są puste.

Nie jest wykonywana walidacja poprawności URL.

---

#### Zamknięcie okna edycji

```js
$("#links-showbox-close-button").on("click", function (e)
```

###### Działanie

Ukrywa modal bez zapisywania zmian.

---

#### Edycja istniejącego linku

```js
$("#links").on("click", ".link-title", function (e)
```

###### Działanie

Po kliknięciu w nazwę linku:

1. pobierany jest indeks elementu,
2. odczytywany jest obiekt z tablicy `links`,
3. formularz zostaje uzupełniony danymi,
4. ustawiany jest `editingIndex`,
5. otwierany jest modal edycji.

---

#### Usuwanie linku

```js
$("#links").on("click", ".delete-link", function ()
```

###### Działanie

1. pobierany jest indeks linku,
2. wyświetlane jest okno potwierdzenia,
3. element zostaje usunięty z tablicy `links`,
4. wykonywany jest ponowny render listy.

---

#### Funkcja `renderLinks()`

###### Opis

Funkcja generuje listę linków widoczną w formularzu zamówienia.

---

##### Działanie

###### 1. Czyszczenie listy

```js
list.empty();
```

Usuwane są wszystkie wcześniejsze elementy HTML.

---

###### 2. Generowanie nowych elementów

Dla każdego linku tworzony jest element:

```html
<li>
  <a href="..." target="_blank">...</a>
  <i class="bi bi-trash"></i>
</li>
```

---

##### Elementy renderowane

###### Link

```html
<a href="..." target="_blank"></a>
```

Po kliknięciu otwierany jest w nowej karcie przeglądarki.

---

###### Ikona usuwania

```html
<i class="bi bi-trash"></i>
```

Służy do usuwania linku.

---

#### Powiązania z innymi modułami

Plik współpracuje z:

- `orders_controller.js`
- formularzem tworzenia i edycji zamówienia,
- modalem `#links-showbox`.

Tablica `links` jest wykorzystywana podczas zapisu zamówienia do backendu.

---

#### Zastosowane technologie

- JavaScript
- jQuery
- Bootstrap Icons
- dynamiczna manipulacja DOM
- event delegation (`.on()`)

---

#### Logika działania modułu

##### Schemat

1. Użytkownik klika „Dodaj link”
2. Otwiera się modal
3. Użytkownik wpisuje dane
4. Dane trafiają do tablicy `links`
5. `renderLinks()` odświeża widok
6. Link pojawia się na liście załączników

---

#### Uwagi techniczne

##### Delegacja zdarzeń

```js
$("#links").on("click", ".link-title", ...)
```

Zastosowano delegację zdarzeń, ponieważ elementy listy są tworzone dynamicznie.

---

##### Przechowywanie danych

Dane przechowywane są wyłącznie po stronie klienta do momentu zapisania zamówienia.

---

##### Renderowanie dynamiczne

Widok listy jest każdorazowo generowany od nowa na podstawie aktualnej zawartości tablicy `links`.

### `order_types_controller.js`

#### Opis pliku

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

#### Zmienne globalne

```js
let orderTypes = [];
```

###### Opis

Tablica przechowująca wszystkie pobrane typy zamówień.

Przykładowy obiekt:

```js
{
  id: 1,
  tytul: "Projekt graficzny"
}
```

---

#### Funkcja `loadOrderTypesController()`

```js
function loadOrderTypesController() {}
```

###### Opis

Funkcja inicjalizacyjna kontrolera.

Obecnie nie zawiera logiki.

Może zostać wykorzystana w przyszłości do:

- inicjalizacji event listenerów,
- preloadingu danych,
- konfiguracji modułu.

---

#### Sekcja formularza zamówienia

##### Funkcja `loadOrderTypes(selectedId = null)`

###### Opis

Funkcja pobiera typy zamówień z backendu i generuje listę `<select>` w formularzu zamówienia.

---

##### Parametry

###### `selectedId`

```js
selectedId = null;
```

ID typu zamówienia, który ma zostać automatycznie zaznaczony.

Wykorzystywane głównie podczas edycji istniejącego zamówienia.

---

##### Mechanizm cache

```js
if (orderTypes.length > 0)
```

Jeżeli dane zostały już wcześniej pobrane:

- nie wykonywany jest kolejny request HTTP,
- wykorzystywana jest lokalna tablica `orderTypes`.

Zmniejsza to liczbę zapytań do backendu.

---

##### Pobieranie danych

```js
fetch("api/fetch_order_types.php");
```

Endpoint zwraca listę wszystkich typów zamówień w formacie JSON.

---

##### Przetwarzanie danych

Po pobraniu:

1. dane zapisywane są do `orderTypes`,
2. wywoływana jest funkcja renderująca formularz.

---

#### Funkcja `renderOrderTypesForm(selectedId = null)`

###### Opis

Funkcja generuje elementy `<option>` w formularzu zamówienia.

---

##### Generowanie domyślnej opcji

```js
<select>
  <option value="">-- wybierz --</option>
</select>
```

Pusta opcja wymusza świadomy wybór typu zamówienia przez użytkownika.

---

##### Generowanie listy typów

Dla każdego typu tworzony jest:

```html
<option value="ID">NAZWA</option>
```

---

##### Automatyczne zaznaczenie wartości

```js
const selected = selectedId == t.id ? "selected" : "";
```

Jeżeli `selectedId` odpowiada aktualnemu elementowi:

- opcja zostaje automatycznie zaznaczona.

Mechanizm używany przy edycji zamówień.

---

#### Sekcja filtrowania tabeli

##### Funkcja `loadOrderTypesFilter()`

###### Opis

Pobiera typy zamówień z backendu i generuje filtr typów w tabeli zamówień.

---

##### Pobieranie danych

```js
fetch("api/fetch_order_types.php");
```

Wykorzystywany jest ten sam endpoint co w formularzu zamówienia.

---

##### Renderowanie

Po pobraniu danych wykonywana jest funkcja:

```js
renderOrderTypesFilter(data);
```

---

#### Funkcja `renderOrderTypesFilter(data)`

###### Opis

Generuje listę typów zamówień w filtrze tabeli.

---

##### Element docelowy

```js
const select = $("#title-sort");
```

Filtr odpowiada za wybór typu zamówienia.

---

##### Opcja domyślna

```html
<option value="">-- wszystkie --</option>
```

Pozwala wyświetlić wszystkie typy zamówień bez filtrowania.

---

##### Generowanie opcji

Dla każdego typu zamówienia tworzony jest:

```html
<option value="ID">NAZWA</option>
```

---

#### Powiązania z innymi modułami

Plik współpracuje z:

- `orders_controller.js`
- formularzem tworzenia zamówienia,
- formularzem edycji zamówienia,
- systemem filtrowania tabeli.

---

#### Zastosowane technologie

- JavaScript
- jQuery
- Fetch API
- dynamiczna manipulacja DOM
- REST API

---

#### Logika działania modułu

##### Formularz zamówienia

1. Wywołanie `loadOrderTypes()`
2. Pobranie danych z backendu
3. Zapis danych w `orderTypes`
4. Wygenerowanie listy `<option>`
5. Wyświetlenie danych użytkownikowi

---

##### Filtrowanie tabeli

1. Wywołanie `loadOrderTypesFilter()`
2. Pobranie listy typów
3. Wygenerowanie filtra
4. Użytkownik wybiera typ
5. Tabela zamówień zostaje przefiltrowana

---

#### Uwagi techniczne

##### Cache danych

Mechanizm:

```js
if (orderTypes.length > 0)
```

eliminuje zbędne pobieranie danych.

To dobre rozwiązanie wydajnościowe.

---

##### Oddzielenie renderowania od pobierania

Kod został poprawnie podzielony na:

- pobieranie danych,
- renderowanie widoku.

Ułatwia to utrzymanie i rozwój modułu.

---

##### Brak obsługi błędów

W module nie zastosowano `.catch()` dla requestów `fetch()`.

W przypadku błędu API użytkownik nie otrzyma informacji o problemie.
