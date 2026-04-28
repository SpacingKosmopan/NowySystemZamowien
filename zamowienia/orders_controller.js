function loadOrdersController() {
  $("#add-order").on("click", function () {
    $("#new-order-overlay").removeClass("hidden");
    loadOrderTypes();
    setCreationDateFromNow();
  });

  $("#btn-cancel").on("click", function () {
    $("#new-order-form")[0].reset();
    $("#new-order-overlay").addClass("hidden");
    editingOrderId = null;
  });

  $("#btn-save").on("click", function (e) {
    e.preventDefault();

    const payload = {
      client_id: parseInt($("#client-id").val()),
      opis: $("#order-desc").val(),
      kwota: parseFloat($("#order-price").val()),
      termin: $("#order-date").val(),
      typ_id: parseInt($("#order-type").val()),
    };

    console.log(payload);

    const url = editingOrderId ? "api/update_order.php" : "api/add_order.php";

    if (editingOrderId) payload.id = editingOrderId;

    fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    }).then(() => location.reload());
  });
}
let editingOrderId = null;
