import {createRequire} from 'node:module';
const tools=createRequire(process.env.START_BUILD_TOOLS?process.env.START_BUILD_TOOLS+'/package.json':import.meta.url);
const {build: bundle, transform}=tools('esbuild'),{optimize}=tools('svgo');
import {createServer} from 'node:http';
import { readFile, writeFile, mkdir, rm, rename, readdir, open, realpath, watch } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';
import path from 'node:path';
import { compileTokens } from './token-compiler.mjs';
const root = fileURLToPath(new URL('..', import.meta.url));
const file = relative => path.join(root, relative);
const json = value => JSON.stringify(value, null, 2) + '\n';
const cssRoot = await realpath(file('assets/src/css'));
function isLocalCss(filename) {
  const relative = path.relative(cssRoot, filename);
  return path.extname(filename) === '.css' && !relative.startsWith('..' + path.sep) && !path.isAbsolute(relative);
}
async function css(relative, chain = []) {
  const filename = await realpath(path.resolve(cssRoot, relative));
  if (!isLocalCss(filename)) throw new Error(`CSS partial is outside assets/src/css: ${relative}`);
  if (chain.includes(filename)) throw new Error(`Cyclic CSS import: ${[...chain, filename].map(item => path.relative(cssRoot, item)).join(' → ')}`);

  const source = await readFile(filename, 'utf8');
  // Skip comments and quoted strings so examples and content values are left alone.
  const tokens = /\/\*[\s\S]*?\*\/|"(?:\\[\s\S]|[^"\\])*"|'(?:\\[\s\S]|[^'\\])*'|(@import\b[^;]*;?)/gi;
  let result = '';
  let cursor = 0;
  for (const token of source.matchAll(tokens)) {
    const rule = token[1];
    if (!rule) continue;
    const specifier = rule.match(/^@import\s+(["'])([^"'\r\n]+)\1\s*;$/i)?.[2];
    const target = specifier && path.resolve(path.dirname(filename), specifier);
    if (!target || /[:?#\\]/.test(specifier) || path.isAbsolute(specifier) || !isLocalCss(target)) {
      throw new Error(`Invalid CSS import in ${relative}: ${rule.trim()}. Use a quoted relative .css path within assets/src/css.`);
    }
    result += source.slice(cursor, token.index);
    result += await css(path.relative(cssRoot, target), [...chain, filename]);
    cursor = token.index + rule.length;
  }
  return result + source.slice(cursor);
}
async function sprite() {
  const symbols = [];
  for (const name of (await readdir(file('assets/src/svg/originals'))).sort()) {
    if (!/^[a-z][a-z0-9-]*\.svg$/.test(name)) throw new Error('Invalid SVG source filename');
    const raw = await readFile(file('assets/src/svg/originals/' + name), 'utf8');
    if (/<(?:script|foreignObject|iframe|animate|set)|\bon\w+\s*=|<!ENTITY|<!DOCTYPE|<\?|(?:href|src)\s*=\s*["'](?!#)/i.test(raw)) throw new Error('Unsafe SVG source');
    const source = optimize(raw,{multipass:true,plugins:['preset-default','removeDimensions',{name:'prefixIds',params:{prefix:'icon-'+name.slice(0,-4)}}]}).data;
    if (/<(?:script|foreignObject|iframe|animate|set)|\bon\w+\s*=|<!ENTITY|<!DOCTYPE|<\?|(?:href|src)\s*=\s*["'](?!#)/i.test(source)) throw new Error('Unsafe SVG source');
    const viewBox = source.match(/viewBox="([\d. -]+)"/)?.[1];
    if (!viewBox) throw new Error('SVG requires viewBox');
    symbols.push(`<symbol id="icon-${name.slice(0,-4)}" viewBox="${viewBox}">${source.replace(/^[\s\S]*?<svg\b[^>]*>/,'').replace(/<\/svg>\s*$/,'')}</symbol>`);
  }
  return `<svg xmlns="http://www.w3.org/2000/svg">${symbols.join('')}</svg>`;
}
async function build() {
  const lock = await open(file('.start-build-lock'), 'wx');
  try {
    const compiled = compileTokens(JSON.parse(await readFile(file('design.tokens.json'), 'utf8')));
    const scripts = await bundle({
      entryPoints: ['main', 'passport-preview', 'editor-preview-links', 'editor-block-width'].map(name => file('assets/src/js/' + name + '.js')),
      outdir: file('assets/dist/js'), bundle: true, write: false,
      format: 'iife', minify: true, target: 'es2022',
    });
    const output = {
      'css/tokens.css':compiled.css, 'css/main.css':await css('main.css'), 'css/editor.css':await css('editor.css'), 'css/passport-preview.css':await css('passport-stamp.css'),
      ...Object.fromEntries(scripts.outputFiles.map(script => ['js/' + path.basename(script.path), script.text])),
      'js/editor.js':await readFile(file('assets/src/js/editor.js'),'utf8'),
      'svg/icons.svg':await sprite(),
      'data/style-guide.json':json({version:1,designId:compiled.document.id,revision:compiled.document.revision,tokens:compiled.document.tokens})
    };
    for (const name of Object.keys(output)) {
      if(name.endsWith('.css') && name!=='css/tokens.css') output[name]=(await transform(output[name],{loader:'css',minify:true,target:'es2022',supported:{nesting:false}})).code;
      if(name==='js/editor.js') output[name]=(await transform(output[name],{loader:'js',minify:true,target:'es2022'})).code;
    }
    await rm(file('assets/.dist-next'), {recursive:true,force:true});
    for (const [name, source] of Object.entries(output)) {
      await mkdir(path.dirname(file('assets/.dist-next/' + name)), {recursive:true});
      await writeFile(file('assets/.dist-next/' + name),source);
    }
    await writeFile(file('.theme-next.json'),json(compiled.themeJson));
    await rm(file('assets/.dist-before'),{recursive:true,force:true});
    try { await rename(file('assets/dist'),file('assets/.dist-before')); } catch (e) { if(e.code!=='ENOENT')throw e; }
    try { await rename(file('assets/.dist-next'),file('assets/dist')); }
    catch (e) { await rename(file('assets/.dist-before'),file('assets/dist')); throw e; }
    await rename(file('.theme-next.json'),file('theme.json'));
    await rm(file('assets/.dist-before'),{recursive:true,force:true});
    console.log('Built theme assets and design tokens.');
  } finally { await lock.close(); await rm(file('.start-build-lock')); }
}
await build();
if (process.argv.includes('--watch')) {
  const origin=new URL(process.env.START_DEV_ORIGIN||'http://foursuitcasesandadream.local').origin;
  const port=Number(process.env.START_DEV_PORT||35729);if(!Number.isInteger(port)||port<1024||port>65535)throw new Error('Invalid reload port');
  const clients=new Set();
  const reload=()=>{for(const response of clients)response.write('data: reload\n\n');};
  const server=createServer((req,res)=>{
    if(!['127.0.0.1:'+port,'localhost:'+port].includes(req.headers.host)||req.headers.origin&&req.headers.origin!==origin){res.writeHead(403).end();return;}
    res.setHeader('Cache-Control','no-store');res.setHeader('Access-Control-Allow-Origin',origin);
    if(req.url==='/client.js'){res.setHeader('Content-Type','text/javascript');res.end(`new EventSource('http://127.0.0.1:${port}/events').onmessage=()=>location.reload();`);return;}
    if(req.url!=='/events'){res.writeHead(404).end();return;}
    res.writeHead(200,{'Content-Type':'text/event-stream','Connection':'keep-alive'});res.write(': connected\n\n');clients.add(res);req.on('close',()=>clients.delete(res));
  });
  server.listen(port,'127.0.0.1',()=>console.log(`Open ${origin} for the site. Live reload listens on http://127.0.0.1:${port}.`));
  const close=()=>{for(const response of clients)response.end();server.close();process.exit();};process.on('SIGTERM',close);process.on('SIGINT',close);

  let running = false, dirty = false;
  const rebuild = async () => {
    dirty = true;
    if (running) return;
    running = true;
    try { while (dirty) { dirty = false; try { await build(); reload(); } catch (e) { console.error(e.message); } } }
    finally { running = false; }
  };
  // Component assets are built by their own watcher; reload only completed output/PHP/JSON changes.
  void (async()=>{try{for await(const event of watch(path.resolve(root,'../../plugins/start-components'),{recursive:true})){if(event.filename?.startsWith('dist/')||event.filename?.endsWith('.php')||event.filename?.startsWith('acf-json/'))reload();}}catch(e){if(e.code!=='ENOENT')console.error('Companion reload watch unavailable');}})();
  console.log('Watching design.tokens.json and assets/src. Enable FOURSUITCASES_DEV_RELOAD in local wp-config.php for automatic reload.');
  for await (const event of watch(root,{recursive:true})) {
    if(event.filename?.endsWith('.php')||event.filename?.startsWith('acf-json/')){reload();continue;}
    if (event.filename === 'design.tokens.json' || event.filename?.startsWith('assets/src/')) void rebuild();
  }
}
