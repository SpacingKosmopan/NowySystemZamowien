function loadOrderTypesController() {}
let orderTypes = [];

// ---------- FORM SELECT ----------
function loadOrderTypes(selectedId = null) {
  if (orderTypes.length > 0) {
    renderOrderTypesForm(selectedId);
    return;
  }

  fetch("api/fetch_order_types.php")
    .then((res) => res.json())
    .then((data) => {
      orderTypes = data;
      renderOrderTypesForm(selectedId);
    });
}

function renderOrderTypesForm(selectedId = null) {
  const select = $("#order-type");
  select.html(`<option value="">-- wybierz --</option>`);

  orderTypes.forEach((t) => {
    const selected = selectedId == t.id ? "selected" : "";
    select.append(`
      <option value="${t.id}" ${selected}>
        ${t.tytul}
      </option>
    `);
  });
}

// ---------- FILTER SELECT ----------
function loadOrderTypesFilter() {
  fetch("api/fetch_order_types.php")
    .then((res) => res.json())
    .then((data) => renderOrderTypesFilter(data));
}

function renderOrderTypesFilter(data) {
  const select = $("#title-sort");

  select.html(`<option value="">-- wszystkie --</option>`);

  data.forEach((t) => {
    select.append(`
      <option value="${t.id}">
        ${t.tytul}
      </option>
    `);
  });
}
