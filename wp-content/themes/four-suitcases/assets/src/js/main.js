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
  let activeSubmenu;
  const menuLabel = menuToggle.querySelector(".screen-reader-text");

  function closeSubmenu(restoreFocus = false) {
    if (!activeSubmenu) return;
    const { button, list } = activeSubmenu;
    button.setAttribute("aria-expanded", "false");
    list.hidden = true;
    activeSubmenu = undefined;
    if (restoreFocus) button.focus();
  }

  function setMenuOpen(open) {
    menuToggle.setAttribute("aria-expanded", String(open));
    primaryNav.classList.toggle("is-open", open);
    menuLabel.textContent = open
      ? window.foursuitcasesTheme?.labels?.closeMenu || "Close menu"
      : window.foursuitcasesTheme?.labels?.openMenu || "Open menu";
    if (!open) closeSubmenu();
  }

  primaryNav.querySelectorAll("#primary-menu > li").forEach((item, index) => {
    const link = item.querySelector(":scope > a");
    const list = item.querySelector(":scope > .sub-menu");
    if (!link || !list) return;

    list.id ||= `primary-submenu-${index + 1}`;
    list.hidden = true;
    const button = document.createElement("button");
    button.type = "button";
    button.className = "primary-submenu-toggle";
    button.setAttribute("aria-label", primaryNav.dataset.submenuLabel.replace("%s", () => link.textContent.trim()));
    button.setAttribute("aria-controls", list.id);
    button.setAttribute("aria-expanded", "false");
    link.after(button);

    button.addEventListener("click", () => {
      const wasOpen = activeSubmenu?.button === button;
      closeSubmenu();
      if (wasOpen) return;
      activeSubmenu = { button, list };
      button.setAttribute("aria-expanded", "true");
      list.hidden = false;
    });
  });

  menuToggle.addEventListener("click", () => setMenuOpen(menuToggle.getAttribute("aria-expanded") !== "true"));
  document.addEventListener("click", event => {
    if (activeSubmenu && !activeSubmenu.button.parentElement.contains(event.target)) closeSubmenu();
  });
  primaryNav.addEventListener("focusout", event => {
    if (event.relatedTarget && activeSubmenu && !activeSubmenu.button.parentElement.contains(event.relatedTarget)) closeSubmenu();
  });
  document.addEventListener("keydown", event => {
    if (event.key !== "Escape" || event.defaultPrevented) return;
    if (activeSubmenu) {
      event.preventDefault();
      closeSubmenu(true);
    } else if (primaryNav.classList.contains("is-open")) {
      event.preventDefault();
      setMenuOpen(false);
      menuToggle.focus();
    }
  });
  matchMedia("(min-width: 73.751rem)").addEventListener("change", () => setMenuOpen(false));
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

initDelight();
