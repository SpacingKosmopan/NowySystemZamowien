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

    $("#order-title-form .error-tooltip").addClass("hidden");
    $("#client-form .error-tooltip").addClass("hidden");
    $("#order-type-form .error-tooltip").addClass("hidden");
    $("#date-form .error-tooltip").addClass("hidden");
  });

  $("#btn-save").on("click", function (e) {
    e.preventDefault();

    if ($("#client-id").val() === "") {
      $("#client-form .error-tooltip").removeClass("hidden");
      return;
    } else {
      $("#client-form .error-tooltip").addClass("hidden");
    }

    if ($("#order-type").val() === "") {
      $("#order-type-form .error-tooltip").removeClass("hidden");
      return;
    } else {
      $("#order-type-form .error-tooltip").addClass("hidden");
    }

    if ($("#order-title").val() === "") {
      $("#order-title-form .error-tooltip").removeClass("hidden");
      return;
    } else {
      $("#order-title-form .error-tooltip").addClass("hidden");
    }

    if ($("#order-date").val() === "") {
      $("#date-form .error-tooltip").removeClass("hidden");
      return;
    } else {
      $("#date-form .error-tooltip").addClass("hidden");
    }

    const payload = {
      client_id: parseInt($("#client-id").val()),
      opis: $("#order-desc").val(),
      kwota: parseFloat($("#order-price").val()),
      termin: $("#order-date").val(),
      typ_id: parseInt($("#order-type").val()),
      tytul: $("#order-title").val(),
    };

    console.log(payload);

    const url = editingOrderId ? "api/update_order.php" : "api/add_order.php";

    if (editingOrderId) payload.id = editingOrderId;

    fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    }).then(() => {
      clearInputs();
      location.reload();
    });
  });
}
let editingOrderId = null;

function clearInputs() {
  document.querySelector("#selected-client").value = "";
  document.querySelector("#order-type").value = "";
  document.querySelector("#order-desc").value = "";
  document.querySelector("#order-price").value = 0.0;
  document.querySelector("#order-date").value = "";
  document.querySelector("#order-title").value = "";

  document.querySelector("#tags").innerHTML = "";
  document.querySelector("#links").innerHTML = "";
  document.querySelector("#photos-selected").innerHTML = "";

  $("#new-order-form")[0].reset();

  selectedTags = [];
  tempSelectedTags = [];
  selectedPhotoIds = [];
  links = [];

  setCreationDate();
}

function setCreationDate(date = new Date()) {
  const formatted = formatDatePL(date);
  $("#creation-date").val(formatted);
}
