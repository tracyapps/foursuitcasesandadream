import{a as V}from"./chunk-U2Z6UIVF.js";import{a as c}from"./chunk-6SSNEIVZ.js";import{a as p}from"./chunk-KJFNBS3U.js";import{b as f}from"./chunk-EJO2UXAP.js";import{a as w}from"./chunk-2ZEB42LG.js";import{a as g}from"./chunk-465Q73BL.js";import"./chunk-2MYMDAOT.js";import{a as d}from"./chunk-VJVG245B.js";import{a as u}from"./chunk-QOLYX4MT.js";import{a as E}from"./chunk-K6YBMDHO.js";import"./chunk-SDNW3ZYT.js";import{a as y,b as h,h as r,i as b,j as l,k as m}from"./chunk-77KF3W73.js";var v=class extends Event{constructor(e){super("wa-hover",{bubbles:!0,cancelable:!1,composed:!0}),this.detail=e}};var P=y`
  :host {
    --symbol-color: var(--wa-color-neutral-on-quiet);
    --symbol-color-active: var(--wa-color-yellow-70);
    --symbol-spacing: 0.125em;

    display: inline-flex;
    border-radius: var(--wa-border-radius-m);
    vertical-align: middle;
    touch-action: none;
  }

  :host(:focus) {
    outline: none;
  }

  :host(:focus-visible) {
    outline: var(--wa-focus-ring);
    outline-offset: var(--wa-focus-ring-offset);
  }

  .rating {
    position: relative;
    display: inline-flex;
  }

  .symbols {
    display: inline-flex;
    gap: 0.125em;
    position: relative;
    line-height: 0;
    color: var(--symbol-color);
    white-space: nowrap;
    cursor: pointer;
  }

  .symbols > * {
    padding: var(--symbol-spacing);
  }

  .symbol-active,
  .partial-filled {
    color: var(--symbol-color-active);
  }

  .partial-symbol-container {
    position: relative;
  }

  .partial-filled {
    position: absolute;
    top: var(--symbol-spacing);
    left: var(--symbol-spacing);
  }

  .symbol {
    transition: scale var(--wa-transition-normal) var(--wa-transition-easing);
    pointer-events: none;
  }

  .symbol-hover {
    scale: 1.2;
  }

  .rating-readonly .symbols {
    cursor: default;
  }

  :host([disabled]) .symbol-hover,
  .rating-readonly .symbol-hover {
    scale: none;
  }

  :host([disabled]) {
    opacity: 0.5;
  }

  :host([disabled]) .symbols {
    cursor: not-allowed;
  }

  /* Forced colors mode */
  @media (forced-colors: active) {
    .symbol-active {
      color: SelectedItem;
    }
  }
`;var C=(e={})=>{let{validationElement:a,validationProperty:t}=e;a||typeof document<"u"&&"createElement"in document&&(a=Object.assign(document.createElement("input"),{required:!0})),t||(t="value");let i={observedAttributes:["required"],message:a?.validationMessage,checkValidity(o){let n={message:"",isValid:!0,invalidKeys:[]};return(o.required??o.hasAttribute("required"))&&!o[t]&&(n.message=typeof i.message=="function"?i.message(o):i.message||"",n.isValid=!1,n.invalidKeys.push("valueMissing")),n}};return i};var s=class extends f{constructor(){super(...arguments),this.assumeInteractionOn=["change"],this.localize=new g(this),this.role="slider",this.hoverValue=0,this.isHovering=!1,this.name=null,this.label="",this.value=0,this.defaultValue=0,this.max=5,this.precision=1,this.readonly=!1,this.required=!1,this.getSymbol=(e,a)=>a?'<wa-icon name="star" library="system" variant="solid"></wa-icon>':'<wa-icon name="star" library="system" variant="regular"></wa-icon>',this.size="m",this.handleClick=e=>{this.disabled||(this.setRatingValue(this.getValueFromXCoordinate(e.clientX)),this.updateComplete.then(()=>{this.dispatchEvent(new Event("change",{bubbles:!0,composed:!0}))}))},this.handleKeyDown=e=>{let a=this.matches(":dir(ltr)"),t=this.localize.dir()==="rtl",i=this.value;if(!(this.disabled||this.readonly)){if(e.key==="ArrowDown"||a&&e.key==="ArrowLeft"||t&&e.key==="ArrowRight"){let o=e.shiftKey?1:this.precision;this.value=Math.max(0,this.value-o),e.preventDefault()}if(e.key==="ArrowUp"||a&&e.key==="ArrowRight"||t&&e.key==="ArrowLeft"){let o=e.shiftKey?1:this.precision;this.value=Math.min(this.max,this.value+o),e.preventDefault()}e.key==="Home"&&(this.value=0,e.preventDefault()),e.key==="End"&&(this.value=this.max,e.preventDefault()),this.value!==i&&this.updateComplete.then(()=>{this.dispatchEvent(new Event("change",{bubbles:!0,composed:!0}))})}},this.handlePointerEnter=e=>{this.isHovering=!0,this.hoverValue=this.getValueFromPointerPosition(e)},this.handlePointerMove=e=>{this.hoverValue=this.getValueFromPointerPosition(e)},this.handlePointerLeave=()=>{this.isHovering=!1},this.handlePointerDown=e=>{e.button===0&&(this.isHovering=!0,this.hoverValue=this.getValueFromPointerPosition(e),this.setPointerCapture(e.pointerId),e.preventDefault())},this.handlePointerUp=e=>{this.releasePointerCapture(e.pointerId),this.isHovering=!1}}static get validators(){return[...super.validators,C()]}connectedCallback(){super.connectedCallback(),this.setAttribute("aria-valuenow",String(this.value)),this.setAttribute("aria-valuemin","0"),this.setAttribute("aria-valuemax",String(this.max)),this.setAttribute("aria-disabled",this.disabled?"true":"false"),this.setAttribute("aria-readonly",this.readonly?"true":"false"),this.label&&this.setAttribute("aria-label",this.label),!this.disabled&&!this.readonly?this.tabIndex=0:this.tabIndex=-1,this.addEventListener("click",this.handleClick),this.addEventListener("keydown",this.handleKeyDown),this.addEventListener("pointerenter",this.handlePointerEnter),this.addEventListener("pointermove",this.handlePointerMove),this.addEventListener("pointerleave",this.handlePointerLeave),this.addEventListener("pointerdown",this.handlePointerDown),this.addEventListener("pointerup",this.handlePointerUp)}disconnectedCallback(){super.disconnectedCallback(),this.removeEventListener("click",this.handleClick),this.removeEventListener("keydown",this.handleKeyDown),this.removeEventListener("pointerenter",this.handlePointerEnter),this.removeEventListener("pointermove",this.handlePointerMove),this.removeEventListener("pointerleave",this.handlePointerLeave),this.removeEventListener("pointerdown",this.handlePointerDown),this.removeEventListener("pointerup",this.handlePointerUp)}updated(e){super.updated(e),e.has("value")&&this.setAttribute("aria-valuenow",String(this.value)),e.has("max")&&this.setAttribute("aria-valuemax",String(this.max)),e.has("disabled")&&(this.setAttribute("aria-disabled",this.disabled?"true":"false"),this.tabIndex=this.disabled||this.readonly?-1:0),e.has("readonly")&&(this.setAttribute("aria-readonly",this.readonly?"true":"false"),this.tabIndex=this.disabled||this.readonly?-1:0),e.has("label")&&(this.label?this.setAttribute("aria-label",this.label):this.removeAttribute("aria-label"))}handleSizeChange(){w(this.localName,this.size)}getValueFromPointerPosition(e){return this.getValueFromXCoordinate(e.clientX)}getValueFromXCoordinate(e){let a=this.localize.dir()==="rtl",{left:t,right:i,width:o}=this.getBoundingClientRect(),n=a?this.roundToPrecision((i-e)/o*this.max,this.precision):this.roundToPrecision((e-t)/o*this.max,this.precision);return V(n,0,this.max)}setRatingValue(e){this.disabled||this.readonly||(this.value=e===this.value?0:e,this.isHovering=!1)}roundToPrecision(e,a=.5){let t=1/a;return Math.ceil(e*t)/t}handleHoverValueChange(){this.dispatchEvent(new v({phase:"move",value:this.hoverValue}))}handleIsHoveringChange(){this.dispatchEvent(new v({phase:this.isHovering?"start":"end",value:this.hoverValue}))}formResetCallback(){this.value=this.defaultValue,super.formResetCallback()}render(){let e=this.didSSR&&!this.hasUpdated?this.dir:this.localize.dir()==="rtl",a=Array.from(Array(this.max).keys()),t=0;return this.disabled||this.readonly?t=this.value:t=this.isHovering?this.hoverValue:this.value,h`
      <div
        part="base rating"
        class=${u({rating:!0,"rating-readonly":this.readonly,"rating-disabled":this.disabled})}
      >
        <span class="symbols">
          ${a.map(i=>{let o=t>=i+1;return t>i&&t<i+1?h`
                <span
                  class=${u({symbol:!0,"partial-symbol-container":!0,"symbol-hover":this.isHovering&&Math.ceil(t)===i+1})}
                  role="presentation"
                >
                  <div
                    style=${c({clipPath:e?`inset(0 ${(t-i)*100}% 0 0)`:`inset(0 0 0 ${(t-i)*100}%)`})}
                  >
                    ${p(this.getSymbol(i+1,!1))}
                  </div>
                  <div
                    class="partial-filled"
                    style=${c({clipPath:e?`inset(0 0 0 ${100-(t-i)*100}%)`:`inset(0 ${100-(t-i)*100}% 0 0)`})}
                  >
                    ${p(this.getSymbol(i+1,!0))}
                  </div>
                </span>
              `:h`
              <span
                class=${u({symbol:!0,"symbol-hover":this.isHovering&&Math.ceil(t)===i+1,"symbol-active":t>=i+1})}
                role="presentation"
              >
                ${p(this.getSymbol(i+1,o))}
              </span>
            `})}
        </span>
      </div>
    `}};s.css=[E,P];r([l({reflect:!0})],s.prototype,"role",2);r([m()],s.prototype,"hoverValue",2);r([m()],s.prototype,"isHovering",2);r([l()],s.prototype,"name",2);r([l()],s.prototype,"label",2);r([l({type:Number})],s.prototype,"value",2);r([l({type:Number,attribute:"default-value"})],s.prototype,"defaultValue",2);r([l({type:Number})],s.prototype,"max",2);r([l({type:Number})],s.prototype,"precision",2);r([l({type:Boolean,reflect:!0})],s.prototype,"readonly",2);r([l({type:Boolean})],s.prototype,"disabled",2);r([l({type:Boolean,reflect:!0})],s.prototype,"required",2);r([l()],s.prototype,"getSymbol",2);r([l({reflect:!0})],s.prototype,"size",2);r([d("size")],s.prototype,"handleSizeChange",1);r([d("hoverValue")],s.prototype,"handleHoverValueChange",1);r([d("isHovering")],s.prototype,"handleIsHoveringChange",1);s=r([b("wa-rating")],s);export{s as default};
/*! For license information please see rating-FPSBHPKK.js.LEGAL.txt */
