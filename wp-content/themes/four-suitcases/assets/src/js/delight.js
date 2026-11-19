import { createPassportStamp } from "./passport-stamp.js";

const clamp = (value, min, max) => Math.min(max, Math.max(min, value));
const reducedMotion = matchMedia("(prefers-reduced-motion: reduce)");
const canHover = matchMedia("(hover: hover) and (pointer: fine)");
const motionLevels = { calm: .45, lively: 1, max: 1.7 };
const protectedContent = "[data-travel-protect], [data-travel-keep-clear], h1, h2, h3, p, li > p, figcaption, summary, a, button, label, input, select, textarea, iframe, video, [contenteditable], [data-nostamp]";
const interactive = "a, button, summary, input, select, textarea, label, [contenteditable], [data-nostamp], [data-drag]";
const paletteNames = ["plum", "sea", "apricot", "sand", "sun", "coral", "sky", "mint"];
const storage = {
  read(key, fallback) {
    try { return JSON.parse(localStorage.getItem(key)) ?? fallback; } catch { return fallback; }
  },
  write(key, value) {
    try { localStorage.setItem(key, JSON.stringify(value)); } catch { /* Storage is optional. */ }
  },
};

function rect(left, top, width, height) {
  return { left, top, right: left + width, bottom: top + height };
}

function overlaps(a, b, gap = 0) {
  return a.left < b.right + gap && a.right > b.left - gap && a.top < b.bottom + gap && a.bottom > b.top - gap;
}

function safeAreas(scope, bounds, except, selector = protectedContent) {
  return Array.from(scope.querySelectorAll(selector)).flatMap(element => {
    if (element === except || except?.contains(element) || element.closest("[hidden], [inert]")) return [];
    if (element.closest("[aria-hidden='true']") && !element.hasAttribute("data-travel-keep-clear")) return [];
    if (getComputedStyle(element).visibility === "hidden") return [];
    return [...element.getClientRects()].filter(area => area.width && area.height && overlaps(area, bounds));
  });
}

function viewport() {
  const visual = window.visualViewport;
  const left = visual?.offsetLeft ?? 0;
  const top = visual?.offsetTop ?? 0;
  const width = Math.min(visual?.width ?? innerWidth, document.documentElement.clientWidth);
  const height = visual?.height ?? innerHeight;
  return { left, top, width, height, right: left + width, bottom: top + height };
}

function setupStamps() {
  const { enabled: siteEnabled, stamps = [] } = window.foursuitcasesTheme?.passport ?? {};
  const zones = new WeakMap();
  let enabled = storage.read("fsx-stamps", true) !== false;
  window.FSX = {
    setStamps(value) {
      enabled = Boolean(value);
      storage.write("fsx-stamps", enabled);
      if (!enabled) document.querySelectorAll(".travel-stamp-slot").forEach(stamp => stamp.remove());
    },
  };

  function stamp(zone, x, y) {
    if (!siteEnabled || !enabled || !stamps.length) return;
    const bounds = zone.getBoundingClientRect();
    const view = viewport();
    let layer = zones.get(zone);
    if (!layer) {
      layer = document.createElement("div");
      layer.className = "travel-stamp-layer";
      layer.setAttribute("aria-hidden", "true");
      zone.prepend(layer);
      zones.set(zone, layer);
    }

    const mark = createPassportStamp(stamps[Math.floor(Math.random() * stamps.length)]);
    mark.classList.add("travel-" + paletteNames[Math.floor(Math.random() * paletteNames.length)]);
    const slot = document.createElement("div");
    slot.className = "travel-stamp-slot";
    const degrees = Math.random() * 36 - 18;
    slot.style.rotate = degrees + "deg";
    slot.style.visibility = "hidden";
    slot.append(mark);
    layer.append(slot);

    // Reserve the actual rotated border box at the animation's largest size.
    const radians = Math.abs(degrees) * Math.PI / 180;
    const halfWidth = (mark.offsetWidth * Math.cos(radians) + mark.offsetHeight * Math.sin(radians)) / 2;
    const halfHeight = (mark.offsetHeight * Math.cos(radians) + mark.offsetWidth * Math.sin(radians)) / 2;
    const obstacles = safeAreas(document, bounds);
    const candidates = [[0, 0], [-96, 0], [96, 0], [0, -96], [0, 96]];
    let position;
    let entranceScale = 1;
    for (const scale of reducedMotion.matches ? [1] : [1.9, 1.5, 1.2, 1]) {
      const rx = Math.ceil(halfWidth * scale) + 2;
      const ry = Math.ceil(halfHeight * scale) + 2;
      const minX = Math.max(bounds.left, view.left) + rx;
      const maxX = Math.min(bounds.right, view.right) - rx;
      const minY = Math.max(bounds.top, view.top) + ry;
      const maxY = Math.min(bounds.bottom, view.bottom) - ry;
      if (minX > maxX || minY > maxY) continue;
      position = candidates.map(([dx, dy]) => [clamp(x + dx, minX, maxX), clamp(y + dy, minY, maxY)])
        .find(([cx, cy]) => !obstacles.some(area => overlaps(rect(cx - rx, cy - ry, rx * 2, ry * 2), area, 6)));
      if (position) {
        entranceScale = scale;
        break;
      }
    }
    if (!position) {
      slot.remove();
      return;
    }
    slot.style.left = (position[0] - bounds.left) + "px";
    slot.style.top = (position[1] - bounds.top) + "px";
    slot.style.removeProperty("visibility");
    if (layer.childElementCount > 14) layer.firstElementChild.remove();
    if (!reducedMotion.matches) {
      slot.animate([{ scale: entranceScale, opacity: .35 }, { scale: 1, opacity: 1 }], { duration: 420, easing: "cubic-bezier(.2,.8,.3,1)" });
    }
    setTimeout(() => {
      if (!slot.isConnected) return;
      if (reducedMotion.matches) slot.remove();
      else mark.animate([{ opacity: .55 }, { opacity: 0 }], { duration: 2400, fill: "forwards" }).finished.then(() => slot.remove(), () => {});
    }, 14000);
  }

  document.addEventListener("click", event => {
    if (!(event.target instanceof Element) || event.defaultPrevented || event.detail === 0 || event.target.closest(interactive)) return;
    const zone = event.target.closest("[data-stamps]");
    if (zone && !event.target.closest("[data-travel-protect]")) stamp(zone, event.clientX, event.clientY);
  });
  return stamp;
}

