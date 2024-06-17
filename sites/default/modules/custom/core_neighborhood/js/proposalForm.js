const lineBase = document.querySelector("#line-base");
const addRow = document.querySelector(".add-row");
const proposalLines = document.querySelector(".proposal-lines");
if (addRow) {
  addRow.addEventListener("click", function (e) {
    addLine();
  });
}
proposalLines.addEventListener("click", (event) => {
  if (event.target.classList.contains("remove-row")) {
    removeLine(event.target);
  }
});
function addLine() {
  const duplicate = lineBase.cloneNode(true);
  duplicate.classList.remove("hidden");
  duplicate.classList.add("proposal-lines__row");
  proposalLines.appendChild(duplicate);
}
function removeLine(e) {
  e.closest(".proposal-lines__row").remove();
}
