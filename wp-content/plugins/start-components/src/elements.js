const syncMode=()=>{const dark=document.documentElement.dataset.theme==='dark'||document.documentElement.dataset.theme!=='light'&&matchMedia('(prefers-color-scheme: dark)').matches;document.documentElement.classList.toggle('wa-dark',dark);document.documentElement.classList.toggle('wa-light',!dark);};
if(!document.body.classList.contains('wp-admin')){
 for(const name of ['vendor-theme.css','vendor-utilities.css'])if(!document.querySelector('link[data-start-elements-style="'+name+'"]')){const link=document.createElement('link');link.rel='stylesheet';link.href=new URL(name,import.meta.url).href;link.dataset.startElementsStyle=name;document.head.append(link);}
 syncMode();new MutationObserver(syncMode).observe(document.documentElement,{attributes:true,attributeFilter:['data-theme']});matchMedia('(prefers-color-scheme: dark)').addEventListener('change',syncMode);
}
import DOMPurify from 'dompurify';
const imports={
 button:()=>import('@web.awesome.me/webawesome-pro/dist/components/button/button.js'),
 card:()=>import('@web.awesome.me/webawesome-pro/dist/components/card/card.js'),
 badge:()=>import('@web.awesome.me/webawesome-pro/dist/components/badge/badge.js'),
 tag:()=>import('@web.awesome.me/webawesome-pro/dist/components/tag/tag.js'),
 icon:()=>import('@web.awesome.me/webawesome-pro/dist/components/icon/icon.js'),
 divider:()=>import('@web.awesome.me/webawesome-pro/dist/components/divider/divider.js'),
 'progress-bar':()=>import('@web.awesome.me/webawesome-pro/dist/components/progress-bar/progress-bar.js'),
 'progress-ring':()=>import('@web.awesome.me/webawesome-pro/dist/components/progress-ring/progress-ring.js'),
 rating:()=>import('@web.awesome.me/webawesome-pro/dist/components/rating/rating.js'),
 avatar:()=>import('@web.awesome.me/webawesome-pro/dist/components/avatar/avatar.js')
};
async function enhance(node){
 const source=node.getAttribute('data-start-props');if(node.dataset.startElementState===source)return;node.dataset.startElementState=source;
 try{
  const p=JSON.parse(source);if(!Object.hasOwn(imports,p.kind))return;
  await imports[p.kind]();if(!node.isConnected||node.getAttribute('data-start-props')!==source)return;
  if(/^[a-f0-9]{10}$/.test(p.faKit||''))document.documentElement.setAttribute('data-fa-kit-code',p.faKit);
  const c=document.createElement('wa-'+p.kind),label=typeof p.label==='string'?p.label:'';if(['progress-bar','progress-ring','rating','avatar','icon'].includes(p.kind)){c.setAttribute('aria-label',label);c.setAttribute('label',label);}
  if(p.kind==='button'){c.variant='brand';if(typeof p.href==='string'&&/^(https?:|mailto:|tel:|\/|#)/i.test(p.href))c.href=p.href;c.appearance=p.outline?'outlined':'accent';c.textContent=label;}
  else if(p.kind==='card'){const title=document.createElement('h2');title.slot='header';title.textContent=label;const body=document.createElement('div');body.innerHTML=DOMPurify.sanitize(p.content||'');c.append(title,body);}
  else if(['progress-bar','progress-ring','rating'].includes(p.kind)){c.value=Math.max(0,Math.min(p.kind==='rating'?5:100,Number(p.value)||0));if(p.kind==='rating')c.readonly=true;}
  else if(p.kind==='icon'){c.name=/^[a-z0-9-]{1,80}$/.test(p.icon)?p.icon:'star';}
  else if(p.kind==='avatar'){c.initials=label.split(/\s+/).map(s=>s[0]||'').slice(0,2).join('');}
  else if(p.kind!=='divider')c.textContent=label;
  if('appearance' in c&&p.outline)c.appearance='outlined';
  if(['progress-bar','progress-ring'].includes(p.kind)){const text=document.createElement('span');text.textContent=label;node.replaceChildren(text,c);}else node.replaceChildren(c);node.dataset.startEnhanced='true';document.documentElement.dataset.startVendor='npm';
 }catch{node.dataset.startElementStatus='unavailable';}
}
function scan(){for(const node of document.querySelectorAll('[data-start-element]'))void enhance(node);}
scan();new MutationObserver(scan).observe(document.body,{subtree:true,childList:true,attributes:true,attributeFilter:['data-start-props']});
