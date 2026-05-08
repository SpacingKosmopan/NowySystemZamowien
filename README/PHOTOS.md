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

```js id="6q7yzp"
loadPhotosController();
```

---

## Otwieranie modala zdjęć

### Event

```js id="9iqwbo"
#edit-photos
```

### Działanie

```js id="gsyc4k"
document.getElementById("photos-showbox").classList.remove("hidden");
```

Pokazuje modal wyboru zdjęć.

---

## Ładowanie zdjęć

Po otwarciu wykonywane jest:

```js id="c4hyb6"
await loadPhotos();
```

Dzięki temu lista zdjęć jest zawsze aktualna.

---

## Zamknięcie modala

### Event

```js id="n0eqxu"
#photos-showbox-close-button
```

### Działanie

```js id="vnp7z2"
classList.add("hidden");
```

Ukrywa modal bez zapisywania zmian wizualnych.

---

## Zapis wybranych zdjęć

### Event

```js id="0p5sxe"
#photos-showbox-save-close-button
```

---

### Działanie

```js id="bh2d8r"
renderSelectedPhotos();
```

Odświeża miniatury zdjęć przypisanych do zamówienia.

Następnie modal zostaje zamknięty.

---

## Wyszukiwarka zdjęć

### Event

```js id="v2uw5q"
#photos-search
```

---

## Mechanizm

```js id="r6ncz4"
.addEventListener("input", loadPhotos);
```

Każda zmiana tekstu:

- ponownie ładuje zdjęcia,
- filtruje listę.

---

# Globalne zmienne

### `selectedPhotoIds`

```js id="2h6j0d"
let selectedPhotoIds = [];
```

Przechowuje ID zaznaczonych zdjęć.

Przykład:

```js id="b90b88"
[1, 5, 12];
```

---

### `allPhotos`

```js id="3kfg5w"
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

```js id="s86r1n"
await loadPhotos();
```

Zapewnia aktualną listę zdjęć.

---

## Kontener miniatur

```js id="h7o3kx"
#photos-selected
```

---

## Czyszczenie widoku

```js id="9vf6aa"
container.innerHTML = "";
```

---

## Render miniatur

Dla każdego ID:

```js id="tx6pq4"
const photo = allPhotos.find(...)
```

wyszukiwane jest odpowiadające zdjęcie.

---

## Obsługiwane rozszerzenia

```js id="1t6pbj"
jpg;
jpeg;
png;
```

Sprawdzanie:

```js id="s0rq8m"
photo.sciezka.match(/\.(jpg|jpeg|png)$/i);
```

---

## Generowanie miniatury

Tworzony jest:

```html id="wj9e4x"
<a>
  <img />
</a>
```

---

## Link do zdjęcia

```js id="fmxwz6"
link.href = "../galeria/uploads/" + photo.sciezka;
```

Kliknięcie otwiera oryginalny plik.

---

## Miniatura

```js id="p95e6j"
img.src = "../galeria/uploads/" + photo.sciezka;
```

---

## Rozmiar miniatur

```js id="i5ht5o"
80x80
```

---

## Style miniatur

Ustawiane dynamicznie:

```js id="zw2l2h"
objectFit = "cover";
borderRadius = "5px";
margin = "5px";
```

---

## Fallback dla nieobsługiwanych plików

Jeżeli plik nie jest obrazem:

```js id="jlwmql"
const span = document.createElement("span");
```

wyświetlane jest samo ID pliku.

---

## `loadPhotos()`

### Opis

Pobiera zdjęcia z backendu i renderuje listę wyboru w modalu.

---

## API

```js id="ehjzmx"
fetch("../galeria/api/files.php");
```

---

## Odpowiedź backendu

Przykładowy format:

```js id="k0zvte"
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

```js id="wgsjml"
allPhotos = photos;
```

---

## Kontener listy

```js id="2l2fho"
#photos-list
```

---

## Czyszczenie listy

```js id="bch2y6"
photosList.innerHTML = "";
```

---

## Pobieranie tekstu wyszukiwania

```js id="2i1nn6"
#photos-search
```

---

## Filtrowanie

```js id="n6m2zd"
photo.nazwa_pliku.toLowerCase().includes(searchTerm);
```

---

## Render pojedynczego zdjęcia

Każdy wpis generuje:

```html id="w9t8og"
<div class="photo-item"></div>
```

---

## Checkbox wyboru

```html id="w7g4rt"
<input type="checkbox" />
```

---

## Synchronizacja zaznaczenia

```js id="wqxb4v"
checkbox.checked = selectedPhotoIds.includes(Number(photo.id));
```

---

## Obsługa zmiany checkboxa

### Dodawanie zdjęcia

```js id="x8i0p0"
selectedPhotoIds.push(pid);
```

---

### Usuwanie zdjęcia

```js id="k1y4ol"
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

```js id="j4gcfz"
photo.nazwa_pliku;
```

---

## Miniatura w modalu

Jeżeli plik jest obrazem:

```js id="czh1tw"
<img>
```

dodawana jest miniatura.

---

## `setSelectedPhotos(photoIds)`

### Opis

Ustawia zaznaczone zdjęcia podczas edycji zamówienia.

---

## Konwersja ID

```js id="4p5e0q"
photoIds.map(Number);
```

Zapewnia typ `number`.

---

## Odświeżenie widoku

```js id="s8yx0s"
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

   ```js id="f7p3p2"
   renderSelectedPhotos();
   ```

7. Formularz pokazuje miniatury.