function setupCompass(stamp) {
  const host = document.querySelector("[data-hunt-status]");
  if (!host) return;
  const allButtons = [...document.querySelectorAll("[data-compass]")];
  const footer = allButtons.find(button => button.classList.contains("travel-compass--footer"));
  const buttons = allButtons.filter(button => button !== footer).slice(0, footer ? 3 : 4);
  if (footer) buttons.push(footer);
  if (!buttons.length) return;
  const key = `fsx-compass:${location.pathname.replace(/\/$/, "") || "/"}`;
  const stored = storage.read(key, []);
  const found = new Set(Array.isArray(stored) ? stored : []);
  const status = host.querySelector("[data-hunt-count]");
  const reset = host.querySelector("[data-hunt-reset]");
  function congratulate() {
    const message = "You found every compass rose. Official family navigator!";
    status.append(" · " + message);
    const toast = document.createElement("div");
    toast.className = "travel-compass-toast";
    toast.setAttribute("aria-hidden", "true");
    toast.textContent = message;
    document.body.append(toast);
    const view = viewport();
    const bounds = toast.getBoundingClientRect();
    const blocked = safeAreas(document, view, toast).some(area => overlaps(area, bounds, 5));
    if (blocked) toast.remove();
    else setTimeout(() => toast.remove(), 4200);
  }
  function update() {
    buttons.forEach((button, index) => {
      button.classList.toggle("is-found", found.has(index));
      button.setAttribute("aria-pressed", String(found.has(index)));
      button.setAttribute("aria-label", found.has(index) ? "Compass rose collected" : "Collect compass rose");
    });
    status.textContent = `${buttons.filter((_, index) => found.has(index)).length}/${buttons.length} compass roses found`;
    reset.hidden = !buttons.every((_, index) => found.has(index));
  }
  buttons.forEach((button, index) => {
    button.hidden = false;
    button.addEventListener("click", () => {
      if (found.has(index)) return;
      found.add(index);
      storage.write(key, [...found]);
      if (!reducedMotion.matches) button.querySelector("img").animate([{ rotate: "0deg" }, { rotate: "720deg" }], { duration: 720, easing: "ease-out" });
      update();
      if (!reset.hidden) {
        congratulate();
        const zone = button.closest("[data-stamps]");
        const bounds = zone?.getBoundingClientRect();
        if (bounds) for (const fraction of [.23, .5, .77]) stamp(zone, bounds.left + bounds.width * fraction, bounds.top + bounds.height * .5);
      }
    });
  });
  reset.addEventListener("click", () => {
    found.clear();
    storage.write(key, []);
    host.focus();
    update();
  });
  host.hidden = false;
  update();
}

