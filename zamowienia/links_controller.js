$("#links-showbox").hide();

function loadLinksController() {
  $("#add-link").on("click", function (e) {
    e.preventDefault();

    editingIndex = null;

    $("#link-title-input").val("");
    $("#link-href-input").val("");

    $("#links-showbox").show();
  });

  // links showbox save and close
  $("#links-showbox-save-close-button").on("click", function (e) {
    e.preventDefault();

    const title = $("#link-title-input").val();
    const href = $("#link-href-input").val();

    if (!title || !href) return;

    const linkData = { title, href };

    if (editingIndex === null) {
      links.push(linkData);
    } else {
      links[editingIndex] = linkData;
    }

    renderLinks();
    $("#links-showbox").hide();
  });

  $("#links-showbox-close-button").on("click", function (e) {
    e.preventDefault();
    $("#links-showbox").hide();
  });

  $("#links").on("click", ".link-title", function (e) {
    e.preventDefault();

    const index = $(this).data("index");
    const link = links[index];

    editingIndex = index;

    $("#link-title-input").val(link.title);
    $("#link-href-input").val(link.href);

    $("#links-showbox").show();
  });

  $("#links").on("click", ".delete-link", function () {
    const index = $(this).data("index");

    if (!confirm("Usunąć link?")) return;

    links.splice(index, 1);
    renderLinks();
  });
}

let links = [];
let editingIndex = null;

function renderLinks() {
  const list = $("#links");
  list.empty();

  links.forEach((link, index) => {
    const li = $(`
      <li>
        <a href="${link.href}" target="_blank" class="link-title" data-index="${index}">
          ${link.title}
        </a>
        <i class="bi bi-trash delete-link" data-index="${index}" style="cursor:pointer;margin-left:8px;"></i>
      </li>
      `);

    list.append(li);
  });
}
