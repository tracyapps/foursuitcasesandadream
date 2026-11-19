const { BlockControls } = window.wp.blockEditor;
const { MenuGroup, MenuItem, ToolbarDropdownMenu } = window.wp.components;
const { createElement: element, Fragment } = window.wp.element;
const { addFilter } = window.wp.hooks;
const { __ } = window.wp.i18n;

const labels = {
  none: __("None", "four-suitcases"),
  contained: __("Contained", "four-suitcases"),
  wide: __("Wide width", "four-suitcases"),
  full: __("Full width", "four-suitcases"),
};

const compactBlocks = ["acf/hero", "acf/travel-card", "start/contact-callout", "start/design-element", "start/disclosure"];

function blockWidth({ name, attributes }) {
  const isTravelSection = name?.startsWith("acf/travel-") && name !== "acf/travel-card";
  if (!isTravelSection && !compactBlocks.includes(name)) return null;
  return Object.hasOwn(labels, attributes?.align) ? attributes.align : (isTravelSection ? "full" : "none");
}

addFilter("editor.BlockEdit", "four-suitcases/block-width", (BlockEdit) => (props) => {
  const width = blockWidth(props);
  if (!width || !props.isSelected) return element(BlockEdit, props);

  return element(Fragment, null,
    element(BlockEdit, props),
    element(BlockControls, { group: "block" },
      element(ToolbarDropdownMenu, { icon: "align-center", label: __("Block alignment", "four-suitcases") },
        ({ onClose }) => element(MenuGroup, null,
          Object.entries(labels).map(([value, label]) => element(MenuItem, {
            key: value,
            role: "menuitemradio",
            isSelected: width === value,
            onClick: () => {
              props.setAttributes({ align: value });
              onClose();
            },
          }, label))
        )
      )
    )
  );
});

addFilter("editor.BlockListBlock", "four-suitcases/block-width", (BlockListBlock) => (props) => {
  const width = blockWidth(props);
  if (!width) return element(BlockListBlock, props);

  return element(BlockListBlock, {
    ...props,
    wrapperProps: { ...props.wrapperProps, "data-fsa-width": width },
  });
});
