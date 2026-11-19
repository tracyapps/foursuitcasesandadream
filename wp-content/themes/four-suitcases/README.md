# Client theme

Independent PHP-first WordPress theme based on the client-v2 Start 2 snapshot. Requires WordPress 6.6+, PHP 8.0+; the build uses Node 24.13+ with pinned esbuild and SVGO development dependencies.

Run `npm run build`; `npm run dev` serializes rebuilds as source changes. Build emits CSS, JS and a validated SVG sprite. Commit deployable assets/dist alongside source. Hosts do not need Node or the Workbench.

Edit templates, inc/custom.php, assets/src/css/custom.css and scripts normally. ACF JSON and durable content definitions belong in wp-content/plugins/start-components; ACF saves/loads its JSON there. Keep client extensions in that plugin’s custom/functions.php and its src directory.

Design source: design.tokens.json. Run the build after direct token edits. Workbench updates also compile tokens and theme.json; they stop on conflicts. Generated outputs: style.css (metadata only), project.identity.json, design.tokens.json, theme.json, assets/dist/css/tokens.css. Do not add custom CSS to style.css. Use custom.css. All other scaffold files become yours after creation; Start does not silently upgrade them.

Keep the Start Components companion plugin enabled for reusable blocks. Block identities remain start/disclosure and start/contact-callout across themes. The theme does not copy Pro credentials or vendor source; the plugin supplies its own deployable runtime.

Client identity metadata does not rename database content, set the WordPress site title or provision pages. Display names can change; slugs/prefixes require migration. theme.json editor palette uses the light design values; runtime CSS maps semantic colors for system/light/dark modes. Client palette choices should use semantic roles (text/background/link) where color meaning must follow mode.

Client-v2 tooling: run npm install once, retain the lockfile, then npm ci on clean checkouts. Builds minify CSS/JS, optimize trusted SVGs, and emit style-guide data. npm run dev serves a loopback-only reload stream on port 35729. Set START_DEV_ORIGIN to your development site URL when starting the watcher. Enable FOURSUITCASES_DEV_RELOAD in local wp-config.php only; production never needs Node. The style guide is available to editors at ?start_style_guide=1, outside menus and sitemaps.

## Four Suitcases travel blocks

The companion plugin registers the travel blocks and their fields. The theme renders them from `template-parts/travel/`. In the block inspector, Layout and Palette work independently. Look for Background and Size on blocks that offer those options. The private Style guide previews the variants and includes the draft FAQ answers.

On the local Four Suitcases site, the Style guide can apply the v2 reference layouts to Home and Plan Your Trip. WP-CLI exposes the same update as `wp four-suitcases apply-reference-pages`. The update uses standard editable blocks, keeps the existing inquiry content and skips pages that already use the new layouts.
