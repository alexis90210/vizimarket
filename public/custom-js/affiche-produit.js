console.log("le ficher est bien charge");

// Récupère la partie de la route qui suit le nom de domaine
var pathname = window.location.pathname;

// Sépare les différents éléments de la route
var pathElements = pathname.split("/");

// Récupère le premier élément de la route
var id = pathElements[3];

const getInfoUrl =
  window.location.protocol +
  "//" +
  window.location.host +
  "/api/v1/affiche/produit/" +
  id;

fetch(getInfoUrl)
  .then((response) => response.json())
  .then((data) => {
    console.log("on est dans la requette----------");
    console.log(data.message);
    let infosProduit = data.message;

    document.getElementById("producttitre").value = infosProduit.titre;

    document.getElementById("productQte").value = infosProduit.quantite;

    document.getElementById("categories").value = infosProduit.sous_categorie;

    document.getElementById("AccepteGaranti").checked =
      infosProduit.productAcceptGaranti;

    document.getElementById("productdesc").value = infosProduit.description;

    document.getElementById("selectEtiquette").value = infosProduit.etiquette;
    console.log("----------------------TEST ICI____________________");

    const test = document.getElementById("taille");
    console.log(test);
    console.log("----------------------FIN TEST____________________");
    document.getElementById("Poids").value = infosProduit.expedition.poids;

    document.getElementById("Longueur").value = infosProduit.expedition.longeur;

    document.getElementById("Largeur").value = infosProduit.expedition.poids;

    document.getElementById("Hauteur").value = infosProduit.expedition.hauteur;

    //$("#productdesc").val("Hello World");

    if (infosProduit.est_un_produit_simple) {
      document.getElementById("EstDisponible").checked =
        infosProduit.produit_simple.disponibilite == "oui" ? true : false;

      document.getElementById("taille").setAttribute("disabled", "");

      document.getElementById("productCaracteristic").value =
        infosProduit.caracteristique;

      document.getElementById("Tarif-regulier-variable").value =
        infosProduit.produit_simple.tarifRegulier;

      document.getElementById("Tarif-promo-variable").value =
        infosProduit.produit_simple.tarifPromo;

      document.getElementById("headerAction").classList.add("d-none");

      document.getElementById("body-variation").innerHTML = `<tr>
      <td>#</td>
      <td>${document.getElementById("Tarif-regulier-variable").value}</td>
      <td>${document.getElementById("Tarif-promo-variable").value}</td>
      <td>${infosProduit.produit_simple.disponibilite}</td>
      </tr>`;

      document.getElementById("headerTaille").classList.add("d-none");
    } else {
    }

    console.log(infosProduit);

    infosProduit.couleurs.forEach((element) => {
      document.getElementById("body-more-variation").innerHTML = `
      <tr>
        <td>#</td>
        <td>${element.libelle}</td>
        <td>${element.couleur}</td>
        <td id="tooltip-container1">
        <a href="javascript:void(0);" class="me-3 text-primary" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
            <i class="mdi mdi-pencil font-size-18"></i>
        </a >
        <a href="javascript:void(0);" class="text-danger" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
            <i class="mdi mdi-trash-can font-size-18"></i>
        </a>
        </td>
      </tr>
      
      `;
    });

    //Les fonctions
  });

function editSimple() {
  document.getElementById("body-simple").innerHTML = `<tr>
  <td>1</td>
  <td>${document.getElementById("Tarif-regulier-simple").value}</td>
  <td>${document.getElementById("Tarif-promo-simple").value}</td>
  <td>${
    document.getElementById("EstDisponibleSimple").checked ? "oui" : "non"
  }</td>
  </tr>`;
}

function onModifie() {
  document.getElementById("body-variation").innerHTML = `<tr>
      <td>#</td>
      <td>${document.getElementById("Tarif-regulier-variable").value}</td>
      <td>${document.getElementById("Tarif-promo-variable").value}</td>
      <td>${infosProduit.produit_simple.disponibilite}</td>
      </tr>`;
}

// function addMoreVariation() {
//   document.querySelector("#tab-content2").style = "height:100% !important";
//   const libCouleur = document.getElementById("lib-couleur");

//   const couleur = document.getElementById("couleur");

//   // let i = moreVariation.length;

//   // i++;

//   let objectForMoreVariation = {
//     id: i,
//     libelle: libCouleur.value,
//     couleur: couleur.value,
//   };

//   moreVariation.push(objectForMoreVariation);

//   libCouleur.value = "";

//   refreshTableauForMoreVariation(moreVariation);

//   afficherCouleur(moreVariation);
// }
