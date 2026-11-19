// Originally shared with generated projects. No Workbench or npm runtime dependency.
const alias = /^\{([a-z][a-z0-9]*(?:[.-][a-z0-9]+)*)\}$/;
const kinds = ['color', 'dimension', 'fontFamily'];
export function parseTokens(input) {
  const obj = (v, keys) => {
    if (!v || typeof v !== 'object' || Array.isArray(v) || Object.keys(v).some(k => !keys.includes(k))) throw new Error('Unknown design field or invalid object');
    return v;
  };
  const d = obj(input, ['version', 'id', 'revision', 'tokens']);
  if (d.version !== 1 || typeof d.id !== 'string' || !/^[a-z][a-z0-9-]{0,59}$/.test(d.id) || !Number.isSafeInteger(d.revision) || d.revision < 1) throw new Error('Invalid design identity/revision');
  if (!d.tokens || typeof d.tokens !== 'object' || Array.isArray(d.tokens) || Object.keys(d.tokens).length > 200) throw new Error('Invalid token catalog');
  const tokens = Object.create(null), names = new Set();
  for (const key of Object.keys(d.tokens).sort()) {
    if (!/^[a-z][a-z0-9]*(?:\.[a-z][a-z0-9-]*)+$/.test(key) || key.length > 100) throw new Error('Invalid stable token ID');
    const cssName = key.replaceAll('.', '-');
    if (names.has(cssName)) throw new Error('Colliding CSS token names');
    names.add(cssName);
    const t = obj(d.tokens[key], ['type', 'value', 'modes']);
    const namespaceType = {color:'color',space:'dimension',radius:'dimension',layout:'dimension',font:'fontFamily'}[key.split('.')[0]];
    if (namespaceType && t.type !== namespaceType) throw new Error('Token namespace/type mismatch');
    if (!kinds.includes(t.type)) throw new Error('Unsupported token type');
    const validate = value => {
      if (typeof value !== 'string' || value.length > 200) throw new Error('Invalid token value');
      if (alias.test(value)) return value;
      if (t.type === 'color' && /^#[a-f0-9]{6}$/i.test(value)) return value;
      if (t.type === 'dimension' && /^(?:0|(?:\d{1,3})(?:\.\d{1,4})?(?:rem|em|px))$/.test(value)) return value;
      if (t.type === 'dimension' && /^clamp\(\d{1,3}(?:\.\d{1,4})?(?:rem|em|px), ?\d{1,3}(?:\.\d{1,4})?vw, ?\d{1,3}(?:\.\d{1,4})?(?:rem|em|px)\)$/.test(value)) return value;
      if (t.type === 'fontFamily' && /^[a-zA-Z][a-zA-Z0-9 ,"'_-]*$/.test(value)) return value;
      throw new Error('Unsafe or unsupported token value');
    };
    tokens[key] = { type:t.type, value:validate(t.value) };
    if (t.modes !== undefined) {
      obj(t.modes, ['dark']);
      if (typeof t.modes.dark !== 'string') throw new Error('Dark mode needs a value');
      tokens[key].modes = { dark:validate(t.modes.dark) };
    }
  }
  for (const [key, type] of Object.entries({ 'color.ink':'color','color.paper':'color','color.accent':'color','color.accent-strong':'color','color.muted':'color','color.bg':'color','color.surface':'color','color.text':'color','color.link':'color','color.focus':'color','color.border':'color','font.sans':'fontFamily','font.serif':'fontFamily','space.xs':'dimension','space.sm':'dimension','space.md':'dimension','space.lg':'dimension','space.xl':'dimension','radius.sm':'dimension','radius.md':'dimension','layout.content':'dimension','layout.wide':'dimension','layout.narrow':'dimension' })) {
    if (tokens[key]?.type !== type) throw new Error(`Missing typed semantic token: ${key}`);
  }
  const resolve = mode => {
    const result = Object.create(null), visiting = new Set();
    const get = key => {
      if (result[key] !== undefined) return result[key];
      if (!tokens[key]) throw new Error(`Missing alias target: ${key}`);
      if (visiting.has(key)) throw new Error(`Cyclic alias: ${key}`);
      visiting.add(key);
      const t = tokens[key], value = mode === 'dark' ? t.modes?.dark ?? t.value : t.value;
      const ref = alias.exec(value)?.[1];
      if (ref && tokens[ref]?.type !== t.type) throw new Error('Alias type mismatch or unknown target');
      result[key] = ref ? get(ref) : value;
      visiting.delete(key);
      return result[key];
    };
    for (const key of Object.keys(tokens)) get(key);
    return result;
  };
  return { document:{version:1,id:d.id,revision:d.revision,tokens}, light:resolve('light'), dark:resolve('dark') };
}
export function compileTokens(input) {
  const parsed = parseTokens(input), {light, dark, document:d} = parsed;
  const declarations = values => Object.entries(values).map(([key, value]) => {
    const name = key.replaceAll('.', '-');
    const rendered = key.startsWith('layout.') && key !== 'layout.full' ? `min(100% - 2rem, ${value})` : value;
    return `  --${name}: ${rendered};` + (key.startsWith('color.') ? `\n  --wp--preset--color--${key.slice(6).replaceAll('.', '-')}: ${value};` : '');
  }).join('\n');
  const css = `/* Generated from design.tokens.json; edit that source or custom.css. */\n:root {\n  color-scheme: light;\n${declarations(light)}\n  --space-2xs: .25rem;\n  --space-section: clamp(3rem, 8vw, 6rem);\n  --shadow-soft: 0 .5rem 2rem rgb(24 33 47 / .12);\n}\n@media (prefers-color-scheme: dark) {\n:root:not([data-theme="light"]) {\n  color-scheme: dark;\n${declarations(dark)}\n}\n}\n:root[data-theme="dark"] {\n  color-scheme: dark;\n${declarations(dark)}\n}\n:root {\n  --wa-font-family-body: var(--font-sans);\n  --wa-color-surface-default: var(--color-surface);\n  --wa-color-surface-border: var(--color-border);\n  --wa-color-text-normal: var(--color-text);\n  --wa-color-text-quiet: var(--color-muted);\n  --wa-focus-ring: 3px solid var(--color-focus);\n  --wa-panel-border-radius: var(--radius-md);\n  --wa-space-m: var(--space-md);\n}\n`;
  const palette = Object.keys(d.tokens).filter(k => k.startsWith('color.')).map(k => ({slug:k.slice(6).replaceAll('.', '-'),name:k.slice(6).replaceAll('-',' '),color:light[k]}));
  const themeJson = {
    $schema:'https://schemas.wp.org/wp/6.6/theme.json',version:3,
    settings:{appearanceTools:false,color:{custom:false,customDuotone:false,customGradient:false,defaultPalette:false,defaultGradients:false,defaultDuotone:false,palette},layout:{contentSize:light['layout.content'],wideSize:light['layout.wide']},spacing:{customSpacingSize:false,defaultSpacingSizes:false,spacingScale:{steps:0},spacingSizes:['xs','sm','md','lg','xl'].map(slug=>({slug,name:slug.toUpperCase(),size:light['space.'+slug]}))},typography:{customFontSize:false,defaultFontSizes:false,fontFamilies:['sans','serif'].map(slug=>({slug,name:slug,fontFamily:light['font.'+slug]})),fontSizes:[{slug:'base',name:'Base',size:'1rem'}]}},
    styles:{color:{background:'var(--color-bg)',text:'var(--color-text)'},typography:{fontFamily:'var(--font-sans)',fontSize:'1rem',lineHeight:'1.6'},elements:{link:{color:{text:'var(--color-link)'}}}}
  };
  return { ...parsed, css, themeJson };
}
