# Component style and behavior

Theme design.tokens.json is the canonical visual source. Use semantic text/background/link/focus/border roles and inherited spacing/radius/font variables. Keep native and enhanced appearances coherent. Put reusable client choices in block styles instead of exposing arbitrary controls.

Label controls visibly; retain focus indicators, sufficient contrast and useful HTML before enhancement. Interactive previews do not replace the fields used to edit a block. Respect reduced motion. New components need keyboard/focus, readable failure, sanitization and save/reopen proof. Keep patterns limited to selected reusable client sections and expose them through clear tools.

Preserve stable design/component IDs for the later EXP integration. No native EXP round-trip implementation is included here.
