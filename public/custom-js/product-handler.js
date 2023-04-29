var toggleProduit = (value) => {
  console.log("type :", value);
  var simple = document.querySelector("#psimple");
  var variable = document.querySelector("#pvariable");

  if (value == 1) {
    console.log("est simple");
    // document.querySelector('#gallerie-produit-simple').classList.remove('d-none')

    if (simple.classList.contains("d-none")) simple.classList.remove("d-none");
    if (!variable.classList.contains("d-none"))
      variable.classList.add("d-none");
  }

  if (value == 2) {
    console.log("est variable ");
    // document.querySelector('#gallerie-produit-simple').classList.add('d-none')

    if (variable.classList.contains("d-none"))
      variable.classList.remove("d-none");
    if (!simple.classList.contains("d-none")) simple.classList.add("d-none");
  }
  
};

var produittype = document.querySelector("#produit-type");

toggleProduit(produittype.value);

produittype.addEventListener("change", (e) => {
  console.log(e.target.value);
  toggleProduit(e.target.value);
});

produittype.addEventListener("change", (e) => {
  console.log(e.target.value);
  toggleProduit(e.target.value);
});
