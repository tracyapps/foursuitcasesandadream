# Client theme development

Read README.md before changing build scripts, generated files or identity. Read STYLE.md before changing tokens, component styles or editor choices.

- Keep custom PHP in inc/custom.php and custom CSS in assets/src/css/custom.css. Templates and ACF JSON are project-owned extension surfaces.
- Edit design.tokens.json for reusable design values, increment its revision and run npm run build. Preserve stable token IDs and aliases.
- Keep durable content definitions in the companion plugin and retain existing block names/attributes. Use native fallbacks for interactive components.
- Verify the selected LocalWP PHP binary and database socket before WP-CLI commands. Build the theme, lint changed PHP and check actual frontend/editor behavior; report manual accessibility checks separately.
- Workbench plans stop on generated-file conflicts. Reconcile the source/baseline instead of forcing a global replacement or regenerating project-owned files.
