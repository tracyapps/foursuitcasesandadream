import{a as p}from"./chunk-2MYMDAOT.js";import{a as h}from"./chunk-VJVG245B.js";import{a as s,b as r,h as e,i as l,j as a,k as n,m}from"./chunk-77KF3W73.js";var c=s`
  :host {
    --size: 3rem;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: relative;
    width: var(--size);
    height: var(--size);
    color: var(--wa-color-neutral-on-normal);
    font: inherit;
    font-size: calc(var(--size) * 0.4);
    vertical-align: middle;
    background-color: var(--wa-color-neutral-fill-normal);
    border-radius: var(--wa-border-radius-circle);
    user-select: none;
    -webkit-user-select: none;
  }

  :host([shape='square']) {
    border-radius: 0;
  }

  :host([shape='rounded']) {
    border-radius: var(--wa-border-radius-m);
  }

  .icon {
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }

  .initials {
    line-height: 1;
    text-transform: uppercase;
  }

  .image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    overflow: hidden;
    border-radius: inherit;
  }
`;var t=class extends m{constructor(){super(...arguments),this.hasError=!1,this.image="",this.label="",this.initials="",this.loading="eager",this.shape="circle"}handleImageChange(){this.hasError=!1}handleImageLoadError(){this.hasError=!0,this.dispatchEvent(new p)}connectedCallback(){if(super.connectedCallback(),this.didSSR){let i=this.shadowRoot?.querySelector?.("img");i&&i.complete&&i.naturalWidth<=0&&this.updateComplete.then(()=>{this.handleImageLoadError()})}}render(){let i=r`
      <img
        part="image"
        class="image"
        src="${this.image}"
        loading="${this.loading}"
        role="img"
        aria-label=${this.label}
        @error="${this.handleImageLoadError}"
      />
    `,o=r``;return this.initials?o=r`<div part="initials" class="initials" role="img" aria-label=${this.label}>
        ${this.initials}
      </div>`:o=r`
        <slot name="icon" part="icon" class="icon" role="img" aria-label=${this.label}>
          <wa-icon name="user" library="system" variant="solid"></wa-icon>
        </slot>
      `,r` ${this.image&&!this.hasError?i:o} `}};t.css=c;e([n()],t.prototype,"hasError",2);e([a()],t.prototype,"image",2);e([a()],t.prototype,"label",2);e([a()],t.prototype,"initials",2);e([a()],t.prototype,"loading",2);e([a({reflect:!0})],t.prototype,"shape",2);e([h("image")],t.prototype,"handleImageChange",1);t=e([l("wa-avatar")],t);export{t as default};
/*! For license information please see avatar-LKV5B56Z.js.LEGAL.txt */
