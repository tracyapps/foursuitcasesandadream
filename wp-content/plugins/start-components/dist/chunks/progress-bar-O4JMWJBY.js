import{a as o}from"./chunk-U2Z6UIVF.js";import{a as c}from"./chunk-A6F2TFV3.js";import{a as p}from"./chunk-465Q73BL.js";import{a as l,b as i,h as r,i as s,j as a,m as n}from"./chunk-77KF3W73.js";var d=l`
  :host {
    --track-height: 1rem;
    --track-color: var(--wa-color-neutral-fill-normal);
    --indicator-color: var(--wa-color-brand-fill-loud);

    display: flex;
  }

  .progress-bar {
    flex: 1 1 auto;
    display: flex;
    position: relative;
    overflow: hidden;
    height: var(--track-height);
    border-radius: var(--wa-border-radius-pill);
    background-color: var(--track-color);
    color: var(--wa-color-brand-on-loud);
    font-size: var(--wa-font-size-s);
  }

  .indicator {
    width: var(--percentage);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--indicator-color);
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    line-height: 1;
    font-weight: var(--wa-font-weight-semibold);
    transition: all var(--wa-transition-slow, 200ms) var(--wa-transition-easing, ease);
    user-select: none;
    -webkit-user-select: none;
  }

  /* Indeterminate */
  :host([indeterminate]) .indicator {
    position: absolute;
    inset-block: 0;
    inline-size: 50%;
    animation: wa-progress-indeterminate 2.5s infinite cubic-bezier(0.37, 0, 0.63, 1);
  }

  @media (forced-colors: active) {
    .progress-bar {
      outline: solid 1px SelectedItem;
      background-color: var(--wa-color-surface-default);
    }

    .indicator {
      outline: solid 1px SelectedItem;
      background-color: SelectedItem;
    }
  }

  @keyframes wa-progress-indeterminate {
    0% {
      inset-inline-start: -50%;
    }

    75%,
    100% {
      inset-inline-start: 100%;
    }
  }
`;var e=class extends n{constructor(){super(...arguments),this.localize=new p(this),this.value=0,this.indeterminate=!1,this.label=""}willUpdate(t){this.style==null&&this.setStyleProperty("--percentage",`${o(this.value,0,100)}%`),super.willUpdate(t)}updated(t){t.has("value")&&requestAnimationFrame(()=>{this.style.setProperty("--percentage",`${o(this.value,0,100)}%`)}),super.updated(t)}render(){return i`
      <div
        part="base progress-bar"
        class="progress-bar"
        role="progressbar"
        title=${c(this.title)}
        aria-label=${this.label.length>0?this.label:this.localize.term("progress")}
        aria-valuemin="0"
        aria-valuemax="100"
        aria-valuenow=${this.indeterminate?"0":this.value}
      >
        <div part="indicator" class="indicator">
          ${this.indeterminate?"":i` <slot part="label" class="label"></slot> `}
        </div>
      </div>
    `}};e.css=d;r([a({type:Number,reflect:!0})],e.prototype,"value",2);r([a({type:Boolean,reflect:!0})],e.prototype,"indeterminate",2);r([a()],e.prototype,"label",2);e=r([s("wa-progress-bar")],e);export{e as default};
/*! For license information please see progress-bar-O4JMWJBY.js.LEGAL.txt */
