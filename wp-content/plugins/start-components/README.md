# Start Components

Portable content and curated Gutenberg blocks for a generated Start client theme. Deploy this plugin with its built `dist/` directory. The host does not need Node, the Workbench or an npm token.

Blocks: FAQ/Disclosure, Contact Callout, Design Component (button, card, badge, tag, icon, divider, progress bar/ring, read-only rating, avatar), and ACF Hero where ACF Pro is available. `component-registry.json` records the full vendor catalog and the current client adapters. New vendor components require a reviewed adapter with attributes, fallback, editor, focus and accessibility contracts; catalog availability alone does not make a Gutenberg block.

Edit ACF definitions through the normal ACF UI or `acf-json/`; stored values stay in WordPress. Content selection, taxonomy/meta and public business defaults are in `project-content.json`. Stable keys and block names are compatibility contracts. Theme changes do not remove content definitions. Keep extensions in `custom/functions.php` and `src/`; rebuild assets after source edits.

For development use Node24.13+. Start's Prepare development tools action can install the pinned Pro source using a project-bound Keychain credential. Alternatively copy `npmrc.example` to `.npmrc`, supply `WEBAWESOME_NPM_TOKEN` only through the local environment, run `npm ci --ignore-scripts`, then `npm run build`. Never commit `.npmrc`, keys or node_modules. `npm run dev` serializes component rebuilds. The Pro runtime and DOMPurify notices/licenses are retained in dist. Icons may fetch through the public configured Font Awesome kit; this is not a fully offline icon bundle.

The theme owns its reset and tokens. Vendor themes/utilities are scoped to content documents rather than WordPress editor chrome. Rich text uses WordPress KSES on the server and DOMPurify in previews. Native content survives unavailable JavaScript. Block availability affects insertion, not saved content. Pattern suppression is restricted to the curated theme and must be rechecked on WordPress editor updates.

`security.php` enforces TLS and refuses redirects for ACFE's license endpoint, including older vendor versions whose own UI requests disable verification. Licenses remain opt-in. This does not establish a security guarantee for third-party plugin code.

Validated locally with WordPress7.1.3/PHP8.2.30 and Chromium/WebKit. Automated accessibility and editor save/reopen are covered; manual assistive-technology and real client/provider evidence must be recorded separately. Follow AGENTS.md and STYLE.md when extending this project.
