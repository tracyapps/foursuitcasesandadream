// Each editor iframe owns a different module/custom-element registry.
const registries = new WeakMap();
function moduleScript(doc, src, attributes = {}) {
  return new Promise((resolve, reject) => {
    const script = doc.createElement('script');
    script.type = 'module';
    script.src = src;
    for (const [key, value] of Object.entries(attributes)) script.setAttribute(key, value);
    const timer = setTimeout(() => reject(new Error('Web Awesome component timed out')), 20000);
    script.onload = () => { clearTimeout(timer); resolve(); };
    script.onerror = () => { clearTimeout(timer); reject(new Error('Web Awesome component could not load')); };
    doc.head.append(script);
  });
}
export function ensureVendor(doc, config) {
  if (registries.has(doc)) return registries.get(doc);
  const pending = (async () => {
    if (!config || !['kit', 'npm'].includes(config.provider)) throw new Error('Component provider needs configuration');
    if (config.faKit) doc.documentElement.setAttribute('data-fa-kit-code', config.faKit);
    // Never apply a vendor theme to WordPress's editor chrome.
    const isEditorChrome = doc.body.classList.contains('wp-admin');
    if (!isEditorChrome) {
      for (const file of config.provider === 'kit' ? ['styles/themes/default.css', 'styles/utilities.css'] : ['vendor-theme.css', 'vendor-utilities.css']) {
        const link = doc.createElement('link');
        link.rel = 'stylesheet'; link.href = config.base + file;
        link.dataset.startVendorStyle = 'true'; doc.head.append(link);
      }
      const syncMode = () => {
        const mode = doc.documentElement.dataset.theme;
        const dark = mode === 'dark' || (mode !== 'light' && doc.defaultView.matchMedia('(prefers-color-scheme: dark)').matches);
        doc.documentElement.classList.toggle('wa-dark', dark);
        doc.documentElement.classList.toggle('wa-light', !dark);
      };
      syncMode();
      new doc.defaultView.MutationObserver(syncMode).observe(doc.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
      doc.defaultView.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', syncMode);
    }
    if (config.provider === 'kit') {
      await moduleScript(doc, config.base + 'webawesome.loader.js', { 'data-fa-kit-code': config.faKit, 'data-start-kit-loader': 'true' });
      // Native fallbacks contain no wa-* tags for the autoloader to discover.
      // Explicitly request Details; all other components stay demand-loaded.
      await moduleScript(doc, config.base + 'components/details/details.js');
    } else {
      await moduleScript(doc, config.previewScript, { 'data-start-components-preview': 'true' });
    }
    if (!doc.defaultView.customElements.get('wa-details')) throw new Error('Web Awesome Details is unavailable');
    doc.documentElement.dataset.startVendor = config.provider;
  })();
  registries.set(doc, pending);
  return pending;
}
