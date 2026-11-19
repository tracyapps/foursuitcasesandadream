import{a as j}from"./chunks/chunk-KJFNBS3U.js";import"./chunks/chunk-ZZDYADJZ.js";import{a as S}from"./chunks/chunk-A6F2TFV3.js";import{a as H}from"./chunks/chunk-2ZEB42LG.js";import{a as K}from"./chunks/chunk-465Q73BL.js";import"./chunks/chunk-2MYMDAOT.js";import{a as P}from"./chunks/chunk-VJVG245B.js";import{a as B}from"./chunks/chunk-K6YBMDHO.js";import"./chunks/chunk-SDNW3ZYT.js";import{a as _,b as k,f as I,h as c,i as O,j as f,k as $,l as L,m as U}from"./chunks/chunk-77KF3W73.js";var J=class extends Event{constructor(e){super("wa-view-change",{bubbles:!0,cancelable:!1,composed:!0}),this.detail=e}};var W=class extends Event{constructor(e){super("wa-focus-day",{bubbles:!0,cancelable:!1,composed:!0}),this.detail=e}};var oe=/^(\d{4})-(\d{2})-(\d{2})$/;function u(e){if(!e)return null;let t=oe.exec(e.trim());if(!t)return null;let r=Number(t[1]),a=Number(t[2]),s=Number(t[3]);if(a<1||a>12||s<1||s>31)return null;let i=new Date(r,a-1,s);return i.getFullYear()!==r||i.getMonth()!==a-1||i.getDate()!==s?null:i}function x(e){if(!e||isNaN(e.getTime()))return"";let t=String(e.getFullYear()).padStart(4,"0"),r=String(e.getMonth()+1).padStart(2,"0"),a=String(e.getDate()).padStart(2,"0");return`${t}-${r}-${a}`}function V(e){return e==null?null:e instanceof Date?isNaN(e.getTime())?null:new Date(e.getFullYear(),e.getMonth(),e.getDate()):u(e)}function M(e){if(!e)return{from:null,to:null};let t=e.split("/");if(t.length===1)return{from:u(t[0]),to:null};let r=u(t[0]),a=u(t[1]);return!r||!a?{from:r,to:a}:r.getTime()<=a.getTime()?{from:r,to:a}:{from:a,to:r}}function C(e){if(!e)return"";let{from:t,to:r}=e;return!t&&!r?"":t&&!r?x(t):!t&&r?x(r):`${x(t)}/${x(r)}`}function F(e,t){return!e||!t?!1:e.getFullYear()===t.getFullYear()&&e.getMonth()===t.getMonth()&&e.getDate()===t.getDate()}function G(e,t){return e.getFullYear()===t.getFullYear()&&e.getMonth()===t.getMonth()}function T(e,t){return new Date(e.getFullYear(),e.getMonth(),e.getDate()+t)}function E(e,t){let r=new Date(e.getFullYear(),e.getMonth()+t,1),a=z(r.getFullYear(),r.getMonth());return new Date(r.getFullYear(),r.getMonth(),Math.min(e.getDate(),a))}function R(e,t){return E(e,t*12)}function z(e,t){return new Date(e,t+1,0).getDate()}function Z(){let e=new Date;return new Date(e.getFullYear(),e.getMonth(),e.getDate())}function A(e,t,r){return t&&e.getTime()<t.getTime()?t:r&&e.getTime()>r.getTime()?r:e}function X(e){let{min:t,max:r,disabledDates:a,disabledDaysOfWeek:s,disablePast:i,disableFuture:n,today:o,isDateDisabled:l}=e,d=t?.getTime()??-1/0,g=r?.getTime()??1/0,w=o.getTime(),y=new Set(s),m=new Set(a.map(v=>v.getTime()));return function(p){let D=p.getTime();return!!(D<d||D>g||i&&D<w||n&&D>w||y.size&&y.has(p.getDay())||m.size&&m.has(D)||l&&l(p))}}function ee(e){if(e==null||e==="")return[];let t=Array.isArray(e)?e:e.split(/\s+/),r=[];for(let a of t){if(a instanceof Date){isNaN(a.getTime())||r.push(new Date(a.getFullYear(),a.getMonth(),a.getDate()));continue}let s=u(String(a).trim());s&&r.push(s)}return r}var Q={sun:0,mon:1,tue:2,wed:3,thu:4,fri:5,sat:6};function te(e){if(e==null||e==="")return[];let t=String(e).toLowerCase().split(/\s+/).filter(Boolean),r=new Set;for(let a of t)a in Q&&r.add(Q[a]);return[...r]}var ne=new Set(["US","CA","MX","BR","JP","PH","IL","AU","NZ","ZA","CO","VE","PE","EC","GT","HN","NI","SV","CR","PA","DO","PR","JM","TT","BS","BB","BZ","BO","BM","TW","HK","MO","SG","TH","ET","KE"]),le=new Set(["SA","AE","QA","KW","BH","OM","YE","JO","SY","IQ","EG","SD","DZ","LY"]),de=new Set(["SA","AE","QA","KW","BH","OM","YE","JO","EG","SD","DZ","LY","SY","IQ","IL"]);function he(e){try{return new Intl.Locale(e).maximize().region??null}catch{return null}}function ce(e){let t=he(e),r=1;t&&ne.has(t)?r=7:t&&le.has(t)&&(r=6);let a=t&&de.has(t)?[5,6]:[6,7];return{firstDay:r,weekend:a}}function N(e){try{let t=new Intl.Locale(e),r=typeof t.getWeekInfo=="function"?t.getWeekInfo():t.weekInfo;if(r&&typeof r.firstDay=="number"&&Array.isArray(r.weekend))return{firstDay:r.firstDay,weekend:r.weekend}}catch{}return ce(e)}function q(e){return e===7?0:e}function re(e){return e.map(q)}var ae=_`
  :host {
    /* @internal Base size of a single cell */
    --cell-size: 2.5em;

    /* @internal Height of one day-grid row: the day button's min-height plus its cell padding. The weekday header row
       is pinned to the same height below. */
    --row-height: calc(var(--cell-size) + 2 * var(--wa-space-3xs));

    /* @internal Intrinsic height of the day grid: 1 weekday header row + 6 week rows. The months and years views are
       pinned to this so switching views never shifts the calendar's height. */
    --grid-height: calc(7 * var(--row-height));

    display: block;
    color: var(--wa-color-text-normal);
    font-family: var(--wa-font-family-body);
    line-height: var(--wa-line-height-normal);
    container-type: inline-size;
    container-name: date-picker;
  }

  :host([disabled]) {
    pointer-events: none;
    opacity: 0.5;
  }

  [part~='base'] {
    display: flex;
    flex-direction: column;
    gap: var(--wa-space-s);
    padding: var(--wa-space-s);
    background-color: var(--wa-color-surface-default);
    border: var(--wa-border-width-s) var(--wa-border-style) var(--wa-color-surface-border);
    border-radius: var(--wa-border-radius-m);
    user-select: none;
    -webkit-user-select: none;
  }

  /* Header */
  [part~='header'] {
    display: flex;
    align-items: center;
    gap: var(--wa-space-2xs);
  }

  [part~='title'] {
    flex: 1 1 auto;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.25em;
    background: transparent;
    border: none;
    border-radius: var(--wa-form-control-border-radius);
    padding: var(--wa-space-2xs) var(--wa-space-s);
    color: inherit;
    font: inherit;
    font-weight: var(--wa-font-weight-semibold);
    cursor: pointer;
    text-align: center;
    min-height: var(--cell-size);
  }

  [part~='title']:hover {
    background-color: var(--wa-color-neutral-fill-quiet);
  }

  [part~='title']:focus-visible {
    outline: var(--wa-focus-ring);
    outline-offset: var(--wa-focus-ring-offset);
  }

  [part~='nav'] {
    display: inline-flex;
    gap: var(--wa-space-2xs);
  }

  [part~='previous'],
  [part~='next'] {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: var(--wa-form-control-border-radius);
    padding: 0;
    color: inherit;
    cursor: pointer;
    width: var(--cell-size);
    height: var(--cell-size);
    font-size: inherit;
  }

  [part~='previous']:hover:not(:disabled),
  [part~='next']:hover:not(:disabled) {
    background-color: var(--wa-color-neutral-fill-quiet);
  }

  [part~='previous']:focus-visible,
  [part~='next']:focus-visible {
    outline: var(--wa-focus-ring);
    outline-offset: var(--wa-focus-ring-offset);
  }

  [part~='previous']:disabled,
  [part~='next']:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }

  /* Months container */
  [part~='months'] {
    display: flex;
    gap: var(--wa-space-m);
    width: 100%;
  }

  /* When the calendar isn't wide enough to fit two months side-by-side, stack them vertically so each
     month still has room to render comfortably. */
  @container date-picker (max-width: 32em) {
    [part~='months'] {
      flex-direction: column;
    }
  }

  [part~='month'] {
    display: flex;
    flex-direction: column;
    gap: var(--wa-space-2xs);
    flex: 1 1 0;
    min-width: 0;
  }

  [part~='month-label'] {
    text-align: center;
    font-weight: var(--wa-font-weight-semibold);
    font-size: 0.875em;
    color: var(--wa-color-text-quiet);
  }

  /* Day grid. Pinned to --grid-height (the same height the months/years view uses) so the calendar never shifts when
     switching views. A table given an explicit height distributes it across its rows; the row heights below keep the
     distribution predictable. */
  [part~='grid'] {
    border-collapse: collapse;
    table-layout: fixed;
    width: 100%;
    height: var(--grid-height);
  }

  [part~='grid'] th,
  [part~='grid'] td {
    padding: var(--wa-space-3xs) 0;
    text-align: center;
  }

  [part~='weekday'] {
    /* Pinned to the same row height as a day row so the grid is a predictable 7 × --row-height. */
    height: var(--row-height);
    box-sizing: border-box;
    font-size: 0.75em;
    font-weight: var(--wa-font-weight-normal);
    color: var(--wa-color-text-quiet);
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  [part~='weeknumber'] {
    font-size: 0.75em;
    font-weight: var(--wa-font-weight-normal);
    color: var(--wa-color-text-quiet);
    padding-inline-end: var(--wa-space-2xs) !important;
    border-inline-end: var(--wa-border-width-s) var(--wa-border-style) var(--wa-color-surface-border);
  }

  /* Day-cell placeholder used by trailing rows so the grid is always 6 rows tall. Matches the day button's vertical
     footprint so the overall calendar height never shifts. */
  [part~='day-placeholder'] {
    display: block;
    min-height: var(--cell-size);
  }

  /* Day cell button */
  [part~='day'] {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: var(--cell-size);
    background: transparent;
    border: none;
    border-radius: var(--wa-form-control-border-radius);
    color: inherit;
    font: inherit;
    font-size: inherit;
    cursor: pointer;
    padding: 0;
  }

  [part~='day']:hover:not([data-disabled]):not([data-out-of-reach]):not([data-selected]):not([data-range-start]):not(
      [data-range-end]
    ):not([data-range-inner]):not([data-range-preview]) {
    background-color: var(--wa-color-neutral-fill-quiet);
  }

  [part~='day'][data-out-of-reach] {
    cursor: not-allowed;
  }

  [part~='day']:focus-visible {
    outline: var(--wa-focus-ring);
    outline-offset: calc(-1 * var(--wa-focus-ring-width, 2px));
  }

  [part~='day'][data-today] {
    border: var(--wa-border-style) var(--wa-border-width-s) var(--wa-color-neutral-border-quiet);
  }

  [part~='day'][data-outside] {
    color: var(--wa-color-text-quiet);
    opacity: 0.6;
  }

  [part~='day'][data-disabled] {
    color: var(--wa-color-text-quiet);
    opacity: 0.4;
    cursor: not-allowed;
  }

  /* Selection styling */
  [part~='day'][data-selected],
  [part~='day'][data-range-start],
  [part~='day'][data-range-end] {
    background-color: var(--wa-color-brand-fill-loud);
    border: none;
    color: var(--wa-color-brand-on-loud);
  }

  [part~='day'][data-selected]:hover,
  [part~='day'][data-range-start]:hover,
  [part~='day'][data-range-end]:hover {
    background-color: var(--wa-color-brand-fill-loud);
  }

  /* Range middle + hover preview */
  [part~='day'][data-range-inner],
  [part~='day'][data-range-preview] {
    background-color: var(--wa-color-brand-fill-quiet);
    color: var(--wa-color-brand-on-quiet);
    border-radius: 0;
  }

  /* Interior range cells are square by default; the row-edge and endpoint rules below selectively restore radii. Each
     selector here is written with matching specificity so source order alone decides the winner — later rules (row
     edges, then endpoints) override earlier ones. */
  td[data-in-range] [part~='day'] {
    border-radius: 0;
  }

  td[data-in-range] [part~='day']:hover:not([data-selected]):not([data-disabled]) {
    background-color: var(--wa-color-brand-fill-normal);
  }

  /* Soften the range fill where it meets a row edge. When a range spans multiple weeks, the fill block ends abruptly at
     the first/last cell of a row, leaving sharp corners. Round the leading edge of a range cell that starts a row (or
     follows a non-range cell), and the trailing edge of one that ends a row (or precedes a non-range cell). The
     "th + td" selector covers the case where the week-numbers column shifts the first day cell off :first-child. */
  td[data-in-range]:first-child [part~='day'],
  th + td[data-in-range] [part~='day'],
  td:not([data-in-range]) + td[data-in-range] [part~='day'] {
    border-start-start-radius: var(--wa-form-control-border-radius);
    border-end-start-radius: var(--wa-form-control-border-radius);
  }

  td[data-in-range]:last-child [part~='day'],
  td[data-in-range]:has(+ td:not([data-in-range])) [part~='day'] {
    border-start-end-radius: var(--wa-form-control-border-radius);
    border-end-end-radius: var(--wa-form-control-border-radius);
  }

  /* Range endpoints: round the outward-facing side fully, square the side facing the range fill. These come last so
     they win over the row-edge rules above at the true endpoints. */
  td[data-range-start] [part~='day'] {
    border-start-start-radius: var(--wa-form-control-border-radius);
    border-end-start-radius: var(--wa-form-control-border-radius);
    border-start-end-radius: 0;
    border-end-end-radius: 0;
  }

  td[data-range-end] [part~='day'] {
    border-start-end-radius: var(--wa-form-control-border-radius);
    border-end-end-radius: var(--wa-form-control-border-radius);
    border-start-start-radius: 0;
    border-end-start-radius: 0;
  }

  /* Single-day range (start === end, e.g. hovering the anchor itself): restore full radius. */
  td[data-range-start][data-range-end] [part~='day'] {
    border-radius: var(--wa-form-control-border-radius);
  }

  /* Year / month grid view. Pinned to the day grid's height (--grid-height) and given equal auto-rows so its 4 rows
     stretch to fill that space — switching views never shifts height. min-height keeps it flexible if the grid's content
     ever needs more room. */
  [part~='view-grid'] {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-auto-rows: 1fr;
    gap: var(--wa-space-2xs);
    min-height: var(--grid-height);
  }

  /* The row and cell wrappers exist only to satisfy the ARIA grid structure (grid → row → gridcell);
     both stay transparent to layout so the view-item buttons remain direct CSS Grid items of view-grid. */
  [part~='view-row'],
  [part~='view-cell'] {
    display: contents;
  }

  [part~='view-item'] {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: var(--wa-form-control-border-radius);
    color: inherit;
    font: inherit;
    cursor: pointer;
    padding: var(--wa-space-s);
    min-height: 3em;
  }

  [part~='view-item']:hover:not([data-disabled]):not([data-selected]) {
    background-color: var(--wa-color-neutral-fill-quiet);
  }

  [part~='view-item'][data-selected]:hover {
    background-color: var(--wa-color-brand-fill-loud);
    color: var(--wa-color-brand-on-loud);
  }

  [part~='view-item']:focus-visible {
    outline: var(--wa-focus-ring);
    outline-offset: var(--wa-focus-ring-offset);
  }

  /* "Today" = the current month/year. Matches today's day in the day grid with a subtle, neutral border. */
  [part~='view-item'][data-today] {
    border: var(--wa-border-style) var(--wa-border-width-s) var(--wa-color-neutral-border-quiet);
  }

  [part~='view-item'][data-selected] {
    background-color: var(--wa-color-brand-fill-loud);
    color: var(--wa-color-brand-on-loud);
  }

  [part~='view-item'][data-disabled] {
    opacity: 0.4;
    cursor: not-allowed;
  }

  [part~='footer']:not(:empty) {
    display: flex;
    align-items: center;
    gap: var(--wa-space-s);
    padding-top: var(--wa-space-s);
    border-top: var(--wa-border-width-s) var(--wa-border-style) var(--wa-color-surface-border);
  }
`;var ie=_`
  .wa-visually-hidden:not(:focus-within),
  .wa-visually-hidden-force,
  .wa-visually-hidden-hint::part(hint),
  .wa-visually-hidden-label::part(label),
  .wa-visually-hidden-label::part(form-control-label) {
    top: 0px !important;
    left: 0px !important;
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    clip: rect(0 0 0 0) !important;
    clip-path: inset(50%) !important;
    border: none !important;
    overflow: hidden !important;
    white-space: nowrap !important;
    padding: 0 !important;
  }
`;var h=class extends U{constructor(){super(...arguments),this.localize=new K(this),this.mode="single",this._value="",this.min="",this.max="",this.today="",this.focusedDate="",this.view="days",this.months=1,this.pageBy="months",this.firstDayOfWeek="auto",this.withOutsideDays=!1,this.withWeekNumbers=!1,this.weekdayFormat="short",this.disabled=!1,this.readonly=!1,this._disabledDatesRaw="",this._disabledDates=[],this.disabledDaysOfWeek="",this.disablePast=!1,this.disableFuture=!1,this.minRange=0,this.maxRange=0,this.size="m",this.locale="",this.rangeAnchor=null,this.rangeCommittedValue="",this.hoverDate=null,this.viewAnchor=null,this.focusedMonth=null,this.focusedYear=null,this.liveAnnouncement="",this.handleGridMouseLeave=()=>{this.mode==="range"&&this.rangeAnchor&&(this.hoverDate=null)}}get value(){return this._value}set value(e){let t=this.normalizeValue(e);if(t===this._value)return;let r=this._value;this._value=t,this.requestUpdate("value",r)}get disabledDates(){return this._disabledDatesRaw}set disabledDates(e){this._disabledDatesRaw=e??"",this._disabledDates=ee(e),this.requestUpdate("disabledDates")}handleSizeChange(){H(this.localName,this.size)}connectedCallback(){super.connectedCallback(),this.updateCustomStates(),this.didSSR&&!this.hasUpdated&&this.updateComplete.then(()=>{this.requestUpdate()})}willUpdate(e){if(super.willUpdate(e),(e.has("disabled")||e.has("readonly")||e.has("mode"))&&this.updateCustomStates(),!this.viewAnchor){let t=this.resolvedFocusedDate();this.viewAnchor=new Date(t.getFullYear(),t.getMonth(),1)}}updateCustomStates(){this.customStates.set("disabled",this.disabled),this.customStates.set("readonly",this.readonly),this.customStates.set("range",this.mode==="range")}handleModeChange(e,t){if(this.rangeAnchor=null,this.hoverDate=null,this.rangeCommittedValue="",this.liveAnnouncement="",this.viewAnchor=null,t!==void 0){let r=this.normalizeValue(this._value);r!==this._value&&(this._value=r)}}focus(e){let t=this.base?.querySelector('[part~="day"][tabindex="0"]');t?t.focus(e):super.focus(e)}goToDate(e){let t=V(e);t&&(this.focusedDate=x(t),this.setViewAnchor(new Date(t.getFullYear(),t.getMonth(),1)))}goToToday(){this.goToDate(this.resolvedToday())}clear(){this.disabled||this.readonly||!this._value&&!this.rangeAnchor||(this._value="",this.rangeAnchor=null,this.hoverDate=null,this.rangeCommittedValue="",this.liveAnnouncement="",this.requestUpdate(),this.dispatchEvent(new InputEvent("input",{bubbles:!0,composed:!0})),this.dispatchEvent(new Event("change",{bubbles:!0,composed:!0})),this.announceSelection())}get valueAsDate(){return this.mode!=="single"?null:u(this._value)}get valueAsRange(){return this.mode!=="range"?{from:null,to:null}:M(this._value)}normalizeValue(e){if(e==null||e==="")return"";if(this.mode==="single"){if(e instanceof Date)return x(e);if(typeof e=="string"){let t=M(e).from;return t?x(t):""}return""}if(typeof e=="string"){let t=M(e);return C(t)}if(e instanceof Date)return x(e);if(typeof e=="object"){let t=e,r=V(t.from??null),a=V(t.to??null);return C({from:r,to:a})}return""}resolvedLocale(){return this.didSSR&&!this.hasUpdated?this.locale||this.lang||"en":this.locale||this.lang||(typeof document<"u"?document.documentElement.lang:"")||"en"}resolvedToday(){return u(this.today)??Z()}resolvedFirstDay(){let e={sun:0,mon:1,tue:2,wed:3,thu:4,fri:5,sat:6},t=String(this.firstDayOfWeek??"").toLowerCase();if(t in e)return e[t];if(this.didSSR&&!this.hasUpdated){let r=this.shadowRoot?.querySelector?.("[data-first-day]")?.getAttribute?.("data-first-day");if(r!=null)return Number(r)}return q(N(this.resolvedLocale()).firstDay)}resolvedWeekendDays(){if(this.didSSR&&!this.hasUpdated){let e=this.shadowRoot?.querySelector?.("[data-weekend-days]")?.getAttribute("data-weekend-days");if(e)return e.split(/\s+/).map(Number)}return re(N(this.resolvedLocale()).weekend)}resolvedFocusedDate(){let e=u(this.min),t=u(this.max),r=u(this.focusedDate);if(r)return A(r,e,t);if(this.mode==="single"){let a=u(this._value);if(a)return A(a,e,t)}else{let a=M(this._value);if(a.from)return A(a.from,e,t)}return A(this.resolvedToday(),e,t)}resolvedViewAnchor(){let e=u(this.min),t=u(this.max);return this.viewAnchor?A(this.viewAnchor,e,t):this.resolvedFocusedDate()}isInVisibleRange(e){let t=this.resolvedViewAnchor(),r=this.resolvedMonthCount(),a=new Date(t.getFullYear(),t.getMonth(),1),s=new Date(t.getFullYear(),t.getMonth()+r,0);return e.getTime()>=a.getTime()&&e.getTime()<=s.getTime()}resolvedRovingFocus(){let e=this.resolvedFocusedDate();if(this.isInVisibleRange(e))return e;let t=this.resolvedViewAnchor();return new Date(t.getFullYear(),t.getMonth(),1)}resolvedFocusedMonth(){return this.focusedMonth!==null?this.focusedMonth:this.resolvedFocusedDate().getMonth()}resolvedFocusedYear(){return this.focusedYear!==null?this.focusedYear:this.resolvedFocusedDate().getFullYear()}buildDisabledChecker(){return X({min:u(this.min),max:u(this.max),disabledDates:this._disabledDates,disabledDaysOfWeek:te(this.disabledDaysOfWeek),disablePast:this.disablePast,disableFuture:this.disableFuture,today:this.resolvedToday(),isDateDisabled:this.isDateDisabled})}resolvedMonthCount(e=this.months){return Number(e)===2?2:1}pageStep(){return this.pageBy==="months"?this.resolvedMonthCount():1}handlePrevious(){if(this.disabled)return;let e=this.resolvedViewAnchor(),t=u(this.min),r=u(this.max);t&&this.isPageOutsideRange("previous",e,t,r)||this.setViewAnchor(this.getPageAnchor("previous",e))}handleNext(){if(this.disabled)return;let e=this.resolvedViewAnchor(),t=u(this.min),r=u(this.max);r&&this.isPageOutsideRange("next",e,t,r)||this.setViewAnchor(this.getPageAnchor("next",e))}getPageAnchor(e,t){let r=e==="previous"?-1:1;return this.view==="days"?E(t,r*this.pageStep()):this.view==="months"?R(t,r*1):R(t,r*12)}isPageOutsideRange(e,t,r,a){if(e==="previous"&&!r||e==="next"&&!a)return!1;if(this.view==="days"){let n=this.pageStep();if(e==="previous"){let l=T(new Date(t.getFullYear(),t.getMonth(),1),-1);return!!r&&l.getTime()<r.getTime()}let o=new Date(t.getFullYear(),t.getMonth()+n,1);return!!a&&o.getTime()>a.getTime()}if(this.view==="months"){let n=t.getFullYear();if(e==="previous"){let l=new Date(n-1,11,31);return!!r&&l.getTime()<r.getTime()}let o=new Date(n+1,0,1);return!!a&&o.getTime()>a.getTime()}let s=Math.floor(t.getFullYear()/12)*12;if(e==="previous"){let n=new Date(s-1,11,31);return!!r&&n.getTime()<r.getTime()}let i=new Date(s+12,0,1);return!!a&&i.getTime()>a.getTime()}setViewAnchor(e){let t=u(this.min),r=u(this.max);this.viewAnchor=A(e,t,r)}handleTitleClick(){this.disabled||(this.view==="days"?(this.setView("months"),this.queueFocus('[part~="view-item"][tabindex="0"]')):this.view==="months"?(this.setView("years"),this.queueFocus('[part~="view-item"][tabindex="0"]')):(this.setView("days"),this.queueFocus('[part~="day"][tabindex="0"]')))}handleTitleKeydown(e){this.disabled||!["ArrowLeft","ArrowRight","ArrowUp","ArrowDown","Home","End"].includes(e.key)||(e.preventDefault(),this.view==="days"?this.queueFocus('[part~="day"][tabindex="0"]'):this.view==="months"?this.queueFocus('[part~="view-item"][tabindex="0"]'):this.queueFocus('[part~="view-item"][tabindex="0"]'))}setView(e){if(this.view===e)return;let t=this.view;this.view=e,e==="months"?t==="days"&&(this.focusedMonth=this.resolvedFocusedDate().getMonth()):e==="years"?this.focusedYear=this.resolvedViewAnchor().getFullYear():(this.focusedMonth=null,this.focusedYear=null),this.dispatchEvent(new J({view:e,date:this.resolvedFocusedDate()}))}handleDayClick(e,t){if(this.disabled||this.readonly||t)return;if(this.mode==="single"&&!this.isInVisibleRange(e)){let l=u(this.min),d=u(this.max);this.viewAnchor=A(new Date(e.getFullYear(),e.getMonth(),1),l,d)}if(this.mode==="single"){let l=x(e);l!==this._value&&(this._value=l,this.focusedDate=l,this.requestUpdate(),this.dispatchEvent(new InputEvent("input",{bubbles:!0,composed:!0})),this.dispatchEvent(new Event("change",{bubbles:!0,composed:!0})),this.announceSelection());return}if(!this.rangeAnchor){this.rangeCommittedValue=this._value,this.rangeAnchor=e,this.hoverDate=e,this.liveAnnouncement="",this._value=x(e),this.focusedDate=this._value,this.dispatchEvent(new InputEvent("input",{bubbles:!0,composed:!0}));return}let r=this.rangeAnchor,a=e,s=r.getTime()<=a.getTime()?r:a,i=r.getTime()<=a.getTime()?a:r,n=Math.round((i.getTime()-s.getTime())/864e5)+1;if(this.maxRange>0&&n>this.maxRange){this.rangeAnchor=null,this.hoverDate=null;let l=this.rangeCommittedValue;this.rangeCommittedValue="",this.liveAnnouncement="",l!==this._value&&(this._value=l,this.dispatchEvent(new InputEvent("input",{bubbles:!0,composed:!0})));return}if(this.minRange>0&&n<this.minRange){this.announce(this.localize.term("rangeTooShort",this.minRange));return}let o=C({from:s,to:i});this.rangeAnchor=null,this.hoverDate=null,this.rangeCommittedValue="",this.liveAnnouncement="",this._value=o,this.focusedDate=x(i),this.dispatchEvent(new InputEvent("input",{bubbles:!0,composed:!0})),this.dispatchEvent(new Event("change",{bubbles:!0,composed:!0})),this.announceSelection()}announce(e){this.liveAnnouncement=this.liveAnnouncement.endsWith("\u200B")?e:e+"\u200B"}announceSelection(){let e=this.resolvedLocale();if(this.mode==="range"){let{from:a,to:s}=M(this._value);if(!a&&!s){this.announce(this.localize.term("selectionCleared"));return}let i=new Intl.DateTimeFormat(e,{month:"long",day:"numeric",year:"numeric"}),n=a&&s?`${i.format(a)} \u2013 ${i.format(s)}`:i.format(a??s);this.announce(this.localize.term("selectedRangeLabel",n));return}let t=u(this._value);if(!t){this.announce(this.localize.term("selectionCleared"));return}let r=new Intl.DateTimeFormat(e,{weekday:"long",month:"long",day:"numeric",year:"numeric"}).format(t);this.announce(this.localize.term("selectedDateLabel",r))}handleDayHover(e){if(this.mode!=="range"||!this.rangeAnchor)return;let t=this.clampHoverDate(e);this.hoverDate&&F(this.hoverDate,t)||(this.hoverDate=t,this.dispatchEvent(new W({date:t})))}clampHoverDate(e){let t=A(e,u(this.min),u(this.max));if(!this.rangeAnchor)return t;let r=this.rangeAnchor,a=t.getTime()>=r.getTime()?1:-1;if(this.maxRange>0){let s=(this.maxRange-1)*a,i=T(r,s);a>0&&t.getTime()>i.getTime()&&(t=i),a<0&&t.getTime()<i.getTime()&&(t=i)}if(this.minRange>0){let s=(this.minRange-1)*a,i=T(r,s);a>0&&t.getTime()<i.getTime()&&(t=i),a<0&&t.getTime()>i.getTime()&&(t=i)}return A(t,u(this.min),u(this.max))}isOutOfReach(e){return this.mode!=="range"||!this.rangeAnchor||this.maxRange<=0?!1:Math.abs(Math.round((e.getTime()-this.rangeAnchor.getTime())/864e5))+1>this.maxRange}handleEscape(){if(this.mode==="range"&&this.rangeAnchor){this.rangeAnchor=null,this.hoverDate=null;let e=this.rangeCommittedValue;this.rangeCommittedValue="",e!==this._value&&(this._value=e,this.dispatchEvent(new InputEvent("input",{bubbles:!0,composed:!0})))}}handleDayKeydown(e,t,r){if(this.disabled||!["ArrowLeft","ArrowRight","ArrowUp","ArrowDown","Home","End","PageUp","PageDown","Enter"," ","Escape"].includes(e.key))return;if(e.preventDefault(),e.key==="Escape"){this.handleEscape();return}if(e.key==="Enter"||e.key===" "){this.handleDayClick(t,r);return}let s=this.localize.dir()==="rtl",i=t;switch(e.key){case"ArrowLeft":i=T(t,s?1:-1);break;case"ArrowRight":i=T(t,s?-1:1);break;case"ArrowUp":i=T(t,-7);break;case"ArrowDown":i=T(t,7);break;case"Home":{let l=this.resolvedFirstDay(),d=(t.getDay()-l+7)%7;i=T(t,-d);break}case"End":{let l=this.resolvedFirstDay(),d=6-(t.getDay()-l+7)%7;i=T(t,d);break}case"PageUp":i=e.shiftKey?R(t,-1):E(t,-1);break;case"PageDown":i=e.shiftKey?R(t,1):E(t,1);break}let n=u(this.min),o=u(this.max);i=A(i,n,o),this.focusedDate=x(i),this.isInVisibleRange(i)||(this.viewAnchor=new Date(i.getFullYear(),i.getMonth(),1)),this.dispatchEvent(new W({date:i})),this.updateComplete.then(()=>{this.base.querySelector(`[data-date="${x(i)}"]`)?.focus()}),this.mode==="range"&&this.rangeAnchor&&(this.hoverDate=i)}render(){let e=this.resolvedViewAnchor(),t=this.buildDisabledChecker(),r=this.resolvedLocale();return k`
      <div part="base date-picker" @mouseleave=${this.handleGridMouseLeave}>
        <slot name="header"> ${this.renderHeader(e,t)} </slot>

        ${this.view==="days"?this.renderDaysView(e,t,r):this.view==="months"?this.renderMonthsView(e,t,r):this.renderYearsView(e,t)}

        <slot name="footer" part="footer"></slot>

        <span class="wa-visually-hidden" aria-live="polite">${this.liveAnnouncement}</span>
      </div>
    `}renderHeader(e,t){let r=this.resolvedLocale(),a=u(this.min),s=u(this.max),i="",n="";if(this.view==="days"){let w=this.resolvedMonthCount()>1;i=new Intl.DateTimeFormat(r,{month:w?void 0:"long",year:"numeric"}).format(e),n=this.localize.term("chooseMonth")}else if(this.view==="months")i=new Intl.DateTimeFormat(r,{year:"numeric"}).format(e),n=this.localize.term("chooseYear");else{let w=Math.floor(e.getFullYear()/12)*12;i=`${w} \u2013 ${w+11}`,n=this.localize.term("chooseDecade")}let o=!!a&&this.isPageOutsideRange("previous",e,a,s),l=!!s&&this.isPageOutsideRange("next",e,a,s),d=this.view==="days"?this.localize.term("previousMonth")||"Previous month":this.view==="months"?this.localize.term("previousYear")||"Previous year":this.localize.term("previousDecade")||"Previous decade",g=this.view==="days"?this.localize.term("nextMonth")||"Next month":this.view==="months"?this.localize.term("nextYear")||"Next year":this.localize.term("nextDecade")||"Next decade";return k`
      <div part="header">
        <button
          type="button"
          part="previous"
          aria-label=${d}
          ?disabled=${o||this.disabled}
          @click=${this.handlePrevious}
        >
          <slot name="previous-icon">
            <wa-icon name="chevron-left" library="system" variant="solid"></wa-icon>
          </slot>
        </button>

        <button
          type="button"
          part="title"
          aria-live="polite"
          aria-label=${n}
          ?disabled=${this.disabled}
          @click=${this.handleTitleClick}
          @keydown=${this.handleTitleKeydown}
        >
          ${i}
        </button>

        <button
          type="button"
          part="next"
          aria-label=${g}
          ?disabled=${l||this.disabled}
          @click=${this.handleNext}
        >
          <slot name="next-icon">
            <wa-icon name="chevron-right" library="system" variant="solid"></wa-icon>
          </slot>
        </button>
      </div>
    `}renderDaysView(e,t,r){let a=this.resolvedMonthCount();if(this.didSSR&&!this.hasUpdated){let s=this.shadowRoot?.querySelector?.("[part~='months']")?.getAttribute("data-month-count");s&&(a=this.resolvedMonthCount(Number(s)))}return k`
      <div part="months" data-month-count="${a}">
        ${Array.from({length:a},(s,i)=>this.renderMonth(E(e,i),t,r,a>1))}
      </div>
    `}renderMonth(e,t,r,a){let s=this.resolvedFirstDay(),i=new Set(this.resolvedWeekendDays()),n=new Date(e.getFullYear(),e.getMonth(),1),o=(n.getDay()-s+7)%7,l=T(n,-o),d=42,g=[];for(let p=0;p<d/7;p++){let D=[];for(let b=0;b<7;b++)D.push(T(l,p*7+b));g.push(D)}let w=new Intl.DateTimeFormat(r,{weekday:this.weekdayFormat}),y=[];for(let p=0;p<7;p++){let D=T(l,p);y.push({label:w.format(D),long:new Intl.DateTimeFormat(r,{weekday:"long"}).format(D)})}let m=new Intl.DateTimeFormat(r,{month:"long",year:"numeric"}),v=this.readonly?`${m.format(e)}, ${this.localize.term("readonly")}`:m.format(e);return k`
      <div part="month" data-first-day="${s}" data-weekend-days="${[...i.values()].join(" ")}">
        ${a?k`<div part="month-label">${m.format(e)}</div>`:I}
        <table part="grid" role="grid" aria-label=${v} aria-readonly=${this.readonly?"true":"false"}>
          <thead>
            <tr part="weekdays">
              ${this.withWeekNumbers?k`<th part="weeknumbers" scope="col" aria-label="Week"></th>`:I}
              ${y.map(p=>k`<th part="weekday" scope="col" aria-label=${p.long}>${p.label}</th>`)}
            </tr>
          </thead>
          <tbody>
            ${g.map(p=>this.renderWeek(p,e,t,i,r))}
          </tbody>
        </table>
      </div>
    `}renderWeek(e,t,r,a,s){let i=this.withWeekNumbers?this.computeIsoWeek(e):null;return k`
      <tr role="row">
        ${i!==null?k`<th part="weeknumber" scope="row">${i}</th>`:I}
        ${e.map(n=>this.renderDay(n,t,r,a,s))}
      </tr>
    `}computeIsoWeek(e){let t=e.find(n=>n.getDay()===4)??e[3],r=new Date(t.getFullYear(),t.getMonth(),t.getDate()),a=new Date(r.getFullYear(),0,4),s=(a.getDay()+6)%7;a.setDate(a.getDate()-s+3);let i=(r.getDay()+6)%7;return r.setDate(r.getDate()-i+3),1+Math.round((r.getTime()-a.getTime())/(7*864e5))}renderDay(e,t,r,a,s){let i=!G(e,t),n=i&&!this.withOutsideDays,o=x(e),l=this.resolvedToday(),d=F(e,l),g=r(e),w=this.resolvedRovingFocus(),y=F(e,w)&&!i,m=this.computeSelectionState(e),v=this.isOutOfReach(e),p=`day-${o}`,D={"data-in-range":m.inner||m.preview||m.start||m.end?"":void 0,"data-range-start":m.start?"":void 0,"data-range-end":m.end?"":void 0};if(n)return k`<td role="gridcell" aria-hidden="true"><span part="day-placeholder"></span></td>`;let b=["day"];return d&&b.push("day-today"),i&&b.push("day-outside"),a.has(e.getDay())&&b.push("day-weekend"),g&&b.push("day-disabled"),m.selected&&b.push("day-selected"),m.start&&b.push("day-range-start"),m.end&&b.push("day-range-end"),m.inner&&b.push("day-range-inner"),m.preview&&b.push("day-range-preview"),k`
      <td
        role="gridcell"
        aria-selected=${m.selected?"true":"false"}
        data-in-range=${S(D["data-in-range"])}
        data-range-start=${S(D["data-range-start"])}
        data-range-end=${S(D["data-range-end"])}
      >
        <button
          type="button"
          part=${b.join(" ")}
          data-date=${o}
          data-day-of-week=${["sun","mon","tue","wed","thu","fri","sat"][e.getDay()]}
          ?data-today=${d}
          ?data-outside=${i}
          ?data-weekend=${a.has(e.getDay())}
          ?data-disabled=${g}
          ?data-selected=${m.selected}
          ?data-range-start=${m.start}
          ?data-range-end=${m.end}
          ?data-range-inner=${m.inner}
          ?data-range-preview=${m.preview}
          ?data-out-of-reach=${v}
          aria-label=${this.dayAriaLabel(e,m,d)}
          aria-disabled=${g?"true":"false"}
          aria-current=${S(d?"date":void 0)}
          tabindex=${y?0:-1}
          @click=${()=>this.handleDayClick(e,g)}
          @mouseenter=${()=>this.handleDayHover(e)}
          @keydown=${Y=>this.handleDayKeydown(Y,e,g)}
        >
          <span part="day-label">
            <slot name=${p}>${this.renderDayFallback(e)}</slot>
          </span>
        </button>
      </td>
    `}dayAriaLabel(e,t,r){let a=this.resolvedLocale(),s=[new Intl.DateTimeFormat(a,{weekday:"long",month:"long",day:"numeric",year:"numeric"}).format(e)];return r&&s.push(this.localize.term("today")),this.mode==="single"&&t.selected&&s.push(this.localize.term("selected")),s.join(", ")}renderDayFallback(e){if(this.dayContent){let t=this.dayContent(e);if(t!=null)return typeof t=="string"?j(t):t}return e.getDate()}computeSelectionState(e){if(this.mode==="single"){let n=u(this._value);return{selected:!!n&&F(n,e),start:!1,end:!1,inner:!1,preview:!1}}if(this.rangeAnchor){let n=this.hoverDate??this.rangeAnchor,o=this.rangeAnchor,l=o.getTime()<=n.getTime()?o:n,d=o.getTime()<=n.getTime()?n:o,g=F(l,e),w=F(d,e),y=e.getTime()>l.getTime()&&e.getTime()<d.getTime();return{selected:g||w,start:g,end:w,inner:!1,preview:y}}let{from:t,to:r}=M(this._value),a=!!t&&F(t,e),s=!!r&&F(r,e),i=!!t&&!!r&&e.getTime()>t.getTime()&&e.getTime()<r.getTime();return{selected:a||s,start:a,end:s,inner:i,preview:!1}}selectedMonthForView(e){let t=this.selectedDateForHighlight();return t&&t.getFullYear()===e?t.getMonth():null}selectedYearForView(){let e=this.selectedDateForHighlight();return e?e.getFullYear():null}selectedDateForHighlight(){return this.mode==="single"?u(this._value):M(this._value).from}renderMonthsView(e,t,r){let a=e.getFullYear(),s=new Intl.DateTimeFormat(r,{month:"short"}),i=new Intl.DateTimeFormat(r,{month:"long",year:"numeric"}),n=new Intl.DateTimeFormat(r,{year:"numeric"}).format(e),o=this.resolvedFocusedMonth(),l=this.selectedMonthForView(a),d=this.resolvedToday(),g=d.getFullYear()===a?d.getMonth():-1,w=y=>{let m=new Date(a,y,1),v=this.isMonthFullyDisabled(a,y,t),p=l===y,D=o===y,b=g===y,Y=["view-item"];return b&&Y.push("view-item-today"),p&&Y.push("view-item-selected"),v&&Y.push("view-item-disabled"),k`
        <div role="gridcell" part="view-cell" aria-selected=${p?"true":"false"}>
          <button
            type="button"
            part=${Y.join(" ")}
            data-month=${y}
            ?data-disabled=${v}
            ?data-selected=${p}
            ?data-today=${b}
            aria-disabled=${v?"true":"false"}
            aria-current=${S(b?"date":void 0)}
            aria-label=${i.format(m)}
            tabindex=${D?0:-1}
            @click=${()=>this.handleMonthPick(a,y,v)}
            @keydown=${se=>this.handleMonthKeydown(se,a,y,v)}
          >
            ${s.format(m)}
          </button>
        </div>
      `};return k`
      <div part="view-grid" role="grid" aria-label="${this.localize.term("chooseMonth")}, ${n}">
        ${this.renderViewRows(12,w)}
      </div>
    `}renderViewRows(e,t){let a=[];for(let s=0;s<e;s+=3){let i=Array.from({length:Math.min(3,e-s)},(n,o)=>t(s+o));a.push(k`<div part="view-row" role="row">${i}</div>`)}return a}renderYearsView(e,t){let r=Math.floor(e.getFullYear()/12)*12,a=r+11,s=this.resolvedFocusedYear(),i=this.selectedYearForView(),n=this.resolvedToday().getFullYear(),o=l=>{let d=r+l,g=this.isYearFullyDisabled(d,t),w=i===d,y=s===d,m=n===d,v=["view-item"];return m&&v.push("view-item-today"),w&&v.push("view-item-selected"),g&&v.push("view-item-disabled"),k`
        <div role="gridcell" part="view-cell" aria-selected=${w?"true":"false"}>
          <button
            type="button"
            part=${v.join(" ")}
            data-year=${d}
            ?data-disabled=${g}
            ?data-selected=${w}
            ?data-today=${m}
            aria-disabled=${g?"true":"false"}
            aria-current=${S(m?"date":void 0)}
            aria-label=${String(d)}
            tabindex=${y?0:-1}
            @click=${()=>this.handleYearPick(d,g)}
            @keydown=${p=>this.handleYearKeydown(p,d,g)}
          >
            ${d}
          </button>
        </div>
      `};return k`
      <div
        part="view-grid"
        role="grid"
        aria-label="${this.localize.term("chooseYear")}, ${r}–${a}"
      >
        ${this.renderViewRows(12,o)}
      </div>
    `}handleMonthKeydown(e,t,r,a){if(this.disabled||!["ArrowLeft","ArrowRight","ArrowUp","ArrowDown","Home","End","PageUp","PageDown","Enter"," ","Escape"].includes(e.key))return;if(e.preventDefault(),e.key==="Escape"){this.setView("days"),this.queueFocus('[part~="day"][tabindex="0"]');return}if(e.key==="Enter"||e.key===" "){this.handleMonthPick(t,r,a);return}let i=this.localize.dir()==="rtl",n=t,o=r;switch(e.key){case"ArrowLeft":o=r+(i?1:-1),o<0&&(o=0),o>11&&(o=11);break;case"ArrowRight":o=r+(i?-1:1),o<0&&(o=0),o>11&&(o=11);break;case"ArrowUp":o=Math.max(0,r-3);break;case"ArrowDown":o=Math.min(11,r+3);break;case"Home":{let l=Math.floor(r/3)*3;o=e.ctrlKey||e.metaKey?0:l;break}case"End":{let l=Math.floor(r/3)*3+2;o=e.ctrlKey||e.metaKey?11:l;break}case"PageUp":n=t-1;break;case"PageDown":n=t+1;break}n!==t&&this.setViewAnchor(new Date(n,r,1)),this.focusedMonth=o,this.queueFocus(`[part~="view-item"][data-month="${o}"]`)}handleYearKeydown(e,t,r){if(this.disabled||!["ArrowLeft","ArrowRight","ArrowUp","ArrowDown","Home","End","PageUp","PageDown","Enter"," ","Escape"].includes(e.key))return;if(e.preventDefault(),e.key==="Escape"){this.setView("months"),this.queueFocus('[part~="view-item"][tabindex="0"]');return}if(e.key==="Enter"||e.key===" "){this.handleYearPick(t,r);return}let s=this.localize.dir()==="rtl",i=Math.floor(t/12)*12,n=t-i,o=t;switch(e.key){case"ArrowLeft":o=t+(s?1:-1);break;case"ArrowRight":o=t+(s?-1:1);break;case"ArrowUp":o=t-3;break;case"ArrowDown":o=t+3;break;case"Home":{let d=i+Math.floor(n/3)*3;o=e.ctrlKey||e.metaKey?i:d;break}case"End":{let d=i+Math.floor(n/3)*3+2;o=e.ctrlKey||e.metaKey?i+11:d;break}case"PageUp":o=t-12;break;case"PageDown":o=t+12;break}if(Math.floor(o/12)*12!==i){let d=this.resolvedViewAnchor();this.setViewAnchor(new Date(o,d.getMonth(),1))}this.focusedYear=o,this.queueFocus(`[part~="view-item"][data-year="${o}"]`)}queueFocus(e){this.updateComplete.then(()=>{this.base.querySelector(e)?.focus()})}isMonthFullyDisabled(e,t,r){let a=new Date(e,t,1),s=new Date(e,t+1,0);return!this.anyEnabledInRange(a,s,r)}isYearFullyDisabled(e,t){let r=new Date(e,0,1),a=new Date(e,11,31);return!this.anyEnabledInRange(r,a,t)}anyEnabledInRange(e,t,r){let a=new Date(e.getFullYear(),e.getMonth(),e.getDate());for(;a.getTime()<=t.getTime();){if(!r(a))return!0;a.setDate(a.getDate()+1)}return!1}handleMonthPick(e,t,r){if(r||this.disabled)return;this.setViewAnchor(new Date(e,t,1));let a=this.resolvedFocusedDate().getDate(),s=Math.min(a,z(e,t));this.focusedDate=x(new Date(e,t,s)),this.setView("days"),this.queueFocus('[part~="day"][tabindex="0"]')}handleYearPick(e,t){if(t||this.disabled)return;let r=this.resolvedFocusedDate(),a=r.getMonth();this.setViewAnchor(new Date(e,a,1));let s=Math.min(r.getDate(),z(e,a));this.focusedDate=x(new Date(e,a,s)),this.setView("months"),this.queueFocus('[part~="view-item"][tabindex="0"]')}};h.css=[ae,ie,B];c([L('[part~="base"]')],h.prototype,"base",2);c([f({reflect:!0})],h.prototype,"mode",2);c([f({reflect:!0})],h.prototype,"value",1);c([f({reflect:!0})],h.prototype,"min",2);c([f({reflect:!0})],h.prototype,"max",2);c([f({reflect:!0})],h.prototype,"today",2);c([f({attribute:"focused-date",reflect:!0})],h.prototype,"focusedDate",2);c([f({reflect:!0})],h.prototype,"view",2);c([f({type:Number,reflect:!0})],h.prototype,"months",2);c([f({attribute:"page-by",reflect:!0})],h.prototype,"pageBy",2);c([f({attribute:"first-day-of-week",reflect:!0})],h.prototype,"firstDayOfWeek",2);c([f({attribute:"with-outside-days",type:Boolean,reflect:!0})],h.prototype,"withOutsideDays",2);c([f({attribute:"with-week-numbers",type:Boolean,reflect:!0})],h.prototype,"withWeekNumbers",2);c([f({attribute:"weekday-format",reflect:!0})],h.prototype,"weekdayFormat",2);c([f({type:Boolean,reflect:!0})],h.prototype,"disabled",2);c([f({type:Boolean,reflect:!0})],h.prototype,"readonly",2);c([f({attribute:"disabled-dates"})],h.prototype,"disabledDates",1);c([f({attribute:"disabled-days-of-week"})],h.prototype,"disabledDaysOfWeek",2);c([f({attribute:"disable-past",type:Boolean,reflect:!0})],h.prototype,"disablePast",2);c([f({attribute:"disable-future",type:Boolean,reflect:!0})],h.prototype,"disableFuture",2);c([f({attribute:"min-range",type:Number,reflect:!0})],h.prototype,"minRange",2);c([f({attribute:"max-range",type:Number,reflect:!0})],h.prototype,"maxRange",2);c([f({reflect:!0})],h.prototype,"size",2);c([P("size")],h.prototype,"handleSizeChange",1);c([f({reflect:!0})],h.prototype,"locale",2);c([f({attribute:!1})],h.prototype,"isDateDisabled",2);c([f({attribute:!1})],h.prototype,"dayContent",2);c([$()],h.prototype,"rangeAnchor",2);c([$()],h.prototype,"hoverDate",2);c([$()],h.prototype,"viewAnchor",2);c([$()],h.prototype,"focusedMonth",2);c([$()],h.prototype,"focusedYear",2);c([$()],h.prototype,"liveAnnouncement",2);c([P("mode")],h.prototype,"handleModeChange",1);h=c([O("wa-date-picker")],h);
/*! For license information please see pro-proof.js.LEGAL.txt */
