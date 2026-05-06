/**
 * Funkcja przełączania klasy sidebar-closed
 */
function toggleDashboardNavbar() {
  document.body.classList.toggle("sidebar-closed");
}

const themeMap = {
  calNew: "--cal-new-event",
  calDone: "--cal-done",
  calInProgress: "--cal-in-progress",
  calCanceled: "--cal-canceled",
  calOverdue: "--cal-overdue",

  ordNew: "--ord-new",
  ordDone: "--ord-done",
  ordInProgress: "--ord-in-progress",
  ordCanceled: "--ord-canceled",
};

async function loadColorsFromFile() {
  const defaultColorsData = {
    calNew: "#ffe29a",
    calDone: "#98ff9d",
    calInProgress: "#95ceff",
    calCanceled: "#e4e4e4",
    calOverdue: "#ff8f8e",

    ordNew: "#ffe29a",
    ordDone: "#98ff9d",
    ordInProgress: "#95ceff",
    ordCanceled: "#ff8f8e",
  };

  const res = await fetch("/NowySystemZamowien/api/filesystem.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      action: "load",
      filename: "colors.json",
      content: JSON.stringify(defaultColorsData),
    }),
  });

  const data = await res.json();
  //console.log("script.js: ", data);
  if (!data.success) return;

  const parsed = JSON.parse(data.content);
  //console.log("Wczytano dane:", parsed);
  applyTheme(parsed);
}

function applyTheme(data) {
  //console.log("ApplyTheme: ", data);
  for (const [key, value] of Object.entries(data)) {
    const cssVar = themeMap[key];

    if (!cssVar) {
      console.warn("Brak mapowania dla:", key);
      continue;
    }

    document.documentElement.style.setProperty(cssVar, value);
  }
}

loadColorsFromFile();
