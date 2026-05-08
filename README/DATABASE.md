> [Strona Główna](../README.md)

# Baza danych

System wykorzystuje relacyjną bazę danych MySQL o nazwie:

```sql
neworders
```

Baza odpowiada za przechowywanie wszystkich kluczowych danych aplikacji, takich jak:

- zamówienia,
- klienci,
- użytkownicy,
- typy zamówień,
- tagi,
- zdjęcia,
- teksty,
- struktury katalogów.

Projekt opiera się na relacjach pomiędzy tabelami oraz wykorzystuje identyfikatory (`id`) jako klucze główne.

---

# Tabela `zamowienia`

Najważniejsza tabela systemu odpowiedzialna za przechowywanie zamówień.

## Pola

| Pole                | Typ           | Opis                     |
| ------------------- | ------------- | ------------------------ |
| `id`                | int           | Identyfikator zamówienia |
| `klient_id`         | int           | ID klienta               |
| `data_utworzenia`   | datetime      | Data utworzenia          |
| `status`            | enum          | Status zamówienia        |
| `kwota`             | decimal(10,2) | Kwota zamówienia         |
| `opis`              | text          | Opis zamówienia          |
| `termin_realizacji` | date          | Termin realizacji        |
| `tagi`              | longtext      | Lista tagów w JSON       |
| `zalaczniki`        | longtext      | Załączniki w JSON        |
| `zdjecia`           | text          | Lista zdjęć              |
| `typ_id`            | int           | Typ zamówienia           |
| `tytul`             | varchar(255)  | Tytuł zamówienia         |

---

## Statusy zamówień

Pole `status` wykorzystuje typ `ENUM`.

Dostępne wartości:

```sql
'nowe'
'w realizacji'
'zrealizowane'
'anulowane'
```

Status wykorzystywany jest między innymi do:

- filtrowania,
- kolorowania rekordów,
- statystyk,
- organizacji pracy.

---

## Relacje

Tabela `zamowienia` posiada relacje:

| Pole        | Relacja              |
| ----------- | -------------------- |
| `klient_id` | → `klienci.id`       |
| `typ_id`    | → `typy_zamowien.id` |

---

# Tabela `klienci`

Tabela przechowująca dane klientów.

## Pola

| Pole              | Typ          |
| ----------------- | ------------ |
| `id`              | int          |
| `imie`            | varchar(50)  |
| `nazwisko`        | varchar(50)  |
| `email`           | varchar(100) |
| `telefon`         | varchar(20)  |
| `adres`           | varchar(255) |
| `data_utworzenia` | timestamp    |

---

## Zastosowanie

Tabela wykorzystywana jest do:

- przypisywania klientów do zamówień,
- wyszukiwania klientów,
- filtrowania danych,
- generowania historii zamówień klienta.

---

# Tabela `typy_zamowien`

Tabela przechowuje typy zamówień.

## Pola

| Pole    | Typ          |
| ------- | ------------ |
| `id`    | int          |
| `tytul` | varchar(255) |

---

## Zastosowanie

Typy zamówień wykorzystywane są do:

- klasyfikacji zamówień,
- filtrowania tabel,
- organizacji pracy.

---

# Tabela `tagi`

Tabela przechowuje tagi możliwe do przypisania do zamówień.

## Pola

| Pole    | Typ          |
| ------- | ------------ |
| `id`    | int          |
| `nazwa` | varchar(255) |

---

## Zastosowanie

Tagi służą do:

- oznaczania zamówień,
- organizacji danych,
- filtrowania rekordów.

Lista przypisanych tagów zapisywana jest w tabeli `zamowienia` jako JSON.

---

# Tabela `zdjecia`

Tabela przechowująca informacje o przesłanych plikach graficznych.

## Pola

| Pole           | Typ          |
| -------------- | ------------ |
| `id`           | int          |
| `nazwa_pliku`  | varchar(255) |
| `opis`         | text         |
| `data_dodania` | timestamp    |
| `sciezka`      | text         |

---

## Zastosowanie

Tabela wykorzystywana jest przez moduł galerii oraz system załączania zdjęć do zamówień.

Przechowywane są:

- nazwy plików,
- ścieżki do plików,
- opisy,
- daty dodania.

---

# Tabela `uzytkownicy`

Tabela przechowuje dane użytkowników systemu.

## Pola

| Pole                 | Typ          |
| -------------------- | ------------ |
| `id`                 | int unsigned |
| `nazwa`              | varchar(50)  |
| `password_hash`      | text         |
| `ostatnie_logowanie` | datetime     |
| `utworzono`          | datetime     |
| `status`             | enum         |

---

## Statusy użytkowników

```sql
'oczekujacy'
'aktywny'
'zablokowany'
```

---

## Zastosowanie

Tabela odpowiada za:

- logowanie,
- autoryzację,
- zarządzanie dostępem,
- blokowanie użytkowników,
- przechowywanie hashy haseł.

---

# Tabela `teksty`

Tabela przechowująca gotowe teksty i szablony.

## Pola

| Pole           | Typ          |
| -------------- | ------------ |
| `id`           | int          |
| `tytul`        | varchar(255) |
| `tresc`        | text         |
| `data_dodania` | timestamp    |
| `struktura_id` | int          |

---

## Relacje

| Pole           | Relacja          |
| -------------- | ---------------- |
| `struktura_id` | → `struktury.id` |

---

# Tabela `struktury`

Tabela organizująca strukturę katalogów i kategorii tekstów.

## Pola

| Pole        | Typ          |
| ----------- | ------------ |
| `id`        | int          |
| `nazwa`     | varchar(255) |
| `rodzic_id` | int          |
| `typ`       | enum         |

---

## Typy struktur

```sql
'catalogue'
'text/plain'
```

---

## Zastosowanie

Tabela wykorzystywana jest do:

- budowy hierarchii katalogów,
- organizacji tekstów,
- tworzenia struktury podobnej do eksploratora plików.

Pole `rodzic_id` umożliwia tworzenie struktur zagnieżdżonych.

---

# Relacje w bazie danych

## Zamówienia ↔ Klienci

Jedno zamówienie należy do jednego klienta:

```text
zamowienia.klient_id → klienci.id
```

Jeden klient może posiadać wiele zamówień.

---

## Zamówienia ↔ Typy zamówień

Każde zamówienie posiada przypisany typ:

```text
zamowienia.typ_id → typy_zamowien.id
```

---

## Teksty ↔ Struktury

Teksty przypisywane są do struktury katalogów:

```text
teksty.struktura_id → struktury.id
```

---

# Typy danych wykorzystywane w bazie

W projekcie wykorzystywane są między innymi:

| Typ         | Zastosowanie                 |
| ----------- | ---------------------------- |
| `INT`       | identyfikatory               |
| `VARCHAR`   | krótkie teksty               |
| `TEXT`      | dłuższe treści               |
| `LONGTEXT`  | dane JSON                    |
| `DATE`      | daty                         |
| `DATETIME`  | data i godzina               |
| `TIMESTAMP` | automatyczne znaczniki czasu |
| `DECIMAL`   | kwoty                        |
| `ENUM`      | statusy i typy               |

---

# JSON w bazie danych

Część danych przechowywana jest jako JSON.

Dotyczy to między innymi:

- tagów,
- załączników,
- zdjęć.

Przykład:

```json
[
  {
    "title": "Projekt",
    "href": "https://example.com"
  }
]
```

Takie podejście upraszcza przechowywanie dynamicznych struktur danych bez konieczności tworzenia dodatkowych tabel relacyjnych.
