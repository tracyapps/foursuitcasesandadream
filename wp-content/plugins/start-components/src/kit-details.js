import { ensureVendor } from './vendor.js';
import { enhanceDisclosures } from './enhance.js';
ensureVendor(document, window.startComponentsVendor).then(() => enhanceDisclosures()).catch(() => {
  // A blocked/offline kit keeps the native, usable content in place.
  document.documentElement.dataset.startVendor = 'unavailable';
});