function setupSuitcases() {
  const suitcases = [...document.querySelectorAll("[data-pop]")];
  if (!suitcases.length) return;
  let active;
  let pendingFrame;
  let initiallyOpening = false;
  let closing;
  let openingAnimation;
  let settleTimer;

  function getSpread(button, itemCount, view) {
    const trigger = button.getBoundingClientRect();
    const margin = 13;
    const safeTop = Math.max(view.top + margin, document.querySelector(".site-header")?.getBoundingClientRect().bottom ?? 0) + 6;
    const playArea = { left: view.left + margin, top: safeTop, right: view.right - margin, bottom: view.bottom - margin };
    const x = clamp(trigger.left + trigger.width / 2, playArea.left, playArea.right);
    const obstacles = safeAreas(document, playArea, button);
    const nearLeft = x - playArea.left < Math.min(175, view.width * .36);
    const nearRight = playArea.right - x < Math.min(175, view.width * .36);
    const direction = nearRight ? Math.PI : nearLeft ? 0 : -Math.PI / 2;
    const directions = [0, .22, -.22, .48, -.48, .8, -.8, 1.12, -1.12].map(offset => direction + offset);
    const preferredSize = Math.round(clamp(trigger.width * .35, 46, 110));

    function clearPath(anchor, end, extent) {
      const distance = Math.hypot(end.x - anchor.x, end.y - anchor.y);
      const samples = Math.max(1, Math.ceil(distance / 9));
      for (let step = 1; step <= samples; step++) {
        const progress = step / samples;
        const flightSize = extent * (.2 + .8 * progress);
        const cx = anchor.x + (end.x - anchor.x) * progress;
        const cy = anchor.y + (end.y - anchor.y) * progress;
        if (obstacles.some(area => overlaps(area, rect(cx - flightSize, cy - flightSize, flightSize * 2, flightSize * 2), 5))) return false;
      }
      return true;
    }

    for (const size of [preferredSize, Math.round(preferredSize * .8), Math.round(preferredSize * .65), 32]) {
      const extent = size * .68;
      const radius = clamp(trigger.width * .68, size * 2, Math.min(view.width, view.height) * .43);
      for (const angle of directions) {
        for (const height of [.47, .63, .33, .77, .88, .96]) {
          const anchor = { x, y: clamp(trigger.top + trigger.height * height, playArea.top, playArea.bottom) };
          for (const cone of [1.82, 1.38, 2.2]) {
            const positions = [];
            for (let index = 0; index < itemCount; index++) {
              const offset = itemCount === 1 ? 0 : (index / (itemCount - 1) - .5) * cone;
              const theta = angle + offset;
              const distances = index % 2 ? [.79, 1.04, .62, 1.28, 1.5] : [1, .8, 1.23, .62, 1.45];
              const point = distances.map(reach => ({
                x: anchor.x + Math.cos(theta) * radius * reach,
                y: anchor.y + Math.sin(theta) * radius * reach,
                size,
              })).find(end => {
                const space = rect(end.x - extent, end.y - extent, extent * 2, extent * 2);
                if (space.left < playArea.left || space.right > playArea.right || space.top < playArea.top || space.bottom > playArea.bottom) return false;
                if (positions.some(item => Math.hypot(end.x - item.x, end.y - item.y) < size * .84)) return false;
                if (obstacles.some(area => overlaps(area, space, 7))) return false;
                return clearPath(anchor, end, extent);
              });
              if (!point) break;
              positions.push(point);
            }
            if (positions.length === itemCount) return { anchor, positions };
          }
        }
      }
    }
    return null;
  }

  function close({ animate = false } = {}) {
    if (!active) return;
    const { button, items, spread } = active;
    active = null;
    clearTimeout(settleTimer);
    button.setAttribute("aria-expanded", "false");
    button.setAttribute("aria-label", "Unpack suitcase");
    button.dataset.tag = "Psst. Unpack the suitcase.";
    if (animate && spread && !items[0].hidden && !reducedMotion.matches) {
      const { anchor, positions } = spread;
      closing = items;
      items.forEach((item, index) => {
        const { x, y } = positions[index];
        item.animate([
          { translate: "0 0", scale: 1, opacity: 1 },
          { translate: `${anchor.x - x}px ${anchor.y - y}px`, scale: .2, opacity: 0 },
        ], { duration: 390, delay: index * 38, easing: "ease-in", fill: "forwards" }).finished.then(() => item.remove(), () => {});
      });
    } else items.forEach(item => item.remove());
    document.querySelector(".travel-unpack-tray")?.remove();
  }

  function update(allowTray = false) {
    pendingFrame = undefined;
    if (!active) return;
    const { button, items } = active;
    const view = viewport();
    if (active.tray) return;
    if (!overlaps(button.getBoundingClientRect(), view)) {
      close();
      return;
    }
    const spread = getSpread(button, items.length, view);
    if (!spread) {
      if (!initiallyOpening && !allowTray) {
        items.forEach(item => { item.hidden = true; });
        return;
      }
      // When copy fills the viewport, give the fan its own space in the layout.
      const tray = document.createElement("div");
      tray.className = "travel-unpack-tray";
      tray.setAttribute("aria-hidden", "true");
      button.closest(".travel-hero-stage").after(tray);
      active.tray = tray;
      items.forEach((item, index) => {
        item.className = "travel-unpack-tray__item";
        item.hidden = false;
        tray.append(item);
        if (!reducedMotion.matches) item.animate([{ scale: .2, translate: "0 2rem", opacity: 0 }, { scale: 1, translate: "0 0", opacity: 1 }], { duration: 520, delay: index * 70, fill: "backwards", easing: "cubic-bezier(.2,.8,.25,1)" });
      });
      tray.scrollIntoView({ block: "nearest", behavior: reducedMotion.matches ? "instant" : "smooth" });
      return;
    }
    active.spread = spread;
    const { anchor, positions } = spread;
    items.forEach((item, index) => {
      const { x, y, size } = positions[index];
      item.hidden = false;
      item.style.left = `${x - size / 2}px`;
      item.style.top = `${y - size / 2}px`;
      item.style.width = item.style.height = `${size}px`;
      item.style.rotate = `${(index - (items.length - 1) / 2) * 11}deg`;
      if (!initiallyOpening) item.getAnimations().forEach(animation => animation.cancel());
      if (initiallyOpening && !reducedMotion.matches) {
        item.animate([
          { translate: `${anchor.x - x}px ${anchor.y - y}px`, scale: .2, opacity: 0 },
          { translate: "0 0", scale: 1, opacity: 1 },
        ], { duration: 610, delay: index * 70, easing: "cubic-bezier(.18,.68,.32,1)", fill: "backwards" });
      }
    });
  }

  function schedule() {
    if (closing) {
      closing.forEach(item => item.remove());
      closing = undefined;
    }
    if (!active || active.tray) return;
    if (pendingFrame === undefined) pendingFrame = requestAnimationFrame(() => update());
    clearTimeout(settleTimer);
    settleTimer = setTimeout(() => {
      if (active?.items[0].hidden) update(true);
    }, 160);
  }

  suitcases.forEach(button => {
    button.hidden = false;
    button.addEventListener("click", () => {
      if (active?.button === button) {
        close({ animate: true });
        return;
      }
      close();
      const items = button.dataset.pop.split(",").filter(Boolean).map(src => {
        const item = new Image();
        item.src = src;
        item.alt = "";
        item.className = "travel-pop-item";
        item.setAttribute("aria-hidden", "true");
        return item;
      });
      if (!items.length) return;
      active = { button, items };
      button.setAttribute("aria-expanded", "true");
      button.setAttribute("aria-label", "Repack suitcase");
      button.dataset.tag = "Repack the suitcase";
      initiallyOpening = true;
      document.body.append(...items);
      update();
      initiallyOpening = false;
      openingAnimation?.cancel();
      if (!reducedMotion.matches) openingAnimation = button.animate([{ scale: 1 }, { scale: "1.05 .92", offset: .22 }, { scale: ".96 1.06", offset: .49 }, { scale: 1 }], { duration: 650, easing: "ease-out" });
    });
  });
  window.addEventListener("scroll", schedule, { passive: true });
  window.addEventListener("resize", schedule, { passive: true });
  window.visualViewport?.addEventListener("resize", schedule, { passive: true });
  window.visualViewport?.addEventListener("scroll", schedule, { passive: true });
  document.addEventListener("toggle", schedule, true);
  document.addEventListener("keydown", event => {
    if (event.key === "Escape" && active) {
      active.button.focus({ preventScroll: true });
      close();
    }
  });
}

