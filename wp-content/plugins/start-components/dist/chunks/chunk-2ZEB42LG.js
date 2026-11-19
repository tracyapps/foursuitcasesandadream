var r={small:"s",medium:"m",large:"l"};var i=new Set;function o(n,e){e in r&&!i.has(`${n}:${e}`)&&(i.add(`${n}:${e}`),console.warn(`[${n}] size="${e}" is deprecated. Use size="${r[e]}" instead. The long-form value will be removed in the next major version.`))}export{o as a};
/*! For license information please see chunk-2ZEB42LG.js.LEGAL.txt */
