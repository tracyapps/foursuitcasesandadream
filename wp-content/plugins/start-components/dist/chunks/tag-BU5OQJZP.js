import"./chunk-4JRNXKCH.js";import"./chunk-EJO2UXAP.js";import"./chunk-A6F2TFV3.js";import{a as p}from"./chunk-2ZEB42LG.js";import{a as c}from"./chunk-465Q73BL.js";import"./chunk-2MYMDAOT.js";import{a as n}from"./chunk-VJVG245B.js";import"./chunk-JGNA2U5L.js";import"./chunk-QOLYX4MT.js";import{a as m}from"./chunk-K6YBMDHO.js";import"./chunk-SDNW3ZYT.js";import{a as s}from"./chunk-HXQCAPQT.js";import{a,b as t,h as o,i as l,j as e,m as i}from"./chunk-77KF3W73.js";var u=class extends Event{constructor(){super("wa-remove",{bubbles:!0,cancelable:!1,composed:!0})}};var d=a`
  @layer wa-component {
    :host {
      display: inline-flex;
      gap: 0.5em;
      border-radius: var(--wa-border-radius-m);
      align-items: center;
      background-color: var(--wa-color-fill-quiet, var(--wa-color-neutral-fill-quiet));
      border-color: var(--wa-color-border-normal, var(--wa-color-neutral-border-normal));
      border-style: var(--wa-border-style);
      border-width: var(--wa-border-width-s);
      color: var(--wa-color-on-quiet, var(--wa-color-neutral-on-quiet));
      font-size: inherit;
      line-height: 1;
      white-space: nowrap;
      user-select: none;
      -webkit-user-select: none;
      height: calc(var(--wa-form-control-height) * 0.8);
      line-height: calc(var(--wa-form-control-height) - var(--wa-form-control-border-width) * 2);
      padding: 0 0.75em;
    }

    /* Appearance modifiers */
    :host([appearance='outlined']) {
      color: var(--wa-color-on-quiet, var(--wa-color-neutral-on-quiet));
      background-color: transparent;
      border-color: var(--wa-color-border-loud, var(--wa-color-neutral-border-loud));
    }

    :host([appearance='filled']) {
      color: var(--wa-color-on-quiet, var(--wa-color-neutral-on-quiet));
      background-color: var(--wa-color-fill-quiet, var(--wa-color-neutral-fill-quiet));
      border-color: transparent;
    }

    :host([appearance='filled-outlined']) {
      color: var(--wa-color-on-quiet, var(--wa-color-neutral-on-quiet));
      background-color: var(--wa-color-fill-quiet, var(--wa-color-neutral-fill-quiet));
      border-color: var(--wa-color-border-normal, var(--wa-color-neutral-border-normal));
    }

    :host([appearance='accent']) {
      color: var(--wa-color-on-loud, var(--wa-color-neutral-on-loud));
      background-color: var(--wa-color-fill-loud, var(--wa-color-neutral-fill-loud));
      border-color: transparent;
    }
  }

  .content {
    font-size: var(--wa-font-size-smaller);
  }

  [part='remove-button'] {
    line-height: 1;
  }

  [part='remove-button']::part(base) {
    padding: 0;
    height: 1em;
    width: 1em;
    color: currentColor;
  }

  @media (hover: hover) {
    :host(:hover) > [part='remove-button']::part(base) {
      background-color: transparent;
      color: color-mix(in oklab, currentColor, var(--wa-color-mix-hover));
    }
  }

  :host(:active) > [part='remove-button']::part(base) {
    background-color: transparent;
    color: color-mix(in oklab, currentColor, var(--wa-color-mix-active));
  }

  /*
   * Pill modifier
   */
  :host([pill]) {
    border-radius: var(--wa-border-radius-pill);
  }
`;var r=class extends i{constructor(){super(...arguments),this.localize=new c(this),this.variant="neutral",this.appearance="filled-outlined",this.size="m",this.pill=!1,this.withRemove=!1}handleSizeChange(){p(this.localName,this.size)}handleRemoveClick(){this.dispatchEvent(new u)}render(){return t`
      <slot part="content" class="content"></slot>

      ${this.withRemove?t`
            <wa-button
              part="remove-button"
              exportparts="base:remove-button__base"
              class="remove"
              appearance="plain"
              size=${this.size}
              @click=${this.handleRemoveClick}
              tabindex="-1"
            >
              <wa-icon name="xmark" library="system" variant="solid" label=${this.localize.term("remove")}></wa-icon>
            </wa-button>
          `:""}
    `}};r.css=[d,s,m];o([e({reflect:!0})],r.prototype,"variant",2);o([e({reflect:!0})],r.prototype,"appearance",2);o([e({reflect:!0})],r.prototype,"size",2);o([n("size")],r.prototype,"handleSizeChange",1);o([e({type:Boolean,reflect:!0})],r.prototype,"pill",2);o([e({attribute:"with-remove",type:Boolean})],r.prototype,"withRemove",2);r=o([l("wa-tag")],r);export{r as default};
/*! For license information please see tag-BU5OQJZP.js.LEGAL.txt */
