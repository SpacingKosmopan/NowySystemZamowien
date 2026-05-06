function loadPhotosController() {
  // --- Otwarcie modala wyboru zdjęć ---
  document.getElementById("edit-photos").addEventListener("click", async () => {
    document.getElementById("photos-showbox").classList.remove("hidden");
    await loadPhotos();
  });

  // --- Zamknięcie modala (anuluj) ---
  document
    .getElementById("photos-showbox-close-button")
    .addEventListener("click", () => {
      document.getElementById("photos-showbox").classList.add("hidden");
    });

  // --- Zapis i zamknięcie modala ---
  document
    .getElementById("photos-showbox-save-close-button")
    .addEventListener("click", () => {
      renderSelectedPhotos();
      document.getElementById("photos-showbox").classList.add("hidden");
    });

  // --- Wyszukiwarka po nazwie zdjęcia ---
  document
    .getElementById("photos-search")
    ?.addEventListener("input", loadPhotos);
}

let selectedPhotoIds = [];
let allPhotos = [];

// --- Render miniatur w formularzu ---
async function renderSelectedPhotos() {
  await loadPhotos();
  const container = document.getElementById("photos-selected");
  container.innerHTML = "";

  selectedPhotoIds.forEach((id) => {
    const photo = allPhotos.find((p) => Number(p.id) === id);
    if (photo && photo.sciezka.match(/\.(jpg|jpeg|png)$/i)) {
      const link = document.createElement("a");
      link.href = "../galeria/uploads/" + photo.sciezka;
      link.target = "_blank";

      const img = document.createElement("img");
      img.src = "../galeria/uploads/" + photo.sciezka;
      img.width = 80;
      img.height = 80;
      img.style.margin = "5px";
      img.style.objectFit = "cover";
      img.style.borderRadius = "5px";

      link.appendChild(img);
      container.appendChild(link);
    } else {
      // fallback jeśli nie obrazek
      const span = document.createElement("span");
      span.innerText = id;
      container.appendChild(span);
    }
  });
}

// --- Ładowanie zdjęć z bazy i render w modal ---
async function loadPhotos() {
  const res = await fetch("../galeria/api/files.php");
  const photos = await res.json();
  allPhotos = photos; // zapisujemy globalnie

  const photosList = document.getElementById("photos-list");
  photosList.innerHTML = "";

  const searchTerm = document
    .getElementById("photos-search")
    ?.value?.toLowerCase();

  photos
    .filter(
      (photo) =>
        !searchTerm || photo.nazwa_pliku.toLowerCase().includes(searchTerm),
    )
    .forEach((photo) => {
      const div = document.createElement("div");
      div.classList.add("photo-item");

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.value = photo.id;
      checkbox.checked = selectedPhotoIds.includes(Number(photo.id));
      checkbox.addEventListener("change", () => {
        const pid = Number(photo.id);
        if (checkbox.checked) {
          if (!selectedPhotoIds.includes(pid)) selectedPhotoIds.push(pid);
        } else {
          selectedPhotoIds = selectedPhotoIds.filter((x) => x !== pid);
        }
      });

      const label = document.createElement("label");
      const fileName = document.createElement("span");
      fileName.classList.add("photo-name");
      fileName.textContent = photo.nazwa_pliku;
      label.appendChild(checkbox);
      label.appendChild(fileName);

      // miniatura dla jpg/png
      if (photo.sciezka.match(/\.(jpg|jpeg|png)$/i)) {
        const img = document.createElement("img");
        img.src = "../galeria/uploads/" + photo.sciezka;
        img.style.borderRadius = "5px";
        img.style.objectFit = "cover";
        label.appendChild(img);
      }

      div.appendChild(label);
      photosList.appendChild(div);
    });
}

// --- Ustawienie zaznaczonych zdjęć przy edycji zamówienia ---
function setSelectedPhotos(photoIds) {
  selectedPhotoIds = photoIds.map(Number);
  renderSelectedPhotos();
}
