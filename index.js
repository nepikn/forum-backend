"use strict";
console.log("s");
document.querySelector("form").onsubmit = async (e) => {
  e.preventDefault();

  const res = await fetch("comment.php", { method: "post" });

  if (res.status == 304) {
    document
      .querySelector("article")
      .insertAdjacentHTML("afterend", "<article>ok</article>");
  }
};
