function loadClientsController() {
  // ---------- OTWIERANIE MODALA ----------
  $(document).on("click", "#select-client, .select-client", function (e) {
    e.preventDefault();
    $("#client-modal").show();

    $("#client-search").val(""); // reset wyszukiwania
    loadClients();
  });

  // ---------- WYBÓR KLIENTA ----------
  $("#clients-table").on("click", ".select-client-btn", function () {
    const id = $(this).data("id");
    const name = $(this).data("name");

    $("#client-id").val(id);
    $("#selected-client").val(name);

    // jeśli chcesz filtrować tabelę zamówień → zostaw
    $("#client-selected").val(name).trigger("input");

    $("#client-modal").hide();
  });

  // ---------- WYSZUKIWANIE ----------
  $("#client-search").on("input", function () {
    const value = $(this).val().toLowerCase().trim();

    const filtered = clients.filter((c) =>
      `${c.imie} ${c.nazwisko}`.toLowerCase().includes(value),
    );

    renderClients(filtered);
  });

  // ---------- ZAMYKANIE MODALA ----------
  $("#client-modal-close").on("click", function () {
    $("#client-modal").hide();

    // reset wyszukiwania
    $("#client-search").val("");
    renderClients(clients);
  });

  // ---------- WYCZYŚĆ KLIENTA ----------
  $("#clear-client").on("click", function () {
    $("#client-id").val("");
    $("#selected-client").val("");

    $("#client-selected").val("").trigger("input");

    $("#client-modal").hide();
  });
}

// ---------- DANE ----------
let clients = [];
let clientsLoaded = false;

// ---------- ŁADOWANIE ----------
/**
 * Funkcja, która wczytuje wszystkich klientów z bazy danych przy pomocy `api/fetch_clients.php`
 * @returns
 */
function loadClients() {
  if (clientsLoaded) {
    renderClients(clients);
    return;
  }

  fetch("api/fetch_clients.php")
    .then((res) => res.json())
    .then((data) => {
      clients = data;
      clientsLoaded = true;
      renderClients(clients);
    })
    .catch((err) => {
      console.error("Błąd ładowania klientów:", err);
    });
}

// ---------- RENDER ----------
/**
 * Funkcja, która generuje tabelę z klientami
 * @param {*} data lista klientów w formacie arraya
 * @returns
 */
function renderClients(data) {
  const tbody = $("#clients-table tbody");
  tbody.html("");

  if (data.length === 0) {
    tbody.append(`
      <tr>
        <td colspan="3" style="text-align:center;">
          Brak wyników
        </td>
      </tr>
    `);
    return;
  }

  data.forEach((client) => {
    tbody.append(`
      <tr>
        <td>${client.id}</td>
        <td>${client.imie} ${client.nazwisko}</td>
        <td>
          <button class="select-client-btn"
            data-id="${client.id}"
            data-name="${client.imie} ${client.nazwisko}">
            Wybierz
          </button>
        </td>
      </tr>
    `);
  });
}
