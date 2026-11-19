const date = new Intl.DateTimeFormat(undefined, { dateStyle: "medium" }).format(new Date());

export function createPassportStamp({ shape, top, title, bottom }) {
  const stamp = document.createElement("div");
  stamp.className = "travel-stamp travel-stamp--" + (["round", "rectangle", "oval"].includes(shape) ? shape : "round");
  for (const [tag, text] of [["span", top], ["strong", title], ["span", bottom]]) {
    if (!text?.trim()) continue;
    const line = document.createElement(tag);
    line.textContent = text.replaceAll("{date}", date);
    stamp.append(line);
  }
  return stamp;
}
