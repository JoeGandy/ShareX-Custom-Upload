window.__updateTheme = function () {
  function SetDark() {
    document.querySelector(
      'link[href*="toggle-bootstrap-dark-overlay.min.css"]'
    ).disabled = false;

    document.body.setAttribute("data-theme", "dark");

    // Update highlight JS themes
    try {
      document.querySelector("#highlightjs-dark-theme").disabled = false;
      document.querySelector("#highlightjs-light-theme").disabled = true;
    } catch {}
  }
  function SetLight() {
    document.querySelector(
      'link[href*="toggle-bootstrap-dark-overlay.min.css"]'
    ).disabled = true;

    document.body.setAttribute("data-theme", "light");

    // Update highlight JS themes
    try {
      document.querySelector("#highlightjs-dark-theme").disabled = true;
      document.querySelector("#highlightjs-light-theme").disabled = false;
    } catch {}
  }

  const userPrefersDark =
    window.matchMedia &&
    window.matchMedia("(prefers-color-scheme: dark)").matches;
  const darkModeSetting = localStorage.getItem("useDarkMode");

  switch (darkModeSetting) {
    case "true":
      SetDark();
      break;
    case "false":
      SetLight();
      break;
    default:
      if (userPrefersDark) SetDark();
      else SetLight();
      break;
  }

  document.body.style.display = null;
};

if (window.__darkCssLoaded === true) {
  window.__updateTheme();
}
