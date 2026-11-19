import{a}from"./chunk-6SSNEIVZ.js";import{a as m}from"./chunk-465Q73BL.js";import"./chunk-SDNW3ZYT.js";import{a as s,b as l,h as e,i as c,j as t,k as n,l as d,m as p}from"./chunk-77KF3W73.js";var u=s`
  :host {
    --size: 8rem;
    --track-width: 0.25em; /* avoid using rems here */
    --track-color: var(--wa-color-neutral-fill-normal);
    --indicator-width: var(--track-width);
    --indicator-color: var(--wa-color-brand-fill-loud);
    --indicator-transition-duration: 0.35s;

    display: inline-flex;
  }

  .progress-ring {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }

  .image {
    width: var(--size);
    height: var(--size);
    rotate: -90deg;
    transform-origin: 50% 50%;
  }

  .track,
  .indicator {
    --radius: calc(var(--size) / 2 - max(var(--track-width), var(--indicator-width)) * 0.5);
    --circumference: calc(var(--radius) * 2 * 3.141592654);

    fill: none;
    r: var(--radius);
    cx: calc(var(--size) / 2);
    cy: calc(var(--size) / 2);
  }

  .track {
    stroke: var(--track-color);
    stroke-width: var(--track-width);
  }

  .indicator {
    stroke: var(--indicator-color);
    stroke-width: var(--indicator-width);
    stroke-linecap: round;
    transition-property: stroke-dashoffset;
    transition-duration: var(--indicator-transition-duration);
    stroke-dasharray: var(--circumference) var(--circumference);
    stroke-dashoffset: calc(var(--circumference) - var(--percentage) * var(--circumference));
  }

  .label {
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    text-align: center;
    user-select: none;
    -webkit-user-select: none;
  }
`;var r=class extends p{constructor(){super(...arguments),this.localize=new m(this),this.value=0,this.label=""}updated(i){if(super.updated(i),i.has("value")){let f=parseFloat(getComputedStyle(this.indicator).getPropertyValue("r")),o=2*Math.PI*f,v=o-this.value/100*o;this.indicatorOffset=`${v}px`}}render(){return l`
      <div
        part="base progress-ring"
        class="progress-ring"
        role="progressbar"
        aria-label=${this.label.length>0?this.label:this.localize.term("progress")}
        aria-describedby="label"
        aria-valuemin="0"
        aria-valuemax="100"
        aria-valuenow="${this.value}"
        style=${a({"--percentage":this.value/100})}
      >
        <svg class="image">
          <circle part="track" class="track"></circle>
          <circle
            part="indicator"
            class="indicator"
            style=${a({"stroke-dashoffset":this.indicatorOffset})}
          ></circle>
        </svg>

        <slot id="label" part="label" class="label"></slot>
      </div>
    `}};r.css=u;e([d(".indicator")],r.prototype,"indicator",2);e([n()],r.prototype,"indicatorOffset",2);e([t({type:Number,reflect:!0})],r.prototype,"value",2);e([t()],r.prototype,"label",2);r=e([c("wa-progress-ring")],r);r.disableWarning?.("change-in-update");export{r as default};
/*! For license information please see progress-ring-SZMMHABJ.js.LEGAL.txt */