function setupPostcards() {
  document.querySelectorAll("[data-flip]").forEach(card => {
    const button = card.querySelector(".travel-flip-toggle");
    const link = card.querySelector(".travel-cover-link");
    let pinned = false;
    let hovered = false;
    function update() {
      const flipped = pinned || hovered || link?.matches(":focus-visible");
      card.classList.toggle("is-flipped", Boolean(flipped));
      button.setAttribute("aria-expanded", String(Boolean(flipped)));
    }
    button.hidden = false;
    card.classList.add("is-flippable");
    card.addEventListener("pointerenter", event => {
      if (event.pointerType === "mouse" && canHover.matches) {
        hovered = true;
        update();
      }
    });
    card.addEventListener("pointerleave", () => {
      hovered = false;
      update();
    });
    card.addEventListener("focusin", update);
    card.addEventListener("focusout", () => requestAnimationFrame(update));
    button.addEventListener("click", () => {
      pinned = !pinned && !hovered;
      hovered = false;
      update();
    });
  });
}

function setupDrag() {
  let topLayer = 2;
  document.querySelectorAll("[data-drag]").forEach(item => {
    let drag;
    item.addEventListener("dragstart", event => event.preventDefault());
    item.addEventListener("pointerdown", event => {
      if (event.button !== 0 || event.target.closest("a,button")) return;
      const [x = 0, y = 0] = item.style.translate.split(" ").map(value => parseFloat(value) || 0);
      drag = { id: event.pointerId, x, y, startX: event.clientX, startY: event.clientY };
      item.setPointerCapture(event.pointerId);
    });
    item.addEventListener("pointermove", event => {
      if (!drag || event.pointerId !== drag.id) return;
      const dx = event.clientX - drag.startX;
      const dy = event.clientY - drag.startY;
      if (Math.hypot(dx, dy) < 7 && !item.classList.contains("is-dragging")) return;
      const region = item.parentElement.getBoundingClientRect();
      const bounds = item.getBoundingClientRect();
      const [x = 0, y = 0] = item.style.translate.split(" ").map(value => parseFloat(value) || 0);
      if (!item.classList.contains("is-dragging")) item.style.zIndex = String(++topLayer);
      item.classList.add("is-dragging");
      const nextX = clamp(drag.x + dx, x + region.left - bounds.left, Math.max(x + region.left - bounds.left, x + region.right - bounds.right));
      const nextY = clamp(drag.y + dy, y + region.top - bounds.top, Math.max(y + region.top - bounds.top, y + region.bottom - bounds.bottom));
      const destination = rect(bounds.left + nextX - x, bounds.top + nextY - y, bounds.width, bounds.height);
      const otherCopy = [...item.parentElement.querySelectorAll("blockquote, figcaption")]
        .filter(copy => !item.contains(copy));
      if (otherCopy.some(copy => overlaps(destination, copy.getBoundingClientRect(), 3))) return;
      item.style.translate = `${nextX}px ${nextY}px`;
    });
    item.addEventListener("lostpointercapture", () => {
      drag = undefined;
      item.classList.remove("is-dragging");
    });
  });
}

function setupTestimonialCarousels() {
  document.querySelectorAll("[data-spotlight]").forEach(carousel => {
    const quotes = [...carousel.querySelectorAll("[data-quote]")];
    const controls = carousel.querySelector(".travel-carousel-controls");
    const dots = [...controls.querySelectorAll("[data-slide-dot]")];
    const pause = controls.querySelector("[data-pause]");
    const track = carousel.querySelector(".travel-quotes__track");
    let current = 0;
    let paused = reducedMotion.matches;
    let hovered = false;
    let entrance;
    function show(index, animate = true) {
      const next = (index + quotes.length) % quotes.length;
      if (animate && current === next) return;
      const direction = index < current ? -1 : 1;
      entrance?.cancel();
      current = next;
      quotes.forEach((quote, i) => { quote.hidden = i !== current; });
      dots.forEach((dot, i) => dot.setAttribute("aria-current", String(i === current)));
      if (animate && !reducedMotion.matches) {
        entrance = quotes[current].animate(
          [{ opacity: .15, translate: (direction * 14) + "px 0" }, { opacity: 1, translate: "none" }],
          { duration: 420, easing: "cubic-bezier(.22,.72,.25,1)" },
        );
      }
    }
    function setPaused(value) {
      paused = value;
      pause.setAttribute("aria-pressed", String(paused));
      pause.setAttribute("aria-label", paused ? "Play testimonials" : "Pause testimonials");
      pause.textContent = paused ? "▶" : "Ⅱ";
      track.setAttribute("aria-live", paused ? "polite" : "off");
    }
    controls.hidden = false;
    show(0, false);
    setPaused(paused);
    controls.querySelector("[data-prev]").addEventListener("click", () => { setPaused(true); show(current - 1); });
    controls.querySelector("[data-next]").addEventListener("click", () => { setPaused(true); show(current + 1); });
    dots.forEach((dot, i) => dot.addEventListener("click", () => { setPaused(true); show(i); }));
    pause.addEventListener("click", () => setPaused(!paused));
    carousel.addEventListener("pointerenter", () => { hovered = true; });
    carousel.addEventListener("pointerleave", () => { hovered = false; });
    reducedMotion.addEventListener("change", () => setPaused(reducedMotion.matches));
    window.setInterval(() => {
      if (!paused && !hovered && !carousel.contains(document.activeElement) && !document.hidden) show(current + 1);
    }, 8000);
  });
}

