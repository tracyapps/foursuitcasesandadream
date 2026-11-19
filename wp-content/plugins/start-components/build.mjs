import { build } from 'esbuild';
import { mkdir, copyFile, rm, rename, open, watch } from 'node:fs/promises';
async function buildAll(){
const lock=await open('.start-build-lock','wx');
try {
// Build everything before replacing the working output.
const out = '.dist-next';
await rm(out, { recursive: true, force: true });
await mkdir(out);
for (const name of ['editor', 'kit-details']) {
  await build({ entryPoints: [`src/${name}.js`], outfile: `${out}/${name}.js`, bundle: true, minify: true, format: 'iife', target: ['es2022'], legalComments: 'linked' });
}
// ESM splitting shares Lit/registrations between selectively loaded components.
await build({ entryPoints: ['src/details.js', 'src/pro-proof.js', 'src/elements.js'], outdir: out, chunkNames: 'chunks/[name]-[hash]', bundle: true, splitting: true, minify: true, format: 'esm', target: ['es2022'], legalComments: 'linked' });
for (const name of ['style', 'hero', 'curation', 'vendor-theme', 'vendor-utilities']) {
  await build({ entryPoints: [`src/${name}.css`], outfile: `${out}/${name}.css`, bundle: true, minify: true });
}
await copyFile('node_modules/@web.awesome.me/webawesome-pro/LICENSE.md', `${out}/WEB-AWESOME-LICENSE.md`);
await copyFile('node_modules/dompurify/LICENSE', `${out}/DOMPURIFY-LICENSE.txt`);
await rm('.dist-before', { recursive: true, force: true });
try { await rename('dist', '.dist-before'); } catch (error) { if (error.code !== 'ENOENT') throw error; }
try { await rename(out, 'dist'); } catch (error) { await rename('.dist-before', 'dist'); throw error; }
await rm('.dist-before', { recursive: true, force: true });

} finally {await lock.close();await rm('.start-build-lock');}
}
await buildAll();
if(process.argv.includes('--watch')){
 let running=false,dirty=false;const rebuild=async()=>{dirty=true;if(running)return;running=true;try{while(dirty){dirty=false;try{await buildAll();console.log('Rebuilt component assets.');}catch(e){console.error(e.message);}}}finally{running=false;}};
 for await(const event of watch('.',{recursive:true}))if(event.filename?.startsWith('src/'))void rebuild();
}
