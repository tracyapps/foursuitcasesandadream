import { createPassportStamp } from "./passport-stamp.js";

const collection = document.querySelector(".acf-field[data-name='travel_passport_stamps']");
if (collection) {
  function update(row) {
    const preview = row.querySelector("[data-passport-preview]");
    if (!preview || row.classList.contains("acf-clone")) return;
    const input = (name, type = "input") => row.querySelector(".acf-field[data-name='" + name + "'] " + type);
    const stamp = createPassportStamp({
      shape: input("shape", "select")?.value,
      top: input("top")?.value,
      title: input("title")?.value || "Main line",
      bottom: input("bottom")?.value,
    });
    preview.replaceChildren(stamp);
    if (!input("enabled", "input[type='checkbox']")?.checked) {
      const status = document.createElement("p");
      status.textContent = "Excluded from rotation";
      preview.append(status);
    }
  }
  collection.querySelectorAll(".acf-row").forEach(update);
  for (const event of ["input", "change"]) {
    collection.addEventListener(event, ({ target }) => {
      const row = target.closest(".acf-row");
      if (row) update(row);
    });
  }
  window.acf?.addAction("append", element => {
    element.toArray().forEach(node => {
      if (!collection.contains(node)) return;
      if (node.matches(".acf-row")) update(node);
      node.querySelectorAll(".acf-row").forEach(update);
    });
  });
}
