const links = "a[href], area[href], wa-button[href]";
const editorBlock = ".block-editor-block-list__block[data-type]";

function disablePreviewLink(link) {
  const block = link.closest(editorBlock);
  if (!block || link.closest('.acf-field, .acf-block-fields, .acf-block-form, [contenteditable="true"]')) return;

  const name = block.dataset.type;
  const preview = name === "acf/hero" || name.startsWith("acf/travel-")
    ? link.closest(".acf-block-preview")
    : name === "start/disclosure"
      ? link.closest(".start-disclosure__preview")
      : name === "start/design-element"
        ? link.closest("[data-start-element]")
        : null;
  if (!preview || !block.contains(preview)) return;

  // Removing the destination also blocks new-tab shortcuts and link context menus.
  // This changes the rendered preview only, leaving block attributes untouched.
  link.removeAttribute("href");
  if (link.localName === "wa-button") {
    link.inert = true;
  } else {
    link.removeAttribute("tabindex");
  }
}

function scan(node) {
  if (node.matches?.(links)) disablePreviewLink(node);
  node.querySelectorAll?.(links).forEach(disablePreviewLink);
}

// ACF refreshes previews when fields change, and vendor previews can mount later.
const observer = new MutationObserver(records => {
  for (const record of records) {
    if (record.type === "attributes") {
      if (record.target.matches(links)) disablePreviewLink(record.target);
    } else {
      record.addedNodes.forEach(scan);
    }
  }
});
observer.observe(document.documentElement, {
  subtree: true, childList: true, attributes: true, attributeFilter: ["href"],
});
scan(document);