function setupPostCarousels() {
  document.querySelectorAll("[data-post-carousel]").forEach(carousel => {
    const controls = carousel.querySelector(".travel-carousel-controls");
    if (!controls) return;
    const windowElement = carousel.querySelector(".travel-post-window");
    const track = carousel.querySelector(".travel-posts");
    const posts = [...track.children];
    const count = controls.querySelector("[data-carousel-count]");
    const prev = controls.querySelector("[data-prev]");
    const next = controls.querySelector("[data-next]");
    const loop = carousel.dataset.loop === "true";
    let start = 0;
    let visible = 1;
    let max = posts.length - 1;
    let step = 0;
    let touchStart;
    function show(wanted) {
      start = wanted < 0 && loop ? max : wanted > max && loop ? 0 : clamp(wanted, 0, max);
      track.style.transform = `translateX(-${start * step}px)`;
      posts.forEach((post, index) => {
        const offscreen = index < start || index >= start + visible;
        post.inert = offscreen;
        post.setAttribute("aria-hidden", String(offscreen));
      });
      prev.disabled = !max || (!loop && start === 0);
      next.disabled = !max || (!loop && start === max);
      controls.hidden = !max;
      count.textContent = `${Math.ceil(start / visible) + 1} / ${Math.ceil(max / visible) + 1}`;
    }
    function measure() {
      const cardWidth = posts[0].offsetWidth;
      if (!cardWidth) return;
      const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
      step = cardWidth + gap;
      visible = Math.max(1, Math.min(posts.length, Math.floor((track.clientWidth + gap + 1) / step)));
      max = posts.length - visible;
      const focused = posts.findIndex(post => post.contains(document.activeElement));
      show(focused >= 0 ? clamp(start, Math.max(0, focused - visible + 1), focused) : Math.min(start, max));
    }
    carousel.classList.add("is-carousel");
    function page(direction) {
      const atEnd = direction > 0 ? start === max : start === 0;
      show(atEnd && loop ? (direction > 0 ? 0 : max) : clamp(start + direction * visible, 0, max));
    }
    prev.addEventListener("click", () => page(-1));
    next.addEventListener("click", () => page(1));
    windowElement.addEventListener("touchstart", event => { touchStart = event.changedTouches[0].clientX; }, { passive: true });
    windowElement.addEventListener("touchend", event => {
      const difference = event.changedTouches[0].clientX - touchStart;
      if (Math.abs(difference) > 50) page(-Math.sign(difference));
    }, { passive: true });
    if ("ResizeObserver" in window) new ResizeObserver(measure).observe(windowElement);
    else window.addEventListener("resize", measure, { passive: true });
    measure();
  });
}

function setupStepper() {
  const sections = [...document.querySelectorAll("[data-steps]")].map(section => ({
    section,
    stage: section.querySelector(".travel-steps-stage"),
    pass: section.querySelector(".travel-boarding-pass"),
    panels: [...section.querySelectorAll("[data-step]")],
    dots: [...section.querySelectorAll("[data-step-dot]")],
    labels: [...section.querySelectorAll("[data-step-label]")],
    numbers: [...section.querySelectorAll("[data-step-num]")],
    current: -1,
    canEnhance: false,
  }));
  if (!sections.length) return;
  function update() {
    for (const stepper of sections) {
      const { section, stage, panels, dots, labels, numbers } = stepper;
      const count = panels.length;
      const stickyTop = parseFloat(getComputedStyle(stage).top) || 0;
      if (!stepper.canEnhance) {
        panels.forEach(panel => panel.removeAttribute("aria-hidden"));
        stepper.current = -1;
        continue;
      }
      const bounds = section.getBoundingClientRect();
      const distance = Math.max(1, bounds.height - stage.offsetHeight);
      const progress = clamp((stickyTop - bounds.top) / distance, 0, 1);
      const index = Math.min(count - 1, Math.floor(progress * count));
      section.style.setProperty("--step-progress", progress);
      if (stepper.current === index) continue;
      stepper.current = index;
      panels.forEach((panel, i) => {
        const position = i < index ? "before" : i > index ? "after" : "current";
        for (const element of [panel, numbers[i]]) {
          element.dataset.stepPosition = position;
          element.classList.toggle("is-current", i === index);
        }
        panel.setAttribute("aria-hidden", String(i !== index));
        labels[i].classList.toggle("is-current", i === index);
        dots[i].classList.toggle("is-past", i < index);
        if (i === index) dots[i].setAttribute("aria-current", "step");
        else dots[i].removeAttribute("aria-current");
      });
    }
  }
  sections.forEach(({ section, stage, dots }) => {
    section.style.setProperty("--step-count", dots.length);
    dots.forEach((dot, index) => dot.addEventListener("click", () => {
      const stickyTop = parseFloat(getComputedStyle(stage).top) || 0;
      const from = scrollY + section.getBoundingClientRect().top - stickyTop;
      const distance = section.getBoundingClientRect().height - stage.offsetHeight;
      window.scrollTo({ top: from + distance * (index + .5) / dots.length, behavior: reducedMotion.matches ? "instant" : "smooth" });
    }));
  });
  let frame;
  function schedule() {
    if (frame !== undefined) return;
    frame = requestAnimationFrame(() => { frame = undefined; update(); });
  }
  window.addEventListener("scroll", schedule, { passive: true });
  function measure() {
    for (const stepper of sections) {
      const { section, pass, stage, panels } = stepper;
      const wasEnhanced = stepper.canEnhance;
      const canTry = panels.length > 1 && innerHeight >= 530 && !reducedMotion.matches;
      pass.hidden = !canTry;
      section.classList.toggle("is-enhanced", canTry);
      stepper.canEnhance = canTry && stage.offsetHeight + (parseFloat(getComputedStyle(stage).top) || 0) <= innerHeight + 8;
      pass.hidden = !stepper.canEnhance;
      section.classList.toggle("is-enhanced", stepper.canEnhance);
      if (wasEnhanced !== stepper.canEnhance) stepper.current = -1;
    }
    update();
  }
  window.addEventListener("resize", measure, { passive: true });
  reducedMotion.addEventListener("change", measure);
  document.fonts?.ready.then(measure);
  measure();
}

