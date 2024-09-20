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

document.querySelector('.proposal-lines').addEventListener('change', function(event) {
  if (event.target && event.target.matches('select[name="activity_clasification[]"]')) {
      let selects = document.querySelectorAll('select[name="activity_clasification[]"]');
      let selectedIndex = Array.from(selects).indexOf(event.target);
      let correspondingClasification = document.querySelectorAll('select[name="clasification[]"]')[selectedIndex];
      correspondingClasification.innerHTML = '';
      const option = document.createElement('option');
      option.value = '';
      option.text = '- Clasificación -';
      correspondingClasification.appendChild(option);
      if(event.target.value !== ""){
        fetchclasifications(event.target.value).then(clasifications => {
            correspondingClasification.appendChild(option);
            clasifications.forEach(cla => {
                const option = document.createElement('option');
                option.value = cla.id;
                option.text = cla.name;
                correspondingClasification.appendChild(option);
            });
        });
      }
  }
});

function fetchclasifications(clasificacionId) {
  return fetch('/barrios-cloud/proposal-clasification/' + clasificacionId)
      .then(response => response.json());
}

