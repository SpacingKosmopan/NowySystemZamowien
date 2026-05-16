# „Galeria”

## Opis modułu

Moduł „Galeria” odpowiada za zarządzanie plikami przesyłanymi do systemu. Użytkownik może dodawać obrazy oraz dokumenty PDF, przeglądać zapisane pliki, filtrować je po nazwie oraz usuwać wybrane elementy.

Podstrona pełni funkcję prostego menedżera plików zintegrowanego z panelem administracyjnym aplikacji.

---

## Struktura podstrony

Interfejs składa się z kilku głównych elementów:

- bocznego panelu nawigacyjnego dashboardu,
- formularza przesyłania plików,
- pola wyszukiwania,
- listy zapisanych plików,
- sekcji statusu operacji.

Po wejściu na stronę wykonywana jest autoryzacja użytkownika poprzez endpoint `auth.php`. W przypadku braku aktywnej sesji użytkownik nie otrzymuje dostępu do modułu.

---

## Formularz przesyłania plików

Formularz umożliwia:

- wybór pliku z dysku,
- podanie opisu pliku,
- wysłanie danych do serwera.

Do przesyłania wykorzystano obiekt `FormData`, który pozwala przekazywać pliki binarne bez ręcznego kodowania danych.

```js
const formData = new FormData();
formData.append("file", file);
formData.append("opis", opis);
```

Dane przesyłane są metodą `POST` do endpointu:

```text
api/upload.php
```

---

## Walidacja plików

Przed wysłaniem wykonywana jest walidacja po stronie klienta.

### Sprawdzanie typu pliku

Dozwolone są wyłącznie:

- PNG,
- JPG / JPEG,
- PDF.

```js
const allowedTypes = ["image/png", "image/jpeg", "application/pdf"];
```

Blokowane są wszystkie inne rozszerzenia.

---

### Ograniczenie rozmiaru

Maksymalny rozmiar pliku wynosi:

```text
5 MB
```

Walidacja wykonywana jest przed wysłaniem danych na serwer.

---

## Ładowanie plików

Lista plików pobierana jest dynamicznie z backendu:

```text
api/files.php
```

Dane pobierane są asynchronicznie przy pomocy `fetch()`.

```js
const res = await fetch(url);
const files = await res.json();
```

Następnie JavaScript generuje elementy HTML reprezentujące pliki.

---

## Wyświetlanie obrazów

Dla plików graficznych tworzony jest dodatkowo podgląd miniatury.

Weryfikacja wykonywana jest poprzez sprawdzenie rozszerzenia pliku:

```js
if (f.sciezka.match(/\.(jpg|jpeg|png)$/i))
```

Miniatury są jednocześnie linkami prowadzącymi do pełnej wersji obrazu.

---

## Usuwanie plików

Każdy element galerii posiada przycisk „Usuń”.

Po kliknięciu:

1. wyświetlane jest okno potwierdzenia,
2. wykonywane jest żądanie `POST`,
3. backend usuwa rekord oraz plik z dysku,
4. lista plików zostaje odświeżona.

Żądanie wysyłane jest do:

```text
api/delete.php
```

---

## Wyszukiwanie plików

Podstrona umożliwia filtrowanie plików po nazwie.

Filtrowanie działa dynamicznie podczas wpisywania tekstu:

```js
nameInput.addEventListener("input", ...)
```

Wartość pola wyszukiwania przekazywana jest jako parametr GET:

```text
api/files.php?filter=nazwa
```

Backend zwraca wyłącznie rekordy spełniające warunek wyszukiwania.

---

## Mechanizm renderowania

Galeria renderowana jest całkowicie dynamicznie po stronie klienta.

JavaScript tworzy:

- kontenery plików,
- linki,
- miniatury,
- opisy,
- daty dodania,
- przyciski usuwania.

Do manipulacji DOM wykorzystano:

- natywne API JavaScript,
- jQuery.

---

## Obsługa asynchroniczna

W module szeroko wykorzystano:

- `async/await`,
- `fetch API`,
- operacje AJAX.

Dzięki temu:

- strona nie przeładowuje się,
- dane aktualizowane są dynamicznie,
- interfejs działa płynniej.

---

## Komunikacja frontend ↔ backend

Frontend komunikuje się z backendem PHP poprzez endpointy API:

| Endpoint     | Funkcja                 |
| ------------ | ----------------------- |
| `auth.php`   | autoryzacja użytkownika |
| `upload.php` | przesyłanie plików      |
| `files.php`  | pobieranie listy plików |
| `delete.php` | usuwanie plików         |

Backend zwraca:

- tekst,
- JSON,
- kody HTTP.

---

## Funkcjonalności modułu

Moduł umożliwia:

- dodawanie plików,
- wyświetlanie listy plików,
- podgląd obrazów,
- filtrowanie danych,
- usuwanie plików,
- dynamiczne odświeżanie zawartości,
- integrację z panelem administracyjnym.