function setupDestinations() {
  const fields = [...document.querySelectorAll("[data-destinations]")].map(element => ({
    element, words: JSON.parse(element.dataset.destinations), index: 0,
  }));
  if (!fields.length) return;
  document.querySelectorAll("[data-destination-pause]").forEach(button => {
    button.hidden = false;
    button.addEventListener("click", () => button.setAttribute("aria-pressed", String(button.getAttribute("aria-pressed") !== "true")));
  });
  setInterval(() => {
    if (reducedMotion.matches || document.hidden) return;
    for (const field of fields) {
      const bounds = field.element.getBoundingClientRect();
      if (bounds.bottom <= 0 || bounds.top >= innerHeight || field.element.closest(".travel-cta-route").querySelector("[aria-pressed='true']")) continue;
      field.index = (field.index + 1) % field.words.length;
      field.element.textContent = field.words[field.index];
    }
  }, 1800);
}

function setupMotion() {
  const level = motionLevels[document.documentElement.dataset.motion] ?? 1;
  const tickerElements = [...document.querySelectorAll("[data-marquee]")];
  const tickerButton = document.querySelector("[data-motion-toggle]");
  let tickingPaused = storage.read("fsx-tickers-paused", false) === true;
  const tickerSpeeds = new WeakMap();
  const tickerHovered = ticker => ticker.matches(":hover, :focus-within");
  const isBaggage = ticker => ticker.classList.contains("travel-baggage__belt");
  const canTick = ticker => visible.has(ticker) && (isBaggage(ticker) || !tickerHovered(ticker));
  const visible = new Set();
  const depth = [...document.querySelectorAll("[data-depth], [data-mouse], [data-float]")];
  const routes = [...document.querySelectorAll("[data-route]")];
  const tilts = new Map();
  const offsets = new WeakMap();
  let pointer = { x: innerWidth / 2, y: innerHeight / 2 };
  let hovered;
  let pointerTarget;
  let litButton;
  let tagSource;
  let tagObstacles = [];
  let tip;
  let frame;
  let previous = 0;
  let previousScroll = scrollY;
  let velocity = 0;
  let tagX = 0;
  let tagY = 0;
  let lastTagX = 0;
  let tagRotation = 0;

  function syncTickerCopies() {
    tickerElements.forEach(ticker => {
      const track = ticker.firstElementChild;
      const original = track?.firstElementChild;
      if (!original) return;
      let width = original.getBoundingClientRect().width;
      if (!width) return;
      const copies = clamp(Math.ceil(ticker.clientWidth / width) + 2, 2, 20);
      while (track.children.length > copies) track.lastElementChild.remove();
      while (track.children.length < copies) {
        const copy = original.cloneNode(true);
        copy.setAttribute("aria-hidden", "true");
        track.append(copy);
      }
      // Keep the duplicate gallery captions out of the accessibility tree.
      [...track.children].slice(1).forEach(child => child.setAttribute("aria-hidden", "true"));
    });
  }

  function toggleTickers(pause) {
    tickingPaused = pause;
    storage.write("fsx-tickers-paused", pause);
    if (tickerButton) {
      tickerButton.textContent = pause ? "Resume moving decorations" : "Pause moving decorations";
      tickerButton.hidden = !tickerElements.length || reducedMotion.matches;
    }
    tickerElements.forEach(ticker => {
      const running = !tickingPaused && !reducedMotion.matches;
      ticker.classList.toggle("is-ticking", running);
      if (!running && (reducedMotion.matches || !isBaggage(ticker))) pausedTicker(ticker);
    });
    if (!tickingPaused) syncTickerCopies();
    queue();
  }

  function pausedTicker(ticker) {
    ticker.firstElementChild?.style.removeProperty("transform");
    offsets.delete(ticker);
  }

  function queue() {
    if (frame === undefined) frame = requestAnimationFrame(paint);
  }

  function updateTilt(element, state) {
    const target = hovered === element ? 1 : 0;
    state.x += ((target ? -state.ny * state.max * level : 0) - state.x) * .06;
    state.y += ((target ? state.nx * state.max * level : 0) - state.y) * .06;
    state.lift += (target - state.lift) * .07;
    if (!target && Math.abs(state.x) + Math.abs(state.y) + state.lift < .008) {
      element.style.removeProperty("transform");
      element.style.removeProperty("filter");
      element.style.removeProperty("box-shadow");
      tilts.delete(element);
      return;
    }
    element.style.transform = `perspective(1100px) rotateX(${state.x}deg) rotateY(${state.y}deg) translate3d(${state.y * .35}px,${-state.x * .35 - state.lift * 4}px,0) scale(${1 + state.lift * .03})`;
    if (element.hasAttribute("data-noshadow")) return;
    const sx = (-state.y * 1.3).toFixed(1);
    const sy = (7 + state.lift * 16 + state.x * .9).toFixed(1);
    const blur = (12 + state.lift * 30).toFixed(1);
    const contact = "rgba(var(--travel-shadow),.23)";
    const shadow = "rgba(var(--travel-shadow),.32)";
    if (element.hasAttribute("data-cut")) element.style.filter = `drop-shadow(0 2px 1.5px ${contact}) drop-shadow(${sx}px ${sy}px ${blur}px ${shadow})`;
    else element.style.boxShadow = `0 2px 4px -2px ${contact}, ${sx}px ${sy}px ${blur}px -5px ${shadow}`;
  }

  function paint(now) {
    frame = undefined;
    const dt = Math.min(50, now - (previous || now));
    previous = now;
    velocity = velocity * .86 + (scrollY - previousScroll) * .14;
    previousScroll = scrollY;
    const view = viewport();
    if (!reducedMotion.matches) {
      for (const element of depth) {
        if (!visible.has(element) || element.matches(":hover")) continue;
        const parent = element.parentElement.getBoundingClientRect();
        const state = offsets.get(element) || { x: 0, y: 0 };
        const drift = Math.min(6, Number(element.dataset.mouse || 0) * .22);
        const float = Math.min(5, Number(element.dataset.float || 0));
        const y = (view.top + view.height / 2 - (parent.top + parent.height / 2)) * Number(element.dataset.depth || 0) * level;
        const xDrift = clamp((pointer.x / view.width - .5) * drift * 2 * level, -6, 6);
        const yDrift = clamp((pointer.y / view.height - .5) * drift * 2 * level, -6, 6);
        state.x += (xDrift - state.x) * .08;
        state.y += (yDrift - state.y) * .08;
        element.style.translate = `${state.x.toFixed(2)}px ${(clamp(y, -view.height * .24, view.height * .24) + state.y + Math.sin(now / 950) * float).toFixed(2)}px`;
        offsets.set(element, state);
      }
      for (const [element, state] of tilts) updateTilt(element, state);
      for (const element of routes) {
        if (!visible.has(element)) continue;
        const bounds = element.getBoundingClientRect();
        element.style.setProperty("--route-progress", clamp((view.bottom - bounds.top) / (view.height + bounds.height), 0, 1));
      }
      if (!tickingPaused) for (const ticker of tickerElements) {
        if (!canTick(ticker)) continue;
        const track = ticker.firstElementChild;
        const width = track.firstElementChild?.getBoundingClientRect().width;
        if (!width) continue;
        const targetSpeed = isBaggage(ticker) && tickerHovered(ticker) ? .25 : 1;
        const previousSpeed = tickerSpeeds.get(ticker) ?? 1;
        const speed = previousSpeed + (targetSpeed - previousSpeed) * Math.min(1, dt / 180);
        tickerSpeeds.set(ticker, speed);
        const amount = (offsets.get(ticker) || 0) + dt / 1000 * speed * (Number(ticker.dataset.marquee) * level + Math.min(400, Math.abs(velocity) * 16));
        offsets.set(ticker, amount % width);
        track.style.transform = `translateX(-${amount % width}px)`;
      }
    }
    if (tip && tagSource) {
      const wide = tip.offsetWidth;
      const tall = tip.offsetHeight;
      const left = pointer.x + wide + 24 > view.right;
      tip.classList.toggle("is-left", left);
      const nearX = pointer.x + (left ? -16 - wide : 16);
      const farX = pointer.x + (left ? 16 : -16 - wide);
      const source = tagSource.getBoundingClientRect();
      const clear = (x, y) => !tagObstacles.some(area => overlaps(rect(x, y, wide, tall), area, 6));
      const place = [
        [nearX, pointer.y + 12], [farX, pointer.y + 12],
        [nearX, source.bottom + 9], [nearX, source.top - tall - 9],
      ].map(([x, y]) => [
        clamp(x, view.left + 8, view.right - wide - 8),
        clamp(y, view.top + 8, view.bottom - tall - 8),
      ]).find(([x, y]) => clear(x, y));
      tip.style.visibility = place ? "" : "hidden";
      if (place) {
        const [newX, newY] = place;
        tagX += (newX - tagX) * (reducedMotion.matches ? 1 : .24);
        tagY += (newY - tagY) * (reducedMotion.matches ? 1 : .24);
        if (!clear(tagX, tagY)) {
          tagX = newX;
          tagY = newY;
          lastTagX = newX;
          tagRotation = 0;
        }
      }
      tagRotation += ((reducedMotion.matches ? 0 : clamp((tagX - lastTagX) * .8, -14, 14)) - tagRotation) * .24;
      lastTagX = tagX;
      const radians = Math.abs(tagRotation) * Math.PI / 180;
      const insetX = Math.max(0, (wide * Math.cos(radians) + tall * Math.sin(radians) - wide) / 2);
      const insetY = Math.max(0, (tall * Math.cos(radians) + wide * Math.sin(radians) - tall) / 2);
      const x = clamp(tagX, view.left + insetX + 8, view.right - wide - insetX - 8);
      const y = clamp(tagY, view.top + insetY + 8, view.bottom - tall - insetY - 8);
      tip.style.transform = `translate(${x}px,${y}px) rotate(${tagRotation}deg)`;
    }
    if ((!reducedMotion.matches && (tilts.size || (!tickingPaused && tickerElements.some(canTick)) || depth.some(el => visible.has(el)))) || tagSource) queue();
  }

  document.addEventListener("pointermove", event => {
    if (event.pointerType !== "mouse" || !(event.target instanceof Element)) return;
    pointer = { x: event.clientX, y: event.clientY };
    pointerTarget = event.target;
    if (!reducedMotion.matches) {
      hovered = pointerTarget.closest("[data-tilt]") || pointerTarget.closest(".travel-destination,.travel-post")?.querySelector("[data-tilt]");
      if (hovered) {
        const bounds = hovered.closest(".travel-destination,.travel-post")?.getBoundingClientRect() ?? hovered.getBoundingClientRect();
        const state = tilts.get(hovered) ?? { x: 0, y: 0, lift: 0, max: Number(hovered.dataset.tilt) };
        state.nx = clamp((pointer.x - bounds.left) / Math.max(1, bounds.width) * 2 - 1, -1, 1);
        state.ny = clamp((pointer.y - bounds.top) / Math.max(1, bounds.height) * 2 - 1, -1, 1);
        tilts.set(hovered, state);
      }
      const button = pointerTarget.closest("[data-magnet]");
      if (litButton && litButton !== button) litButton.style.removeProperty("background-image");
      litButton = button;
      if (button) {
        const bounds = button.getBoundingClientRect();
        button.style.backgroundImage = `radial-gradient(circle ${Math.max(70, bounds.width * .55)}px at ${pointer.x - bounds.left}px ${pointer.y - bounds.top}px, #ffffff61, #ffffff14 55%, transparent 75%)`;
      }
    }
    const tag = pointerTarget.closest("[data-tag]");
    if (tag && !tip) {
      tip = document.createElement("div");
      tip.className = "travel-tag-tip";
      tip.setAttribute("aria-hidden", "true");
      tip.hidden = true;
      document.body.append(tip);
    }
    if (tip) {
      if (tag !== tagSource) {
        tagX = pointer.x;
        tagY = pointer.y;
        lastTagX = tagX;
        tagRotation = 0;
      }
      const label = tag?.dataset.tag || "";
      if (tag && tag !== tagSource) {
        tagObstacles = safeAreas(document, viewport(), null, "a, button, summary, input, select, textarea, label, h1, h2, h3, figcaption");
      }
      if (tip.textContent !== label) tip.textContent = label;
      tip.hidden = !label;
    }
    tagSource = tag;
    queue();
  }, { passive: true });

  document.documentElement.addEventListener("pointerleave", () => {
    hovered = tagSource = null;
    if (tip) tip.hidden = true;
    if (litButton) litButton.style.removeProperty("background-image");
    queue();
  });
  window.addEventListener("scroll", () => {
    if (tip) tip.hidden = true;
    tagSource = null;
    queue();
  }, { passive: true });
  reducedMotion.addEventListener("change", () => {
    if (reducedMotion.matches) {
      depth.forEach(el => el.style.removeProperty("translate"));
      routes.forEach(el => el.style.removeProperty("--route-progress"));
      for (const element of tilts.keys()) ["transform", "filter", "box-shadow"].forEach(property => element.style.removeProperty(property));
      tilts.clear();
      if (litButton) litButton.style.removeProperty("background-image");
    }
    toggleTickers(tickingPaused);
  });

  if ("IntersectionObserver" in window) {
    const io = new IntersectionObserver(entries => {
      for (const { target, isIntersecting } of entries) {
        if (isIntersecting) visible.add(target);
        else visible.delete(target);
      }
      queue();
    }, { rootMargin: "100px" });
    [...depth, ...routes, ...tickerElements].forEach(element => io.observe(element));

    const enter = new IntersectionObserver(entries => {
      entries.forEach(({ target, isIntersecting }) => {
        if (!isIntersecting) return;
        enter.unobserve(target);
        if (reducedMotion.matches || target.matches(":focus-within")) return;
        const type = target.dataset.reveal;
        const from = { up: "translateY(44px)", pop: "scale(.82) rotate(-4deg)", drop: "translateY(-60px) rotate(6deg)", left: "translateX(-60px)", right: "translateX(60px)" };
        target.animate([{ transform: from[type] || from.up, opacity: .4 }, { transform: "none", opacity: 1 }], {
          duration: 750, delay: (Number(target.dataset.delay) || 0) * 90, easing: "cubic-bezier(.2,.8,.2,1)",
        });
      });
    });
    document.querySelectorAll("[data-reveal]").forEach(element => {
      if (element.getBoundingClientRect().top > innerHeight) enter.observe(element);
    });
  }

  tickerButton?.addEventListener("click", () => toggleTickers(!tickingPaused));
  toggleTickers(tickingPaused);
  if ("ResizeObserver" in window && tickerElements.length) {
    const tickerFills = new ResizeObserver(syncTickerCopies);
    tickerElements.forEach(ticker => tickerFills.observe(ticker));
    document.fonts?.ready.then(syncTickerCopies);
  }
}

