// === LOGOWANIE ===

init();

async function init() {
  const res = await fetch("../logowanie/api/auth.php", {
    credentials: "include",
  });

  //alert(res.status);

  if (res.status === 401 || res.status === 403) {
    alert("Brak dostępu. Należy się zalogować");
    return;
  }

  loadData();
}

/////

function loadData() {
  const monthYear = document.getElementById("month-year");
  const calendarContainer = document.getElementById("calendar-container");
  const calendarGrid = calendarContainer.querySelector(".calendar-grid");
  const prevBtn = document.getElementById("prev-month");
  const nextBtn = document.getElementById("next-month");
  const viewSelect = document.getElementById("view-type");

  let today = new Date();
  let currentMonth = today.getMonth();
  let currentYear = today.getFullYear();
  let selectedDay = today.getDate();

  let eventsData = {};

  fetch("fetch_orders.php")
    .then((response) => response.json())
    .then((data) => {
      eventsData = data;
      renderCalendarView();
    })
    .catch((err) => console.error("Błąd pobierania zamówień:", err));

  let currentDate = new Date(today);

  function renderCalendarView() {
    const view = viewSelect.value;
    if (view === "m") {
      renderMonthView(currentDate.getMonth(), currentDate.getFullYear());
    } else if (view === "w") {
      renderWeekView(currentDate);
    } else if (view === "d") {
      renderDayView(currentDate);
    }
  }

  // ======================
  // Wyświetlanie wydarzeń
  // ======================
  function displayEventsForDay(dayDiv, dateKey) {
    const eventsContainer = document.createElement("div");
    eventsContainer.classList.add("events");

    if (eventsData[dateKey]) {
      eventsData[dateKey].forEach((event) => {
        const eventDiv = document.createElement("div");
        eventDiv.classList.add("event");
        //console.log("Rendering event:", event);

        const todayStr = new Date().toISOString().slice(0, 10);
        //console.log(todayStr);
        if (
          event.data < todayStr &&
          event.status !== "anulowane" &&
          event.status !== "zrealizowane"
        ) {
          eventDiv.classList.add("zalegle-event");
        } else {
          eventDiv.classList.add(
            event.status === "w realizacji"
              ? "w-realizacji-event"
              : event.status + "-event",
          );
        }
        eventDiv.setAttribute("data-id", event.id);

        // Typ
        const titleEl = document.createElement("div");
        titleEl.classList.add("event-title");
        titleEl.textContent = event.tytul;

        // Klient
        const clientEl = document.createElement("div");
        clientEl.classList.add("event-client");
        clientEl.textContent = event.klient;

        // Tytuł
        const orderTitleEl = document.createElement("div");
        orderTitleEl.classList.add("event-order-title");
        orderTitleEl.textContent = event.tytul_zamowienia;

        eventDiv.appendChild(titleEl);
        eventDiv.appendChild(clientEl);
        eventDiv.appendChild(orderTitleEl);
        eventsContainer.appendChild(eventDiv);

        eventDiv.addEventListener("click", (e) => {
          e.stopPropagation();
          const orderId = event.id ?? event.ID ?? 0;
          if (!orderId) return alert("Błąd: brak ID zamówienia!");
          window.location.href = `../zamowienia/index.html?id=${orderId}&isViewOnly=true`;
        });
      });
    }

    dayDiv.appendChild(eventsContainer);
  }

  // ----------------------
  // Widok miesiąca
  // ----------------------
  function renderMonthView(month, year) {
    calendarGrid.innerHTML = "";
    calendarGrid.style.gridTemplateColumns = "repeat(7, 1fr)";

    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDay = (firstDay.getDay() + 6) % 7;

    monthYear.textContent = firstDay.toLocaleString("pl-PL", {
      month: "long",
      year: "numeric",
    });

    ["Pon", "Wt", "Śr", "Czw", "Pt", "Sob", "Niedz"].forEach((d) => {
      const dayName = document.createElement("div");
      dayName.classList.add("day-name");
      dayName.textContent = d;
      calendarGrid.appendChild(dayName);
    });

    for (let i = 0; i < startDay; i++) {
      const empty = document.createElement("div");
      empty.classList.add("day", "empty");
      calendarGrid.appendChild(empty);
    }

    for (let day = 1; day <= lastDay.getDate(); day++) {
      const dayDiv = document.createElement("div");
      dayDiv.classList.add("day");

      const dayNumber = document.createElement("div");
      dayNumber.classList.add("day-number");
      dayNumber.textContent = day;
      dayDiv.appendChild(dayNumber);

      const dateKey = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
      displayEventsForDay(dayDiv, dateKey);

      if (
        day === today.getDate() &&
        month === today.getMonth() &&
        year === today.getFullYear()
      ) {
        dayDiv.classList.add("today");
      }

      dayDiv.addEventListener("click", () => {
        currentDate = new Date(year, month, day);
        if (viewSelect.value !== "m") renderCalendarView();
      });

      calendarGrid.appendChild(dayDiv);
    }
  }

  // ----------------------
  // Widok tygodnia
  // ----------------------
  function renderWeekView(date) {
    calendarGrid.innerHTML = "";
    calendarGrid.style.gridTemplateColumns = "repeat(7, 1fr)";

    const dayOfWeek = (date.getDay() + 6) % 7; // Pon=0
    const weekStart = new Date(date);
    weekStart.setDate(date.getDate() - dayOfWeek);

    monthYear.textContent = `Tydzień ${weekStart.toLocaleDateString("pl-PL")}`;

    ["Pon", "Wt", "Śr", "Czw", "Pt", "Sob", "Niedz"].forEach((d) => {
      const dayName = document.createElement("div");
      dayName.classList.add("day-name");
      dayName.textContent = d;
      calendarGrid.appendChild(dayName);
    });

    for (let i = 0; i < 7; i++) {
      const d = new Date(weekStart);
      d.setDate(weekStart.getDate() + i);

      const dayDiv = document.createElement("div");
      dayDiv.classList.add("day");

      const dayNumber = document.createElement("div");
      dayNumber.classList.add("day-number");
      dayNumber.textContent = d.getDate();
      dayDiv.appendChild(dayNumber);

      const dateKey = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;
      displayEventsForDay(dayDiv, dateKey);

      if (d.toDateString() === today.toDateString())
        dayDiv.classList.add("today");

      dayDiv.addEventListener("click", () => {
        currentDate = new Date(d);
        if (viewSelect.value !== "m") renderCalendarView();
      });

      calendarGrid.appendChild(dayDiv);
    }
  }

  // ----------------------
  // Widok dnia
  // ----------------------
  function renderDayView(date) {
    calendarGrid.innerHTML = "";
    calendarGrid.style.gridTemplateColumns = "1fr";

    monthYear.textContent = date.toLocaleDateString("pl-PL", {
      weekday: "long",
      day: "numeric",
      month: "long",
      year: "numeric",
    });

    const dateKey = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, "0")}-${String(date.getDate()).padStart(2, "0")}`;

    const dayDiv = document.createElement("div");
    dayDiv.classList.add("day");

    displayEventsForDay(dayDiv, dateKey);

    if (date.toDateString() === today.toDateString())
      dayDiv.classList.add("today");

    calendarGrid.appendChild(dayDiv);
  }

  // --- Nawigacja ---
  prevBtn.addEventListener("click", () => {
    const view = viewSelect.value;
    if (view === "m") {
      currentDate.setMonth(currentDate.getMonth() - 1);
    } else if (view === "w") {
      currentDate.setDate(currentDate.getDate() - 7);
    } else if (view === "d") {
      currentDate.setDate(currentDate.getDate() - 1);
    }
    renderCalendarView();
  });

  nextBtn.addEventListener("click", () => {
    const view = viewSelect.value;
    if (view === "m") {
      currentDate.setMonth(currentDate.getMonth() + 1);
    } else if (view === "w") {
      currentDate.setDate(currentDate.getDate() + 7);
    } else if (view === "d") {
      currentDate.setDate(currentDate.getDate() + 1);
    }
    renderCalendarView();
  });

  // Zmiana widoku
  viewSelect.addEventListener("change", renderCalendarView);

  // Pierwsze renderowanie
  renderCalendarView();
}
