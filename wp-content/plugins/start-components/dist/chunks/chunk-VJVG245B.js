function u(t,c){let l={waitUntilFirstUpdate:!1,...c};return(a,o)=>{let{update:d}=a,f=Array.isArray(t)?t:[t];a.update=function(i){f.forEach(h=>{let s=h;if(i.has(s)){let n=i.get(s),e=this[s];n!==e&&(!l.waitUntilFirstUpdate||this.hasUpdated)&&this[o](n,e)}}),d.call(this,i)}}}export{u as a};
/*! For license information please see chunk-VJVG245B.js.LEGAL.txt */
