> [Strona Główna](../README.md)

# `tags_controller.js`

## Opis pliku

Plik odpowiada za obsługę tagów przypisywanych do zamówień.

Moduł realizuje:

- otwieranie modala wyboru tagów,
- pobieranie tagów z backendu,
- zaznaczanie i odznaczanie checkboxów,
- tymczasowe przechowywanie zmian,
- zapisywanie wybranych tagów,
- renderowanie tagów w formularzu zamówienia.

Tagi pozwalają organizować i oznaczać zamówienia według wybranych kategorii.

---

## Inicjalizacja kontrolera

```js
loadTagsController();
```

Funkcja inicjalizuje wszystkie event listenery związane z obsługą tagów.

---

## Ukrycie modala przy starcie

```js
$("#tags-showbox").hide();
```

Okno wyboru tagów jest ukrywane podczas ładowania strony.

---

## Event listenery

### Otwieranie modala

```js
$("#edit-tags").on("click", openTagsModal);
```

Kliknięcie przycisku otwiera modal wyboru tagów.

---

### Obsługa checkboxów

```js
$(document).on("change", "#tags-list input[type='checkbox']", function () {
  handleTagsCheckboxSelect.call(this);
});
```

Listener reaguje na:

- zaznaczenie tagu,
- odznaczenie tagu.

Zastosowano delegację zdarzeń, ponieważ checkboxy generowane są dynamicznie.

---

### Zapis zmian

```js
$("#tags-showbox-save-close-button").on("click", saveAndCloseTagsModal);
```

Przycisk:

- zapisuje wybrane tagi,
- renderuje je w formularzu,
- zamyka modal.

---

### Zamknięcie bez zapisu

```js
$("#tags-showbox-close-button").on("click", function (e) {
  e.preventDefault();
  $("#tags-showbox").hide();
});
```

Anulowanie zmian:

- zamyka modal,
- nie zapisuje zmian,
- pozostawia poprzedni stan tagów.

---

## Zmienne globalne

### `selectedTags`

```js
let selectedTags = [];
```

Przechowuje finalną listę tagów przypisanych do zamówienia.

Dane z tej tablicy wysyłane są do backendu.

---

### `tempSelectedTags`

```js
let tempSelectedTags = [];
```

Tymczasowy stan checkboxów w modalu.

Pozwala:

- anulować zmiany,
- edytować tagi bez natychmiastowego zapisu.

Mechanizm działa podobnie do „bufora zmian”.

---

## Renderowanie modala tagów

### `renderTagsModal()`

Funkcja pobiera wszystkie tagi z backendu i generuje listę checkboxów.

---

### Pobieranie tagów

```js
fetch("api/fetch_tags.php");
```

Backend zwraca listę wszystkich dostępnych tagów.

---

### Synchronizacja stanu

```js
tempSelectedTags = [...selectedTags];
```

Tworzona jest kopia aktualnych tagów.

Dzięki temu:

- użytkownik może anulować zmiany,
- oryginalna lista pozostaje bez zmian aż do zapisu.

---

### Renderowanie checkboxów

```js
$("#tags-list").append(`
  <div class="tags-list-checkable-element">
    <label>
      <input type="checkbox" value="${tagId}" ${checked}>
      ${tag.name}
    </label>
  </div>
`);
```

Każdy tag renderowany jest jako:

- checkbox,
- nazwa tagu,
- osobny element listy.

---

## Otwieranie modala

### `openTagsModal(e)`

Funkcja:

- blokuje domyślne działanie przycisku,
- pokazuje modal,
- renderuje aktualną listę tagów.

---

### Wyświetlenie modala

```js
$("#tags-showbox").show();
```

---

### Aktualizacja listy

```js
renderTagsModal();
```

Lista checkboxów tworzona jest dynamicznie przy każdym otwarciu.

Dzięki temu dane zawsze są aktualne.

---

## Obsługa zaznaczania tagów

### `handleTagsCheckboxSelect()`

Funkcja odpowiada za aktualizację tymczasowej listy tagów.

---

### Dodawanie tagu

```js
tempSelectedTags.push(tagId);
```

Dodaje ID tagu do listy zaznaczonych tagów.

---

### Usuwanie tagu

```js
tempSelectedTags = tempSelectedTags.filter((id) => id !== tagId);
```

Usuwa tag z tymczasowej listy.

---

## Zapis tagów

### `saveAndCloseTagsModal(e)`

Funkcja zapisuje wybrane tagi jako aktualny stan zamówienia.

---

### Zatwierdzenie zmian

```js
selectedTags = [...tempSelectedTags];
```

Tymczasowy stan zostaje zapisany jako właściwy stan formularza.

---

### Czyszczenie widoku

```js
$("#tags").html("");
```

Usuwane są wcześniej wyświetlone tagi.

---

### Renderowanie tagów

```js
$("#tags").append(`<span class="tag">${tagName}</span>`);
```

Wybrane tagi renderowane są jako elementy:

```html
<span class="tag"></span>
```

---

## Pobieranie nazw tagów

Nazwy pobierane są bezpośrednio z checkboxów:

```js
$(`#tags-list input[value="${tagId}"]`);
```

Następnie pobierany jest tekst etykiety:

```js
.parent().text().trim()
```

---

## Struktura danych tagów

Tagi przechowywane są jako lista identyfikatorów:

```js
[1, 2, 5];
```

Podczas renderowania pobierane są odpowiadające im nazwy.

---

## Integracja z formularzem zamówień

`selectedTags` wykorzystywane jest podczas zapisu zamówienia:

```js
tagi: selectedTags;
```

Dane trafiają następnie do backendu jako JSON.

---

## Architektura działania

Moduł wykorzystuje model dwóch stanów:

| Stan               | Rola              |
| ------------------ | ----------------- |
| `selectedTags`     | zapisane tagi     |
| `tempSelectedTags` | tymczasowe zmiany |

Pozwala to na:

- bezpieczną edycję,
- anulowanie zmian,
- oddzielenie danych zapisanych od roboczych.

---

## Zależności

Moduł współpracuje z:

| Element                | Funkcja              |
| ---------------------- | -------------------- |
| `orders_controller.js` | zapis formularza     |
| `fetch_tags.php`       | pobieranie tagów     |
| `zamowienia.tagi`      | zapis danych w bazie |
| `#tags`                | render tagów         |
| `#tags-showbox`        | modal wyboru         |
