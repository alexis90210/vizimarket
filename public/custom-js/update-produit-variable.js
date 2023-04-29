let produitId = document
  .getElementById("producttitre")
  .getAttribute("product-id");
let url = `${window.location.protocol}//${window.location.host}/api/v1/retrieve/produit-variable/${produitId}`;
let body = {
  method: "GET",
  headers: {
    "Content-Type": "application/json",
  },
};
const urlGetCategorieOfProductSimple = `${window.location.protocol}//${window.location.host}/api/v1/get/checked/produitSimplecategorie/${produitId}`;

// LOAD CATEGORIE

fetch(urlGetCategorieOfProductSimple)
  .then((response) => response.json())
  .then((data) => {
    console.log(data);

    let categorie = data.message;

    categorie.forEach((el) => {
      document.getElementById("categorie_" + el.id).setAttribute("checked", "");
    });
  })
  .catch((error) => console.error(error));

// LOAD DATA
fetch(url, body)
  .then((res) => res.json())
  .then((data) => {
    console.log(data);
    if (data) loadData(data);
  })
  .catch((error) => console.log(error));

function loadData(data) {
  // load attributs

  var p = document.getElementById("preview-cover-produit-file");
  p.src = data.imageCouverture;

  var b = document.getElementById("finalPhotoFullBox2");
  b.innerHTML = null;

  data.gallerie.map((el) => {
    b.innerHTML += `<div class="col-lg-3">
            <img src="${el.image}" alt="" srcset="${el.image}" width="100px" class="rounded shadow-sm">
            <button class="btn btn-close btn-primary"></button>
        </div>`;
  });

  console.log(data);
}

// addAttribut
function addServerAttribut(type_attr, type, valeur) {
  let box_attr = document.querySelector("#box-attributs");

  let attribut = `<div class="border border-3 p-3 mb-2 border-rounded border-success" attribut="${type_attr}"
    style="border: 1px dashed #43C590 !important" id="selected-attribut-${type_attr}">
  <div class="d-flex justify-content-between">
      <h4 class="header-title">Type ${type_attr}</h4>
      <p class="text-danger" style="cursor:pointer;" title="supprimer l'attribut ${type_attr}" onclick="deleteAttribut('#selected-attribut-${type_attr}', '${type_attr}')">supprimer</p>
  </div>

  <div class="row">
    <div class="col-lg-9">
      <select name="produit-attribut-${type_attr}" id="produit-attribut-${type_attr}" class="form-select select2 col-lg-12">`;

        if (type == "Couleur") {
            attribut += `<option value="${valeur};${titre}" title="${titre}">${titre}</option>`;
        }

        if (type == "Taille") {
            attribut += `<option value="${valeur}" title="${titre}">${valeur}</option>`;
        }

        attribut += `
    </select> 
    </div>
  </div>`;

  box_attr.innerHTML += attribut;
}
