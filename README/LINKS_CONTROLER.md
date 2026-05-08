# `links_controller.js`

## Opis pliku

Plik odpowiada za obsługę załączników w formie linków przypisanych do zamówienia.
Umożliwia:

- dodawanie linków,
- edytowanie istniejących linków,
- usuwanie linków,
- renderowanie listy linków w formularzu zamówienia.

Dane przechowywane są tymczasowo w tablicy JavaScript `links`.

---

## Główne elementy

### Zmienne globalne

```js
let links = [];
let editingIndex = null;
```

#### `links`

Tablica przechowująca wszystkie linki przypisane do aktualnie edytowanego zamówienia.

Przykładowy element:

```js
{
  title: "Projekt logo",
  href: "https://example.com/logo.png"
}
```

---

#### `editingIndex`

Przechowuje indeks aktualnie edytowanego linku.

- `null` → dodawanie nowego linku,
- liczba → edycja istniejącego linku.

---

## Funkcja `loadLinksController()`

#### Opis

Funkcja inicjalizująca wszystkie event listenery związane z obsługą linków.

Wywoływana podczas inicjalizacji strony zamówień.

---

## Dodawanie nowego linku

```js
$("#add-link").on("click", function (e)
```

#### Działanie

Po kliknięciu przycisku:

1. resetowany jest `editingIndex`,
2. czyszczone są pola formularza,
3. otwierany jest modal `#links-showbox`.

---

## Zapisywanie linku

```js
$("#links-showbox-save-close-button").on("click", function (e)
```

#### Działanie

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

### Walidacja

```js
if (!title || !href) return;
```

Sprawdzane jest jedynie, czy pola nie są puste.

Nie jest wykonywana walidacja poprawności URL.

---

## Zamknięcie okna edycji

```js
$("#links-showbox-close-button").on("click", function (e)
```

#### Działanie

Ukrywa modal bez zapisywania zmian.

---

## Edycja istniejącego linku

```js
$("#links").on("click", ".link-title", function (e)
```

#### Działanie

Po kliknięciu w nazwę linku:

1. pobierany jest indeks elementu,
2. odczytywany jest obiekt z tablicy `links`,
3. formularz zostaje uzupełniony danymi,
4. ustawiany jest `editingIndex`,
5. otwierany jest modal edycji.

---

## Usuwanie linku

```js
$("#links").on("click", ".delete-link", function ()
```

#### Działanie

1. pobierany jest indeks linku,
2. wyświetlane jest okno potwierdzenia,
3. element zostaje usunięty z tablicy `links`,
4. wykonywany jest ponowny render listy.

---

## Funkcja `renderLinks()`

#### Opis

Funkcja generuje listę linków widoczną w formularzu zamówienia.

---

### Działanie

#### 1. Czyszczenie listy

```js
list.empty();
```

Usuwane są wszystkie wcześniejsze elementy HTML.

---

#### 2. Generowanie nowych elementów

Dla każdego linku tworzony jest element:

```html
<li>
  <a href="..." target="_blank">...</a>
  <i class="bi bi-trash"></i>
</li>
```

---

### Elementy renderowane

#### Link

```html
<a href="..." target="_blank"></a>
```

Po kliknięciu otwierany jest w nowej karcie przeglądarki.

---

#### Ikona usuwania

```html
<i class="bi bi-trash"></i>
```

Służy do usuwania linku.

---

## Logika działania modułu

### Schemat

1. Użytkownik klika „Dodaj link”
2. Otwiera się modal
3. Użytkownik wpisuje dane
4. Dane trafiają do tablicy `links`
5. `renderLinks()` odświeża widok
6. Link pojawia się na liście załączników
