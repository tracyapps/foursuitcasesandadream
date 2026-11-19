# Client theme

Independent PHP-first WordPress theme based on the client-v2 Start 2 snapshot. Requires WordPress 6.6+, PHP 8.0+; the build uses Node 24.13+ with pinned esbuild and SVGO development dependencies.

Run `npm run build`; `npm run dev` serializes rebuilds as source changes. Build emits CSS, JS and a validated SVG sprite. Commit deployable assets/dist alongside source. Hosts do not need Node or the Workbench.

Edit templates, inc/custom.php, assets/src/css/custom.css and scripts normally. ACF JSON and durable content definitions belong in wp-content/plugins/start-components; ACF saves/loads its JSON there. Keep client extensions in that plugin’s custom/functions.php and its src directory.

Design source: design.tokens.json. Run the build after direct token edits. Workbench updates also compile tokens and theme.json; they stop on conflicts. Generated outputs: style.css (metadata only), project.identity.json, design.tokens.json, theme.json, assets/dist/css/tokens.css. Do not add custom CSS to style.css. Use custom.css. All other scaffold files become yours after creation; Start does not silently upgrade them.

Keep the Start Components companion plugin enabled for reusable blocks. Block identities remain start/disclosure and start/contact-callout across themes. The theme does not copy Pro credentials or vendor source; the plugin supplies its own deployable runtime.

Client identity metadata does not rename database content, set the WordPress site title or provision pages. Display names can change; slugs/prefixes require migration. theme.json editor palette uses the light design values; runtime CSS maps semantic colors for system/light/dark modes. Client palette choices should use semantic roles (text/background/link) where color meaning must follow mode.

Run `npm install` once, retain the lockfile, then use `npm ci` on clean checkouts. Builds minify CSS and JavaScript, optimize trusted SVGs, and emit style-guide data. The style guide is available to editors at `?start_style_guide=1`, outside menus and sitemaps.

Split styles with quoted imports such as `@import "./blocks/boarding-pass-stepper.css";`. Import paths are relative to the importing file and must stay within `assets/src/css`. The build inlines partials in import order.

`assets/src/css/custom.css` is the index for the travel styles loaded after the theme defaults. Look in `css/blocks/` for layouts and variants, and `css/custom/` for shared travel styles, site chrome, and the Style Kit. Use native CSS nesting for related selectors.

Start the site in Local, run `npm run dev` from the theme directory, and open `http://foursuitcasesandadream.local`. The watcher sends reload events on `127.0.0.1:35729` while Local serves the WordPress pages. The default site origin lives in `scripts/build.mjs`. Override it for another local domain or scheme with `START_DEV_ORIGIN=https://example.local npm run dev`. Enable `FOURSUITCASES_DEV_RELOAD` in local `wp-config.php` to load the browser client.

## Four Suitcases travel blocks

The companion plugin registers the travel blocks and their fields. The theme renders them from `template-parts/travel/`. In the block inspector, Layout and Palette work independently. Look for Background and Size on blocks that offer those options. The private Style guide previews the variants and includes the draft FAQ answers.

On the local Four Suitcases site, the Style guide can apply the v2 reference layouts to Home and Plan Your Trip. WP-CLI exposes the same update as `wp four-suitcases apply-reference-pages`. The update uses standard editable blocks, keeps the existing inquiry content and skips pages that already use the new layouts.
