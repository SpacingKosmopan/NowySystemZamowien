# Generowanie dokumentacji

## JavaScript → JSDoc

Komentarze:

```js
/**
 * Dodaje dwie liczby.
 * @param {number} a
 * @param {number} b
 * @returns {number}
 */
function add(a, b) {
  return a + b;
}
```

### Co możesz z tym zrobić:

Możesz wygenerować dokumentację automatycznie narzędziami takimi jak:

- **JSDoc** (najbardziej klasyczne)
- **TypeDoc** (szczególnie przy TypeScript)
- wbudowana obsługa w IDE (VS Code, WebStorm)

### Przykład (JSDoc CLI):

```bash
npm install -g jsdoc
jsdoc script.js -d docs
```

Efekt:

- strona HTML z dokumentacją funkcji, parametrów, opisów itd.

# Generowanie dokumentacji JavaScript

### JavaScript → JSDoc

```bash
npx jsdoc ./ -r -d docs
// TO JEST KOMENDA DO GENEROWANIA
```

- `-r` = rekurencyjnie przez foldery
- `-d docs` = folder wyjściowy
