> [Strona Główna](../README.md)

# `photos.js`

## Opis

Plik odpowiada za:

- wybór zdjęć przypisanych do zamówienia,
- renderowanie miniatur zdjęć,
- obsługę modala galerii,
- wyszukiwanie zdjęć,
- zaznaczanie i odznaczanie plików,
- synchronizację wybranych zdjęć z formularzem zamówienia.

Plik współpracuje głównie z:

- `orders.js`
- `orders_controller.js`
- modułem galerii (`../galeria/api/files.php`)

---

## Funkcja `loadPhotosController()`

### Opis

Inicjalizuje event listenery modułu zdjęć.

Uruchamiana podczas startu aplikacji:

```js
loadPhotosController();
```

---

## Otwieranie modala zdjęć

### Event

```js
#edit-photos
```

### Działanie

```js
document.getElementById("photos-showbox").classList.remove("hidden");
```

Pokazuje modal wyboru zdjęć.

---

## Ładowanie zdjęć

Po otwarciu wykonywane jest:

```js
await loadPhotos();
```

Dzięki temu lista zdjęć jest zawsze aktualna.

---

## Zamknięcie modala

### Event

```js
#photos-showbox-close-button
```

### Działanie

```js
classList.add("hidden");
```

Ukrywa modal bez zapisywania zmian wizualnych.

---

## Zapis wybranych zdjęć

### Event

```js
#photos-showbox-save-close-button
```

---

### Działanie

```js
renderSelectedPhotos();
```

Odświeża miniatury zdjęć przypisanych do zamówienia.

Następnie modal zostaje zamknięty.

---

## Wyszukiwarka zdjęć

### Event

```js
#photos-search
```

---

## Mechanizm

```js
.addEventListener("input", loadPhotos);
```

Każda zmiana tekstu:

- ponownie ładuje zdjęcia,
- filtruje listę.

---

# Globalne zmienne

### `selectedPhotoIds`

```js
let selectedPhotoIds = [];
```

Przechowuje ID zaznaczonych zdjęć.

Przykład:

```js
[1, 5, 12];
```

---

### `allPhotos`

```js
let allPhotos = [];
```

Cache wszystkich zdjęć pobranych z backendu.

---

## `renderSelectedPhotos()`

### Opis

Renderuje miniatury wybranych zdjęć w formularzu zamówienia.

---

# Ładowanie danych

Na początku wykonywane jest:

```js
await loadPhotos();
```

Zapewnia aktualną listę zdjęć.

---

## Kontener miniatur

```js
#photos-selected
```

---

## Czyszczenie widoku

```js
container.innerHTML = "";
```

---

## Render miniatur

Dla każdego ID:

```js
const photo = allPhotos.find(...)
```

wyszukiwane jest odpowiadające zdjęcie.

---

## Obsługiwane rozszerzenia

```js
jpg;
jpeg;
png;
```

Sprawdzanie:

```js
photo.sciezka.match(/\.(jpg|jpeg|png)$/i);
```

---

## Generowanie miniatury

Tworzony jest:

```html
<a>
  <img />
</a>
```

---

## Link do zdjęcia

```js
link.href = "../galeria/uploads/" + photo.sciezka;
```

Kliknięcie otwiera oryginalny plik.

---

## Miniatura

```js
img.src = "../galeria/uploads/" + photo.sciezka;
```

---

## Rozmiar miniatur

```js
80x80
```

---

## Style miniatur

Ustawiane dynamicznie:

```js
objectFit = "cover";
borderRadius = "5px";
margin = "5px";
```

---

## Fallback dla nieobsługiwanych plików

Jeżeli plik nie jest obrazem:

```js
const span = document.createElement("span");
```

wyświetlane jest samo ID pliku.

---

## `loadPhotos()`

### Opis

Pobiera zdjęcia z backendu i renderuje listę wyboru w modalu.

---

## API

```js
fetch("../galeria/api/files.php");
```

---

## Odpowiedź backendu

Przykładowy format:

```js
[
  {
    id: 1,
    nazwa_pliku: "logo.png",
    sciezka: "abc/logo.png",
  },
];
```

---

## Cache zdjęć

```js
allPhotos = photos;
```

---

## Kontener listy

```js
#photos-list
```

---

## Czyszczenie listy

```js
photosList.innerHTML = "";
```

---

## Pobieranie tekstu wyszukiwania

```js
#photos-search
```

---

## Filtrowanie

```js
photo.nazwa_pliku.toLowerCase().includes(searchTerm);
```

---

## Render pojedynczego zdjęcia

Każdy wpis generuje:

```html
<div class="photo-item"></div>
```

---

## Checkbox wyboru

```html
<input type="checkbox" />
```

---

## Synchronizacja zaznaczenia

```js
checkbox.checked = selectedPhotoIds.includes(Number(photo.id));
```

---

## Obsługa zmiany checkboxa

### Dodawanie zdjęcia

```js
selectedPhotoIds.push(pid);
```

---

### Usuwanie zdjęcia

```js
selectedPhotoIds =
  selectedPhotoIds.filter(...)
```

---

## Label zdjęcia

Renderowane są:

- checkbox,
- nazwa pliku,
- miniatura.

---

## Nazwa pliku

```js
photo.nazwa_pliku;
```

---

## Miniatura w modalu

Jeżeli plik jest obrazem:

```js
<img>
```

dodawana jest miniatura.

---

## `setSelectedPhotos(photoIds)`

### Opis

Ustawia zaznaczone zdjęcia podczas edycji zamówienia.

---

## Konwersja ID

```js
photoIds.map(Number);
```

Zapewnia typ `number`.

---

## Odświeżenie widoku

```js
renderSelectedPhotos();
```

---

## Przepływ działania modułu

### Dodawanie zdjęć

1. Użytkownik otwiera modal.
2. `loadPhotos()` pobiera dane.
3. Wyświetlana jest lista zdjęć.
4. Użytkownik zaznacza checkboxy.
5. ID trafiają do `selectedPhotoIds`.
6. Po zapisie wykonywane jest:

   ```js
   renderSelectedPhotos();
   ```

7. Formularz pokazuje miniatury.
