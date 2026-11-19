import { initDelight } from "./delight.js";

const root = document.documentElement;
root.classList.add("has-js");
const storage = { get: () => { try { return localStorage.getItem(storageKey); } catch { return null; } }, set: value => { try { if (value) localStorage.setItem(storageKey, value); else localStorage.removeItem(storageKey); } catch { /* Storage is optional. */ } } };
const menuToggle = document.querySelector(".menu-toggle");
const primaryNav = document.querySelector(".primary-navigation");
const themeToggle = document.querySelector(".theme-toggle");
const storageKey = "four-suitcases-theme";

function setTheme(theme) {
  if (!["light", "dark"].includes(theme)) {
    root.removeAttribute("data-theme");
    storage.set(null);
    if (themeToggle) themeToggle.setAttribute("aria-pressed", "false");
    return;
  }

  root.setAttribute("data-theme", theme);
  storage.set(theme);
  if (themeToggle) themeToggle.setAttribute("aria-pressed", theme === "dark" ? "true" : "false");
}

const systemTheme = matchMedia("(prefers-color-scheme: dark)");
const savedTheme = themeToggle ? storage.get() : null;

if (savedTheme) {
  setTheme(savedTheme);
} else if (window.foursuitcasesTheme?.themeMode && window.foursuitcasesTheme.themeMode !== "system") {
  setTheme(window.foursuitcasesTheme.themeMode);
}

if (menuToggle && primaryNav) {
  menuToggle.addEventListener("click", () => {
    const isOpen = menuToggle.getAttribute("aria-expanded") === "true";
    menuToggle.setAttribute("aria-expanded", String(!isOpen));
    primaryNav.classList.toggle("is-open", !isOpen);
    menuToggle.querySelector(".screen-reader-text").textContent = isOpen
      ? window.foursuitcasesTheme?.labels?.openMenu || "Open menu"
      : window.foursuitcasesTheme?.labels?.closeMenu || "Close menu";
  });
}

if (themeToggle) {
  const syncThemeToggle = () => themeToggle.setAttribute("aria-pressed", String(root.dataset.theme ? root.dataset.theme === "dark" : systemTheme.matches));
  syncThemeToggle();
  systemTheme.addEventListener("change", syncThemeToggle);
  themeToggle.addEventListener("click", () => {
    const current = root.getAttribute("data-theme") || (matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light");
    setTheme(current === "dark" ? "light" : "dark");
  });
}

if (menuToggle && primaryNav) { document.addEventListener("keydown", event => { if (event.key === "Escape" && primaryNav.classList.contains("is-open")) { primaryNav.classList.remove("is-open"); menuToggle.setAttribute("aria-expanded", "false"); menuToggle.querySelector(".screen-reader-text").textContent=window.foursuitcasesTheme?.labels?.openMenu||"Open menu"; menuToggle.focus(); } }); }

if(menuToggle&&primaryNav){const breakpoint=matchMedia("(min-width: 73.751rem)");breakpoint.addEventListener("change",()=>{primaryNav.classList.remove("is-open");menuToggle.setAttribute("aria-expanded","false");menuToggle.querySelector(".screen-reader-text").textContent=window.foursuitcasesTheme?.labels?.openMenu||"Open menu";});}

initDelight();
