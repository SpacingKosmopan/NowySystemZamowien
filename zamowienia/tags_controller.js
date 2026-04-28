$("#tags-showbox").hide();

function loadTagsController() {
  $("#edit-tags").on("click", openTagsModal); // checkboxy aktualizują tylko tempSelectedTags

  $(document).on("change", "#tags-list input[type='checkbox']", function () {
    handleTagsCheckboxSelect.call(this);
  });

  // zapisz zmiany do selectedTags
  $("#tags-showbox-save-close-button").on("click", saveAndCloseTagsModal);

  // anuluj – tylko zamykamy modal, nie zmieniamy selectedTags
  $("#tags-showbox-close-button").on("click", function (e) {
    e.preventDefault();
    $("#tags-showbox").hide();
  });
}
/** Lista tagów dla obecnie otwartego zamówienia */
let selectedTags = [];
/** Tymczasowa lista tagów, która zmienia się przy zaznaczaniu / odznaczaniu checkboxów obok tagów */
let tempSelectedTags = []; // tymczasowy stan dla modala

/**
 * Funkcja renderuje okienko z listą wszystkich tagów, które użytkownik może oznaczyć do danego zamówienia
 */
function renderTagsModal() {
  fetch("api/fetch_tags.php")
    .then((res) => res.json())
    .then((data) => {
      $("#tags-list").html("");
      tempSelectedTags = [...selectedTags];
      data.forEach((tag) => {
        const tagId = Number(tag.id);
        const checked = tempSelectedTags.includes(tagId) ? "checked" : "";
        $("#tags-list").append(`
          <div class="tags-list-checkable-element">
            <label>
              <input type="checkbox" value="${tagId}" ${checked}>
              ${tag.name}
            </label>
          </div>
        `);
      });
    });
}

/**
 * Handler do otwarcia okienka z tagami
 */
function openTagsModal(e) {
  e.preventDefault();
  $("#tags-showbox").show();

  renderTagsModal();
}

/**
 * Handler do aktualizacji checkboxów, przy zaznaczaniu tagów
 */
function handleTagsCheckboxSelect() {
  const tagId = Number($(this).val());
  if ($(this).is(":checked")) {
    if (!tempSelectedTags.includes(tagId)) tempSelectedTags.push(tagId);
  } else {
    tempSelectedTags = tempSelectedTags.filter((id) => id !== tagId);
  }
}

/**
 * Funkcja, która zapisuje wszelkie zmiany dokonane w modalu z tagami (tzn. odznaczenia, zaznaczenia)
 * @param {*} e event - wydarzenie przesłania formularza
 */
function saveAndCloseTagsModal(e) {
  e.preventDefault();
  selectedTags = [...tempSelectedTags]; // kopiujemy do trwałego stanu
  $("#tags").html("");

  selectedTags.forEach((tagId) => {
    const tagName = $(`#tags-list input[value="${tagId}"]`)
      .parent()
      .text()
      .trim();
    $("#tags").append(`<span class="tag">${tagName}</span>`);
  });

  $("#tags-showbox").hide();
}
