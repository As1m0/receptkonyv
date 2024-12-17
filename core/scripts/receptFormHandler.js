
//range handler

document.getElementById("nehezseg").addEventListener("input", () => {
  document.getElementById("nehezseg-sign").innerHTML = document.getElementById("nehezseg").value;

  if (document.getElementById("nehezseg").value <= 3) {
    document.getElementById("nehezseg-sign").style = "color: green";
  } else if (document.getElementById("nehezseg").value > 3 && document.getElementById("nehezseg").value <= 7) {
    document.getElementById("nehezseg-sign").style = "color: blue";
  } else {
    document.getElementById("nehezseg-sign").style = "color: orange";
  }
});





//ingredients handler

let ingredientIndex = 1;

document.getElementById('addIngredient').addEventListener('click', () => {
  const ingredientsContainer = document.getElementById('ingredients');
  const newRow = document.createElement('div');
  newRow.className = 'col-12 row align-items-end ingredient-row';
  newRow.innerHTML = `
        <div class="col-md-4">
          <label for="ingredientName${ingredientIndex}" class="form-label">Hozzávaló neve</label>
          <input type="text" class="form-control input-filed" id="ingredientName${ingredientIndex}" name="ingredients[${ingredientIndex}][nev]" placeholder="pl. cukor.." required>
        </div>
        <div class="col-md-3">
          <label for="ingredientAmount${ingredientIndex}" class="form-label">Mennyiség</label>
          <input type="number" class="form-control input-filed" id="ingredientAmount${ingredientIndex}" name="ingredients[${ingredientIndex}][mennyiseg]" placeholder="pl. 100.." required>
        </div>
        <div class="col-md-3">
          <label for="ingredientUnit${ingredientIndex}" class="form-label">Mértékegység</label>
          <select class="form-select" id="ingredientUnit${ingredientIndex}" name="ingredients[${ingredientIndex}][mertekegyseg]">
            <option value="gramm">gramm</option>
            <option value="dkg">dkg</option>
            <option value="ml">ml</option>
            <option value="liter">liter</option>
            <option value="db">db</option>
            <option value="ek">ek</option>
            <option value="tk">tk</option>
            <option value="bögre">bögre</option>
            <option value="csomag">csomag</option>
            <option value="csipet">csipet</option>
            <option value="csokor">csokor</option>
            <option value="csésze">csésze</option>
            <option value="szelet">szelet</option>
          </select>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-outline-danger remove-ingredient">Törlés</button>
        </div>
      `;
  ingredientsContainer.appendChild(newRow);
  ingredientIndex++;

  // Show remove buttons
  document.querySelectorAll('.remove-ingredient').forEach(btn => btn.classList.remove('d-none'));
});

document.getElementById('ingredientsForm').addEventListener('click', (e) => {
  if (e.target.classList.contains('remove-ingredient')) {
    e.target.closest('.ingredient-row').remove();
    // Hide remove buttons if only one row remains
    if (document.querySelectorAll('.ingredient-row').length === 1) {
      document.querySelector('.remove-ingredient').classList.add('d-none');
    }
  }
});




//validation
/*
function validateForm(event) {

  let receptName = document.getElementById("name");
  let ingredients = document.getElementById("ingredients");
  let descr = document.getElementById("leiras");
  let inputFileds = document.getElementsByClassName("input-filed");

  event.preventDefault();

  if (!receptName.value.trim()) {
    alert("Recept neve");
    return false;
  }

  if (!descr.value.trim()) {
    alert("Leiras");
    return false;
  }

  if (ingredients.childElementCount < 2) {
    alert("Legalább 2 hozzávaló szükséges");
    return false;
  }

  for (let i=0; i<inputFileds.length; i++){
    if (inputFileds[i].value == "")
    {
      alert("Minden hozzávaló minden értéke legyen megadva");
      return false;
    }
  }

  document.getElementById("receptFeltoltes").submit();

}
*/