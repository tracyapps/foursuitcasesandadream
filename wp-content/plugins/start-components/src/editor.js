import './element-editor.js';
import DOMPurify from 'dompurify';
import { makeDisclosure } from './presentation.js';
import { ensureVendor } from './vendor.js';
const { createElement: el, Fragment, useEffect, useRef } = window.wp.element;
const { registerBlockType } = window.wp.blocks;
const { useBlockProps, RichText, InspectorControls } = window.wp.blockEditor;
const { PanelBody, TextControl, ToggleControl } = window.wp.components;
const { __ } = window.wp.i18n;

// React 18 is supplied by this WordPress install. Mount enhancement within an
// owned DOM container, so React never reconciles the vendor's replaced subtree.
function Preview({ summary, content, open, accent }) {
  const ref = useRef();
  useEffect(() => {
    const node = ref.current;
    const doc = node.ownerDocument;
    const fallback = doc.createElement('details');
    fallback.open = open;
    const title = doc.createElement('summary');
    title.textContent = summary || __('More information', 'start-components');
    const body = doc.createElement('div');
    // Match the approved rich-text vocabulary; retained attributes remain untrusted.
    body.innerHTML = DOMPurify.sanitize(content, { ALLOWED_TAGS: ['p', 'strong', 'em', 'a', 'br'], ALLOWED_ATTR: ['href', 'title'] });
    fallback.append(title, body);
    node.replaceChildren(fallback);
    let cancelled = false;
    // An iframe has its own custom element registry. Loading in editor chrome
    // does not register the component in the editable canvas.
    ensureVendor(doc, window.startComponentsEditor.vendor).then(() => {
      if (cancelled) return;
      const component = makeDisclosure(doc, { summary: title.textContent, open, accent }, body);
      node.replaceChildren(component);
    }).catch(() => {
      if (!cancelled) node.dataset.vendorStatus = 'unavailable';
    });
    return () => { cancelled = true; node.replaceChildren(); };
  }, [summary, content, open, accent]);
  return el('div', { ref, className: 'start-disclosure__preview' });
}
registerBlockType('start/disclosure', {
  edit({ attributes: a, setAttributes }) {
    return el(Fragment, {},
      el(InspectorControls, {}, el(PanelBody, { title: __('Disclosure settings', 'start-components') },
        el(ToggleControl, { __nextHasNoMarginBottom: true, label: __('Open initially', 'start-components'), checked: a.open, onChange: open => setAttributes({ open }) }))),
      el('div', useBlockProps(),
        el(TextControl, { __next40pxDefaultSize: true, __nextHasNoMarginBottom: true, label: __('Question / summary', 'start-components'), value: a.summary, onChange: summary => setAttributes({ summary }) }),
        el('label', { className: 'start-field-label' }, __('Answer', 'start-components')),
        el(RichText, { tagName: 'div', className: 'start-answer-editor', value: a.content, allowedFormats: ['core/bold', 'core/italic', 'core/link'], onChange: content => setAttributes({ content }), 'aria-label': __('Answer', 'start-components') }),
        el('p', { className: 'start-field-label' }, __('Interactive preview', 'start-components')),
        el(Preview, { summary: a.summary, content: a.content, open: a.open, accent: a.className?.includes('is-style-accent') })
      ));
  },
  save() { return null; }
});
registerBlockType('start/contact-callout', {
  edit({ attributes: a, setAttributes }) {
    return el('section', useBlockProps(),
      el(TextControl, { __next40pxDefaultSize: true, __nextHasNoMarginBottom: true, label: __('Heading', 'start-components'), value: a.heading, onChange: heading => setAttributes({ heading }) }),
      el(RichText, { tagName: 'div', value: a.content, allowedFormats: ['core/bold', 'core/italic'], onChange: content => setAttributes({ content }), 'aria-label': __('Contact message', 'start-components') }),
      el(TextControl, { __next40pxDefaultSize: true, __nextHasNoMarginBottom: true, type: 'email', label: __('Contact email', 'start-components'), help: __('The email link appears once a valid address is provided.', 'start-components'), value: a.email, onChange: email => setAttributes({ email }) }));
  },
  save() { return null; }
});

if (window.startComponentsEditor.hidePatterns) {
  // Compatibility adapter for WP's hard-coded inserter tabs. Disable as well as
  // hide the empty tab so roving keyboard navigation skips it. Never remove or
  // alter saved pattern content. A focused Patterns tab returns to Blocks.
  const curateInserter = () => {
    document.querySelectorAll('.block-editor-inserter__menu [role="tab"][id$="-patterns"]').forEach(tab => {
      if (tab.getAttribute('aria-selected') === 'true') {
        tab.closest('.block-editor-inserter__menu').querySelector('[role="tab"][id$="-blocks"]')?.click();
      }
      tab.disabled = true;
      tab.hidden = true;
    });
  };
  // The core Tabs store still contains the omitted tab. Keep its documented
  // manual activation behavior while navigating only the visible tabs.
  document.addEventListener('keydown', event => {
    const tab = event.target.closest?.('.block-editor-inserter__menu [role="tab"]');
    if (!tab || !['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
    const tabs = [...tab.closest('[role="tablist"]').querySelectorAll('[role="tab"]')].filter(item => !item.disabled && !item.hidden);
    const current = tabs.indexOf(tab);
    if (current < 0 || !tabs.length) return;
    const rtl = getComputedStyle(tab).direction === 'rtl';
    const step = (event.key === 'ArrowRight' ? 1 : -1) * (rtl ? -1 : 1);
    const index = event.key === 'Home' ? 0 : event.key === 'End' ? tabs.length - 1 : (current + step + tabs.length) % tabs.length;
    event.preventDefault();
    event.stopPropagation();
    tabs[index].focus();
  }, true);
  const observer = new MutationObserver(curateInserter);
  observer.observe(document.body, { childList: true, subtree: true });
  curateInserter();
}
