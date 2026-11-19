import { makeDisclosure } from './presentation.js';

export function enhanceDisclosures(root = document) {
  root.querySelectorAll('details[data-start-disclosure]').forEach(native => {
    // Preserve a visitor's interaction if loading was delayed. Never replace the
    // focused element while someone is using the native fallback.
    if (native.contains(native.ownerDocument.activeElement)) {
      native.addEventListener('focusout', () => queueMicrotask(() => enhanceDisclosures(root)), { once: true });
      return;
    }
    const summary = native.querySelector(':scope > summary');
    if (!summary) return;
    const body = native.ownerDocument.createDocumentFragment();
    while (summary.nextSibling) body.append(summary.nextSibling);
    const component = makeDisclosure(native.ownerDocument, {
      summary: summary.textContent,
      open: native.open,
      accent: native.parentElement.classList.contains('is-style-accent')
    }, body);
    native.replaceWith(component);
  });
}
