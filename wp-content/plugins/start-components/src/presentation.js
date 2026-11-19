// Pure DOM factory shared by editor and frontend. Registration belongs to the
// target document; importing this helper does not register a vendor element.
export function makeDisclosure(doc, { summary, open, accent }, body) {
  const component = doc.createElement('wa-details');
  component.setAttribute('summary', summary || '');
  component.setAttribute('appearance', accent ? 'filled-outlined' : 'outlined');
  if (open) component.setAttribute('open', '');
  for (const [slot, symbol] of [['expand-icon', '+'], ['collapse-icon', '−']]) {
    const icon = doc.createElement('span');
    icon.slot = slot;
    icon.setAttribute('aria-hidden', 'true');
    icon.textContent = symbol;
    component.append(icon);
  }
  component.append(body);
  return component;
}
