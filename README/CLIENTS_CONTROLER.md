> [Strona Główna](../README.md)

# `clients_controller.js`

## 1. Opis pliku

Plik `clients_controller.js` odpowiada za obsługę wyboru klientów w module zamówień.

Moduł realizuje:

- otwieranie modala wyboru klienta,
- pobieranie listy klientów,
- renderowanie tabeli klientów,
- filtrowanie klientów,
- przypisywanie klienta do formularza zamówienia,
- czyszczenie aktualnego wyboru.

---

## 2. Funkcja inicjalizacyjna

### Funkcja

```javascript
loadClientsController();
```

odpowiada za inicjalizację wszystkich event listenerów modułu.

---

## 3. Otwieranie modala wyboru klienta

### Obsługiwane elementy

```javascript
#select-client
.select-client
```

### Mechanizm działania

Po kliknięciu:

- otwierany jest modal klienta,
- resetowane jest pole wyszukiwania,
- pobierana jest lista klientów.

### Fragment kodu

```javascript
$("#client-modal").show();
```

---

## 4. Wybór klienta

### Obsługa przycisku wyboru

```javascript
.select-client-btn
```

### Mechanizm działania

Po wybraniu klienta:

- ustawiany jest identyfikator klienta,
- ustawiana jest nazwa klienta,
- zamykany jest modal.

### Aktualizowane pola

| Pole               | Opis                  |
| ------------------ | --------------------- |
| `#client-id`       | identyfikator klienta |
| `#selected-client` | nazwa klienta         |

---

## 5. Integracja z filtrowaniem zamówień

Po wyborze klienta wykonywane jest:

```javascript
$("#client-selected").val(name).trigger("input");
```

Mechanizm umożliwia integrację z systemem filtrowania zamówień.

---

## 6. Wyszukiwanie klientów

### Pole wyszukiwania

```javascript
#client-search
```

### Mechanizm działania

Podczas wpisywania tekstu:

- dane są filtrowane lokalnie,
- renderowana jest nowa lista klientów.

### Algorytm filtrowania

Filtrowanie odbywa się metodą:

```javascript
includes();
```

na połączonych danych:

```javascript
imie + nazwisko;
```

---

## 7. Zamykanie modala

### Przycisk

```javascript
#client-modal-close
```

### Działanie

Po zamknięciu:

- modal zostaje ukryty,
- resetowane jest wyszukiwanie,
- przywracana jest pełna lista klientów.

---

## 8. Czyszczenie wybranego klienta

### Przycisk

```javascript
#clear-client
```

### Mechanizm działania

System:

- usuwa identyfikator klienta,
- usuwa nazwę klienta,
- resetuje filtrowanie,
- zamyka modal.

---

## 9. Przechowywanie danych

### Zmienne globalne

```javascript
let clients = [];
let clientsLoaded = false;
```

### Znaczenie

| Zmienna         | Opis                            |
| --------------- | ------------------------------- |
| `clients`       | lista klientów                  |
| `clientsLoaded` | informacja o załadowaniu danych |

---

## 10. Mechanizm cache danych

System wykorzystuje prosty cache frontendowy.

### Mechanizm działania

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

## 11. Pobieranie klientów

### Funkcja

```javascript
loadClients();
```

odpowiada za pobranie danych klientów z backendu.

### Endpoint API

```text
api/fetch_clients.php
```

### Format odpowiedzi

Backend zwraca dane JSON zawierające listę klientów.

---

## 12. Renderowanie tabeli klientów

### Funkcja

```javascript
renderClients(data);
```

odpowiada za generowanie tabeli klientów.

### Parametr funkcji

| Parametr | Opis           |
| -------- | -------------- |
| `data`   | lista klientów |

---

## 13. Obsługa pustych wyników

Jeżeli lista klientów jest pusta:

```javascript
if (data.length === 0)
```

system wyświetla komunikat:

```text
Brak wyników
```

---

## 14. Dynamiczne generowanie tabeli

Tabela generowana jest dynamicznie metodą:

```javascript
tbody.append();
```

Każdy rekord zawiera:

- ID klienta,
- imię i nazwisko,
- przycisk wyboru.

---

## 15. Struktura danych klienta

Przykładowy obiekt klienta:

```json
{
  "id": 1,
  "imie": "Jan",
  "nazwisko": "Kowalski"
}
```

---

## 16. Technologie wykorzystane w module

| Technologia | Zastosowanie            |
| ----------- | ----------------------- |
| JavaScript  | logika aplikacji        |
| jQuery      | obsługa DOM             |
| Fetch API   | komunikacja z backendem |
| JSON        | format danych           |

---

## 17. Architektura działania

Schemat działania modułu:

```text
Modal → Fetch API → Backend PHP → JSON → Render tabeli
```

---

## 18. Integracja z modułem zamówień

Moduł współpracuje z formularzem zamówień poprzez:

| Element            | Funkcja                    |
| ------------------ | -------------------------- |
| `#client-id`       | przechowywanie ID klienta  |
| `#selected-client` | wyświetlanie nazwy klienta |
| `#client-selected` | integracja z filtrowaniem  |
