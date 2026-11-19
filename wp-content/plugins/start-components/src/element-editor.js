import DOMPurify from 'dompurify';
import {ensureVendor} from './vendor.js';
const {createElement:el,Fragment,useEffect,useRef}=window.wp.element;
const {registerBlockType}=window.wp.blocks,{useBlockProps,RichText,InspectorControls}=window.wp.blockEditor,{PanelBody,SelectControl,TextControl,RangeControl}=window.wp.components;
const names=['button','card','badge','tag','icon','divider','progress-bar','progress-ring','rating','avatar'];
function Preview({attributes:a}){
 const ref=useRef();useEffect(()=>{
  const node=ref.current,doc=node.ownerDocument,config=window.startComponentsEditor.vendor;
  node.dataset.startElement=a.kind;node.dataset.startProps=JSON.stringify({...a,faKit:config.faKit,outline:a.className?.includes('is-style-outline')});
  node.replaceChildren();const fallback=doc.createElement(a.kind==='card'?'section':'span');fallback.textContent=a.label;node.append(fallback);
  let disposed=false;ensureVendor(doc,config).then(()=>{if(disposed||doc.querySelector('script[data-start-elements]'))return;const script=doc.createElement('script');script.type='module';script.src=window.startComponentsEditor.elementsScript;script.dataset.startElements='true';doc.head.append(script);}).catch(()=>{});
  return()=>{disposed=true;};
 },[a.kind,a.label,a.content,a.href,a.value,a.icon,a.className]);return el('div',{ref});
}
registerBlockType('start/design-element',{
 edit({attributes:a,setAttributes}){return el(Fragment,{},el(InspectorControls,{},el(PanelBody,{title:'Design component'},el(SelectControl,{label:'Component',value:a.kind,options:names.map(n=>({label:n.replaceAll('-',' '),value:n})),onChange:kind=>setAttributes({kind})}),a.kind==='button'&&el(TextControl,{label:'Link destination',value:a.href,onChange:href=>setAttributes({href})}),a.kind==='icon'&&el(TextControl,{label:'Font Awesome icon name',value:a.icon,onChange:icon=>setAttributes({icon})}),['progress-bar','progress-ring','rating'].includes(a.kind)&&el(RangeControl,{label:'Value',value:a.value,min:0,max:a.kind==='rating'?5:100,onChange:value=>setAttributes({value})}))),el('div',useBlockProps(),el(TextControl,{label:'Text / accessible label',value:a.label,onChange:label=>setAttributes({label})}),a.kind==='card'&&el(RichText,{tagName:'div',value:a.content,allowedFormats:['core/bold','core/italic','core/link'],onChange:content=>setAttributes({content}),'aria-label':'Card content'}),el('p',{className:'start-field-label'},'Interactive preview'),el(Preview,{attributes:a})));},save(){return null;}
});
