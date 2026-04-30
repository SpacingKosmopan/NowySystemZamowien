function loadOrders() {
  $(document).ready(function () {
    const tbody = $("#all-orders tbody");
    const statuses = ["anulowane", "nowe", "w realizacji", "zrealizowane"];

    loadOrderTypesFilter();
    loadMonthFilter();

    function normalizeStatus(s) {
      return s.toLowerCase().replace(/\s+/g, "-");
    }

    function renderTable(data) {
      tbody.empty();

      if (!data.length) {
        tbody.append(`<tr><td colspan="7">Brak zamówień</td></tr>`);
        return;
      }

      data.forEach((o) => {
        const tr = $(`<tr data-id="${o.id}">`);

        tr.append(`<td>${o.id}</td>`);
        tr.append(`<td>${o.imie} ${o.nazwisko}</td>`);
        tr.append(`<td>${o.typ}</td>`);
        tr.append(`<td>${o.tytul}</td>`);
        tr.append(`<td>${o.data_utworzenia}</td>`);
        tr.append(`<td>${o.termin_realizacji}</td>`);

        const select = $(`
        <select class="status-select" data-id="${o.id}">
          ${statuses
            .map(
              (s) => `
            <option value="${s}" ${s === o.status ? "selected" : ""}>
              ${s}
            </option>
          `,
            )
            .join("")}
        </select>
      `);

        select.addClass(normalizeStatus(o.status));

        tr.append($("<td>").append(select));

        tr.append(`
        <td class="actions">
          <button class="check-order">🔍</button>
          <button class="edit-btn">Edytuj</button>
          <button class="delete-btn">Usuń</button>
        </td>
      `);

        tbody.append(tr);
      });
    }

    function filterOrders() {
      const status = $("#status-sort").val()?.toLowerCase();
      const type = $("#title-sort").val();
      const client = $("#client-selected").val()?.toLowerCase();
      const month = $("#month-filter").val();

      const filtered = ordersData.filter((o) => {
        if (status && o.status.toLowerCase() !== status) return false;
        if (type && String(o.typ_id) !== String(type)) return false;

        const name = `${o.imie} ${o.nazwisko}`.toLowerCase();
        if (client && !name.includes(client)) return false;

        if (month) {
          const orderMonth = o.termin_realizacji?.slice(0, 7); // YYYY-MM
          if (orderMonth !== month) return false;
        }

        return true;
      });

      renderTable(filtered);
    }

    $("#status-sort, #title-sort, #client-selected").on(
      "input change",
      filterOrders,
    );

    $("#month-filter").on("change", filterOrders);

    // LOAD ORDERS
    $.getJSON("api/fetch_orders.php", (data) => {
      ordersData = data;
      renderTable(ordersData);

      handleDeepLink();
    });

    function handleDeepLink() {
      const params = new URLSearchParams(window.location.search);
      const orderId = params.get("id");

      if (!orderId) return;

      const order = ordersData.find((o) => o.id == orderId);
      if (!order) return;

      openOrder(order, false);
    }

    // DELETE
    tbody.on("click", ".delete-btn", function () {
      const id = $(this).closest("tr").data("id");

      const order = ordersData.find((o) => o.id == id);
      if (!order) return;

      const ok = confirm(
        `Czy na pewno usunąć zamówienie #${id}?\nTej operacji nie da się cofnąć.`,
      );

      if (!ok) return;

      $.post("api/delete_order.php", { id }, () => {
        ordersData = ordersData.filter((o) => o.id != id);
        renderTable(ordersData);
      });
    });

    // STATUS UPDATE
    tbody.on("change", ".status-select", function () {
      const id = $(this).data("id");
      const status = $(this).val();

      fetch("api/update_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id, status }),
      })
        .then((res) => {
          if (!res.ok) throw new Error("Update failed");
        })
        .catch(() => {
          alert("Nie udało się zmienić statusu");
          renderTable(ordersData); // rollback UI
        });

      const order = ordersData.find((o) => o.id == id);
      if (order) order.status = status;
      renderTable(ordersData);
    });

    tbody.on("click", ".check-order", function () {
      const id = $(this).closest("tr").data("id");

      const order = ordersData.find((o) => o.id == id);
      if (!order) return;

      // tryb tylko do odczytu
      openOrder(order, true);
    });

    tbody.on("click", ".edit-btn", function () {
      const id = $(this).closest("tr").data("id");

      const order = ordersData.find((o) => o.id == id);
      if (!order) return;

      openOrder(order, false);
    });

    $("#reset-sort").on("click", function () {
      $("#status-sort").val("");
      $("#title-sort").val("");
      $("#client-selected").val("");
      $("#month-filter").val("");

      renderTable(ordersData);
    });
  });
}
let ordersData = [];

function openOrder(order, readOnly = false) {
  editingOrderId = readOnly ? null : order.id;

  $("#client-id").val(order.client_id ?? order.klient_id ?? 0);
  $("#selected-client").val(order.imie + " " + order.nazwisko);
  $("#order-desc").val(order.opis);
  $("#order-price").val(order.kwota);
  $("#order-title").val(order.tytul);

  $("#order-date").val(order.termin_realizacji?.split(" ")[0]);
  $("#creation-date").val(order.data_utworzenia);

  loadOrderTypes(order.typ_id);

  $("#new-order-overlay").removeClass("hidden");

  setFormDisabled(readOnly);
}

function setCreationDateFromNow() {
  const now = new Date();
  $("#creation-date").val(formatDatePL(now));
}

const monthNames = [
  "Styczeń",
  "Luty",
  "Marzec",
  "Kwiecień",
  "Maj",
  "Czerwiec",
  "Lipiec",
  "Sierpień",
  "Wrzesień",
  "Październik",
  "Listopad",
  "Grudzień",
];

function loadMonthFilter() {
  const select = $("#month-filter");
  select.empty();

  for (let year = 2026; year <= 2040; year++) {
    for (let month = 1; month <= 12; month++) {
      const label = `${year}-${String(month).padStart(2, "0")}`;
      const text = `${monthNames[month - 1]} ${year}`;

      select.append(`<option value="${label}">${text}</option>`);
    }
  }
}