function animateHeroWords() {
  if (reducedMotion.matches) return;
  const heading = document.querySelector(".travel-v2-hero--collage h1");
  if (!heading || heading.getBoundingClientRect().top >= innerHeight) return;
  const contents = [...heading.childNodes];
  const words = [];
  contents.forEach(node => {
    if (node.nodeType !== Node.TEXT_NODE) {
      words.push(node);
      return;
    }
    const parts = node.textContent.split(/(\s+)/).filter(Boolean).map(value => {
      if (!value.trim()) return document.createTextNode(value);
      const word = document.createElement("span");
      word.className = "travel-hero-word";
      word.textContent = value;
      words.push(word);
      return word;
    });
    node.replaceWith(...parts);
  });
  words.forEach((word, index) => word.animate([{ translate: "0 .5em", rotate: "4deg", opacity: 0 }, { translate: "0 0", rotate: "0deg", opacity: 1 }], {
    duration: 750, delay: index * 85, easing: "cubic-bezier(.2,.8,.2,1)", fill: "backwards",
  }));
}

export function initDelight() {
  const stamp = setupStamps();
  setupCompass(stamp);
  setupSuitcases();
  setupPostcards();
  setupDrag();
  setupTestimonialCarousels();
  setupPostCarousels();
  setupStepper();
  setupDestinations();
  setupMotion();
  animateHeroWords();
}
