import{b as w}from"./chunks/chunk-EJO2UXAP.js";import{a as v}from"./chunks/chunk-465Q73BL.js";import"./chunks/chunk-2MYMDAOT.js";import{a as y}from"./chunks/chunk-VJVG245B.js";import{a as g}from"./chunks/chunk-QOLYX4MT.js";import"./chunks/chunk-SDNW3ZYT.js";import{a as h,b as u,h as o,i as m,j as s,k as f,l as n,m as b}from"./chunks/chunk-77KF3W73.js";var x=h`
  :host {
    --spacing: var(--wa-space-m);
    --show-duration: var(--wa-transition-normal);
    --hide-duration: var(--wa-transition-normal);

    display: block;
  }

  details {
    display: block;
    overflow-anchor: none;
    border: var(--wa-panel-border-width) var(--wa-color-surface-border) var(--wa-panel-border-style);
    background-color: var(--wa-color-surface-default);
    border-radius: var(--wa-panel-border-radius);
    color: var(--wa-color-text-normal);

    /* Print styles */
    @media print {
      background: none;
      border: solid var(--wa-border-width-s) var(--wa-color-surface-border);

      summary {
        list-style: none;
      }
    }
  }

  /* Appearance modifiers */
  :host([appearance='plain']) details {
    background-color: transparent;
    border-color: transparent;
    border-radius: 0;
  }

  :host([appearance='outlined']) details {
    background-color: var(--wa-color-surface-default);
    border-color: var(--wa-color-surface-border);
  }

  :host([appearance='filled']) details {
    background-color: var(--wa-color-neutral-fill-quiet);
    border-color: transparent;
  }

  :host([appearance='filled-outlined']) details {
    background-color: var(--wa-color-neutral-fill-quiet);
    border-color: var(--wa-color-neutral-border-quiet);
  }

  :host([disabled]) details {
    opacity: 0.5;
    cursor: not-allowed;
  }

  summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--spacing);
    padding: var(--spacing); /* Add padding here */
    border-radius: calc(var(--wa-panel-border-radius) - var(--wa-panel-border-width));
    user-select: none;
    -webkit-user-select: none;
    cursor: pointer;

    &::marker,
    &::-webkit-details-marker {
      display: none;
    }

    &:focus {
      outline: none;
    }

    &:focus-visible {
      outline: var(--wa-focus-ring);
      outline-offset: calc(var(--wa-panel-border-width) + var(--wa-focus-ring-offset));
    }
  }

  :host([open]) summary {
    border-end-start-radius: 0;
    border-end-end-radius: 0;
  }

  /* 'Start' icon placement */
  :host([icon-placement='start']) summary {
    flex-direction: row-reverse;
    justify-content: start;
  }

  [part~='icon'] {
    flex: 0 0 auto;
    display: flex;
    align-items: center;
    color: var(--wa-color-text-quiet);
    transition: rotate var(--wa-transition-normal) var(--wa-transition-easing);
  }

  :host([open]) [part~='icon'] {
    rotate: 90deg;
  }

  :host([open]:dir(rtl)) [part~='icon'] {
    rotate: -90deg;
  }

  :host([open]) slot[name='expand-icon'],
  :host(:not([open])) slot[name='collapse-icon'] {
    display: none;
  }

  .body.animating {
    overflow: hidden;
  }

  .content {
    display: block;
    box-sizing: border-box; /* Ensure contents don't overflow */
    padding-block-start: var(--spacing);
    padding-inline: var(--spacing); /* Add horizontal padding */
    padding-block-end: var(--spacing); /* Add bottom padding */
  }
`;var E=class extends Event{constructor(){super("wa-show",{bubbles:!0,cancelable:!0,composed:!0})}};var A=class extends Event{constructor(e){super("wa-hide",{bubbles:!0,cancelable:!0,composed:!0}),this.detail=e}};var k=class extends Event{constructor(){super("wa-after-hide",{bubbles:!0,cancelable:!1,composed:!0})}};var S=class extends Event{constructor(){super("wa-after-show",{bubbles:!0,cancelable:!1,composed:!0})}};function d(e,a){return new Promise(r=>{function i(l){l.target===e&&(e.removeEventListener(a,i),r())}e.addEventListener(a,i)})}async function c(e,a,r){return e.animate(a,r).finished.catch(()=>{})}function p(e){return e=e.toString().toLowerCase(),e.indexOf("ms")>-1?parseFloat(e)||0:e.indexOf("s")>-1?(parseFloat(e)||0)*1e3:parseFloat(e)||0}var t=class extends b{constructor(){super(...arguments),this.localize=new v(this),this.animationGeneration=0,this.isAnimating=!1,this.open=!1,this.disabled=!1,this.appearance="outlined",this.iconPlacement="end"}disconnectedCallback(){super.disconnectedCallback(),this.detailsObserver?.disconnect()}firstUpdated(e){super.firstUpdated(e),this.body.style.height=this.open?"auto":"0",this.open&&(this.details.open=!0),this.detailsObserver=new MutationObserver(a=>{for(let r of a)r.type==="attributes"&&r.attributeName==="open"&&(this.details.open?this.show():this.hide())}),this.detailsObserver.observe(this.details,{attributes:!0})}updated(e){e.has("isAnimating")&&this.customStates.set("animating",this.isAnimating)}handleSummaryClick(e){e.composedPath().some(i=>{if(!(i instanceof HTMLElement))return!1;let l=i.tagName?.toLowerCase();return["a","button","input","textarea","select"].includes(l)?!0:i instanceof w?!("disabled"in i)||!i.disabled:!1})||(e.preventDefault(),this.disabled||(this.open?this.hide():this.show(),this.header.focus()))}handleSummaryKeyDown(e){(e.key==="Enter"||e.key===" ")&&(e.preventDefault(),this.open?this.hide():this.show()),(e.key==="ArrowUp"||e.key==="ArrowLeft")&&(e.preventDefault(),this.hide()),(e.key==="ArrowDown"||e.key==="ArrowRight")&&(e.preventDefault(),this.show())}closeOthersWithSameName(){if(!this.name)return;this.getRootNode().querySelectorAll(`wa-details[name="${this.name}"]`).forEach(r=>{r!==this&&r.open&&(r.open=!1)})}async handleOpenChange(){this.animationGeneration++;let e=this.animationGeneration;if(this.open){this.details.open=!0;let a=new E;if(this.dispatchEvent(a),a.defaultPrevented){this.open=!1,this.details.open=!1;return}this.closeOthersWithSameName(),this.isAnimating=!0;let r=p(getComputedStyle(this.body).getPropertyValue("--show-duration"));if(await c(this.body,[{height:"0",opacity:"0"},{height:`${this.body.scrollHeight}px`,opacity:"1"}],{duration:r,easing:"linear"}),this.animationGeneration!==e)return;this.body.style.height="auto",this.isAnimating=!1,this.dispatchEvent(new S)}else{let a=new A;if(this.dispatchEvent(a),a.defaultPrevented){this.details.open=!0,this.open=!0;return}this.isAnimating=!0;let r=p(getComputedStyle(this.body).getPropertyValue("--hide-duration"));if(await c(this.body,[{height:`${this.body.scrollHeight}px`,opacity:"1"},{height:"0",opacity:"0"}],{duration:r,easing:"linear"}),this.animationGeneration!==e)return;this.body.style.height="0",this.isAnimating=!1,this.details.open=!1,this.dispatchEvent(new k)}}async show(){if(!(this.open||this.disabled))return this.open=!0,d(this,"wa-after-show")}async hide(){if(!(!this.open||this.disabled))return this.open=!1,d(this,"wa-after-hide")}render(){let e=this.hasUpdated?this.localize.dir()==="rtl":this.dir==="rtl";return u`
      <details part="base details">
        <summary
          part="header"
          role="button"
          aria-expanded=${this.open?"true":"false"}
          aria-controls="content"
          aria-disabled=${this.disabled?"true":"false"}
          tabindex=${this.disabled?"-1":"0"}
          @click=${this.handleSummaryClick}
          @keydown=${this.handleSummaryKeyDown}
        >
          <slot name="summary" part="summary">${this.summary}</slot>

          <span part="icon">
            <slot name="expand-icon">
              <wa-icon library="system" variant="solid" name=${e?"chevron-left":"chevron-right"}></wa-icon>
            </slot>
            <slot name="collapse-icon">
              <wa-icon library="system" variant="solid" name=${e?"chevron-left":"chevron-right"}></wa-icon>
            </slot>
          </span>
        </summary>

        <div
          class=${g({body:!0,animating:this.isAnimating})}
          role="region"
          aria-labelledby="header"
        >
          <slot part="content" id="content" class="content"></slot>
        </div>
      </details>
    `}};t.css=x;o([n("details")],t.prototype,"details",2);o([n("summary")],t.prototype,"header",2);o([n(".body")],t.prototype,"body",2);o([n(".expand-icon-slot")],t.prototype,"expandIconSlot",2);o([f()],t.prototype,"isAnimating",2);o([s({type:Boolean,reflect:!0})],t.prototype,"open",2);o([s()],t.prototype,"summary",2);o([s({reflect:!0})],t.prototype,"name",2);o([s({type:Boolean,reflect:!0})],t.prototype,"disabled",2);o([s({reflect:!0})],t.prototype,"appearance",2);o([s({attribute:"icon-placement",reflect:!0})],t.prototype,"iconPlacement",2);o([y("open",{waitUntilFirstUpdate:!0})],t.prototype,"handleOpenChange",1);t=o([m("wa-details")],t);
/*! For license information please see details.js.LEGAL.txt */
