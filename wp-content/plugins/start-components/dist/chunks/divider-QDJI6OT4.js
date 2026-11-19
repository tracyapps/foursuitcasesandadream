import{a as n}from"./chunk-VJVG245B.js";import{a as s}from"./chunk-JGNA2U5L.js";import{a,b as i,h as e,i as o,j as l,m as r}from"./chunk-77KF3W73.js";var h=a`
  :host {
    --color: var(--wa-color-surface-border);
    --width: var(--wa-border-width-s);
    --spacing: var(--wa-space-m);
    --label-spacing: var(--wa-space-s);
    --label-offset: 0;
  }

  :host(:not([orientation='vertical'])) {
    display: block;
    border-top: solid var(--width) var(--color);
    margin: var(--spacing) 0;
  }

  :host([orientation='vertical']) {
    display: inline-block;
    height: 100%;
    border-inline-start: solid var(--width) var(--color);
    margin: 0 var(--spacing);
    min-block-size: 1lh;
  }

  /* With a label, the host's border gives way to two lines that flank the label */
  :host([with-label]) {
    align-items: center;
    gap: var(--label-spacing);
    border: none;
  }

  :host([with-label]:not([orientation='vertical'])) {
    display: flex;
  }

  :host([with-label][orientation='vertical']) {
    display: inline-flex;
    flex-direction: column;
  }

  :host([with-label])::before,
  :host([with-label])::after {
    content: '';
    flex: 1 1 0;
  }

  :host([with-label]:not([orientation='vertical']))::before,
  :host([with-label]:not([orientation='vertical']))::after {
    min-inline-size: 1em;
    border-top: solid var(--width) var(--color);
  }

  :host([with-label][orientation='vertical'])::before,
  :host([with-label][orientation='vertical'])::after {
    min-block-size: 1lh;
    border-inline-start: solid var(--width) var(--color);
  }

  :host([with-label][label-placement='start'])::before,
  :host([with-label][label-placement='end'])::after {
    flex: 0 0 var(--label-offset);
    min-inline-size: 0;
    min-block-size: 0;
  }

  :host(:not([with-label])) .label {
    display: none;
  }

  :host([with-label]) .label {
    display: inline-flex;
    align-items: center;
    gap: var(--wa-space-2xs);
    flex: 0 1 auto;
    color: var(--wa-color-text-quiet);
    font-size: var(--wa-font-size-s);
    line-height: var(--wa-line-height-condensed);
    text-align: center;
  }
`;var t=class extends r{constructor(){super(...arguments),this.hasSlotController=new s(this,"[default]"),this.orientation="horizontal",this.withLabel=!1,this.labelPlacement="center"}connectedCallback(){super.connectedCallback(),this.setAttribute("role","separator")}willUpdate(c){this.withLabel=this.hasSlotController.test("[default]","withLabel"),super.willUpdate(c)}handleVerticalChange(){this.setAttribute("aria-orientation",this.orientation)}handleSlotChange(){this.internals&&(this.internals.ariaLabel=this.textContent?.trim()||null)}render(){return i`
      <div part="label" class="label">
        <slot @slotchange=${this.handleSlotChange}></slot>
      </div>
    `}};t.css=h;e([l({reflect:!0})],t.prototype,"orientation",2);e([l({attribute:"with-label",type:Boolean,reflect:!0})],t.prototype,"withLabel",2);e([l({attribute:"label-placement",reflect:!0})],t.prototype,"labelPlacement",2);e([n("orientation")],t.prototype,"handleVerticalChange",1);t=e([o("wa-divider")],t);export{t as default};
/*! For license information please see divider-QDJI6OT4.js.LEGAL.txt */
