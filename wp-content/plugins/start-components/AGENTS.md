# Component development

Read README.md and component-registry.json before adding blocks or vendor imports. Read STYLE.md for visual/interaction rules.

- Preserve saved block names/attributes, ACF keys and public URLs; changes need explicit migrations.
- Keep custom PHP in custom/functions.php; ordinary ACF JSON and src edits are project-owned. Build deployable dist after source changes.
- Secrets belong in protected environment/Keychain/vendor settings, never JSON definitions, browser props, source or dist.
- Validate input, check capabilities for writes, escape output and provide native fallbacks. Do not implement security by hiding UI.
- Check actual frontend and editor iframe, save/reopen, JS failure, light/dark, mobile, keyboard and reduced motion. Run PHP lint/build and report manual accessibility/provider checks separately.
- Do not replace client source during a runtime update. Stop on ownership conflicts. Third-party activation effects require their own recovery plan.
