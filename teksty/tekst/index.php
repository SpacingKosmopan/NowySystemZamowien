<!doctype html>
<html lang="pl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- ICONS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="../../style.css" />
    <link rel="stylesheet" href="../teksty.css" />
    <!-- RUBIK FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <aside id="dashboard-navbar">
      <h1>
        <a href="../../index.html" id="home-link">
          <i class="bi bi-house-fill"></i> Dashboard</a
        >
      </h1>
      <hr class="separator" />
      <a class="dashboard-link" href="../../zamowienia/index.html"
        ><i class="bi bi-clipboard2-fill"></i> Zamówienia</a
      >
      <a class="dashboard-link" href="../../kalendarz/index.html"
        ><i class="bi bi-calendar-event-fill"></i> Kalendarz</a
      >
      <a class="dashboard-link" href="../../klienci/index.html"
        ><i class="bi bi-file-person-fill"></i> Klienci</a
      >
      <a class="dashboard-link" href="../../typy_tagi/index.html"
        ><i class="bi bi-info-circle-fill"></i> Typy zamówień | Tagi</a
      >
      <a class="dashboard-link" href="../index.html"
        ><i class="bi bi-chat-left-quote-fill"></i> Teksty</a
      >
      <a class="dashboard-link" href="../../galeria/index.html"
        ><i class="bi bi-image-fill"></i> Galeria</a
      >
      <i
        class="bi bi-chevron-double-left"
        id="dashboard-toggle-visibility"
        onclick="toggleDashboardNavbar()"
      ></i>
      <hr class="separator" />
    </aside>
    <button id="toggle-btn" onclick="toggleDashboardNavbar()">
      <i class="bi bi-chevron-double-right"></i>
    </button>

    <main>
      <header>
        <h1 id="page-name">
          Teksty
          <button id="add-text" class="footer-button">
            <i class="bi bi-file-plus-fill"></i> Nowy tekst
          </button>
        </h1>
        <nav class="subnav"></nav>
      </header>
      <hr class="horizontal-separator" />
      <section id="">
        <?php
        session_start();

        if (!isset($_SESSION['user_id'])) {
          http_response_code(401);
          echo json_encode(["error" => "NOT_LOGGED"]);
          exit;
        }

        $tekst_id = $_GET['id'] ?? null;

        if (!$tekst_id) {
            echo "<h1>Błąd: Brak ID tekstu</h1>";
        } else {
        require '../api/db.php';
    
        // Pobieramy dane tekstu oraz rodzica z tabeli struktury
        $stmt = $conn->prepare("
          SELECT t.id, t.tytul, t.tresc, t.struktura_id, s.rodzic_id 
          FROM teksty t 
          JOIN struktury s ON t.struktura_id = s.id 
          WHERE t.id = ?
          ");
        $stmt->bind_param("i", $tekst_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if (!$data) {
          echo "<h1>Błąd: Nie znaleziono tekstu</h1>";
        } else {
          // Renderowanie formularza
          echo "<h1>" . htmlspecialchars($data['tytul']) . "</h1>
            <p>
              <button id='text-save'>Zapisz</button>
              <button id='text-cancel'>Anuluj</button>
              <button id='text-delete'>Usuń</button>
            </p>
            <textarea id='text-content'>" . htmlspecialchars($data['tresc']) . "</textarea>
            <input type='hidden' id='tekst-id' value='{$data['id']}'>
            <input type='hidden' id='parent-id' value='{$data['rodzic_id']}'>
            <input type='hidden' id='struktura-id' value='{$data['struktura_id']}'>
            ";
          }
        }
      ?>

      </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../script.js"></script>
    <script>
      const textarea = document.getElementById("text-content");
      const saveBtn = document.getElementById("text-save");

      let originalValue = textarea.value;

      saveBtn.addEventListener("click", async () => {
        const textId = document.querySelector("#tekst-id").value;
        const data = {
          id: textId,
          tresc: textarea.value
        };

        try {
          const res = await fetch("../api/update_text.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
          });

          const result = await res.json();

          if (result.success) {
            alert("Zapisano");
            const parentId = document.querySelector("#parent-id").value;
            window.location.href = `../index.html?id-folderu=${parentId}`;
          } else {
            alert("Błąd zapisu");
          }
        } catch (err) {
          console.error(err);
          alert("Błąd połączenia");
        }
      });

      const cancelBtn = document.getElementById("text-cancel");

      cancelBtn.addEventListener("click", () => {
        if (textarea.value !== originalValue) {
          const confirmLeave = confirm("Masz niezapisane zmiany. Kontynuować?");

          if (!confirmLeave) return;
        }

        const parentId = document.querySelector("#parent-id").value;
        window.location.href = `../index.html?id-folderu=${parentId}`;
      });

      const deleteBtn = document.getElementById("text-delete");

      deleteBtn.addEventListener("click", async () => {
        if (!confirm("Na pewno chcesz usunąć ten tekst?")) return;

        const tekstId = document.querySelector("#tekst-id").value;
        const strukturaId = document.querySelector("#struktura-id").value; 

        try {
          const res = await fetch("../api/delete_text.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              tekst_id: tekstId,
              struktura_id: strukturaId
            })
          });

          const result = await res.json();

          if (result.success) {
            alert("Usunięto");
            const parentId = document.querySelector("#parent-id").value;
            window.location.href = `../index.html?id-folderu=${parentId}`;
          } else {
            alert("Błąd przy usuwaniu");
          }
        } catch (err) {
          console.error(err);
          alert("Błąd połączenia");
        }
      });
    </script>
  </body>
</html>
