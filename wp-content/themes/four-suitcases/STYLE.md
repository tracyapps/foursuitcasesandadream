# Design rules

Canonical source: design.tokens.json. Typed tokens have stable dotted IDs, whole-value aliases and optional dark values. The compiler resolves aliases independently per mode and emits the WordPress palette, runtime variables and Web Awesome mappings.

Use semantic color roles: bg, surface, text, link, focus and border. Respect space/radius/layout tokens. Keep approved block styles discoverable through Blocks. Add reusable sections as selected client tools.

Custom selectors belong in assets/src/css/custom.css. Preserve focus indicators, reduced motion and usable native HTML when enhancement fails. Review both explicit modes and the system preference in the editor iframe and frontend.

The exchange schema is Start version 1, not an EXP .design document. Preserve token IDs/revisions for the future adapter; native EXP live-component import/write-back remains separate work.
