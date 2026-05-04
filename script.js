/**
 * Funkcja przełączania klasy sidebar-closed
 */
function toggleDashboardNavbar() {
  document.body.classList.toggle("sidebar-closed");
}

// === STYLE ===
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

function applyTheme(data) {
  Object.entries(data).forEach(([key, value]) => {
    const cssVar = themeMap[key];

    if (!cssVar) return;

    document.documentElement.style.setProperty(cssVar, value);
  });
}

function loadTheme() {
  const raw = localStorage.getItem("theme-colors");
  if (!raw) return;

  const data = JSON.parse(raw);

  applyTheme(data);
}

loadTheme();
loadStyles();

function loadStyles() {
  const savedStyles = localStorage.getItem("saved-styles");

  if (!savedStyles) {
    console.warn("No saved styles");
    return;
  }

  const styles = JSON.parse(savedStyles);

  styles.forEach((element) => {
    document.documentElement.style.setProperty(
      element.propertyName,
      element.propertyValue,
    );
  });
}
