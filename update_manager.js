let localVersion = "xXx";
let remoteVersion = "xXx";

async function getRemoteVersion() {
  try {
    const res = await fetch(
      "https://raw.githubusercontent.com/SpacingKosmopan/NowySystemZamowien/main/latest_v.txt",
    );

    if (!res.ok) throw new Error(res.status);

    return await res.text();
  } catch (err) {
    console.error("Błąd:", err);
  }
}

async function getLocalVersion() {
  try {
    const res = await fetch("local_v.txt");

    if (!res.ok) throw new Error(res.status);

    return await res.text();
  } catch (err) {
    console.error("Błąd:", err);
  }
}

async function checkUpdate() {
  const local_v = await getLocalVersion();
  localVersion = local_v;
  const remote_v = await getRemoteVersion();
  remoteVersion = remote_v;

  return local_v.trim() !== remote_v.trim();
}

checkUpdate().then((hasUpdate) => {
  if (hasUpdate) {
    document.querySelector("#upd-text").innerText =
      `Dostępna jest aktualizacja! Nowa wersja: ${remoteVersion}, obecnie zainstalowana wersja: ${localVersion}`;
  }
});
