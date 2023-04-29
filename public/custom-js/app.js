console.log("le ficher app js est bien charge");

document.querySelector("#tab-content2")?.style = "height:100% !important";

document.getElementById("taille").setAttribute("disabled", "");

document.getElementById("tailleProduitSimple").setAttribute("disabled", "");

document.getElementById("enteteTaille").classList.add("d-none");

fetch(
  window.location.protocol +
    "//" +
    window.location.host +
    "/api/v1/get/categories"
)
  .then((response) => response.json())
  .then((data) => {
    const categories = data.message;

    const select = document.getElementById("categories");
    categories.forEach((el) => {
      select.innerHTML += `
        <option value="${el.id}">${el.libelle}</option>
        `;
    });
  });

fetch(
  window.location.protocol +
    "//" +
    window.location.host +
    "/api/v1/get/etiquettes"
)
  .then((response) => response.json())
  .then((data) => {
    let etiquettes = data.message;
    let selectEtiquette = document.getElementById("selectEtiquette");

    etiquettes.forEach((element) => {
      selectEtiquette.innerHTML += `<option value='${element.id}'>${element.libelle}</option>`;
    });
  });

//add variation
var produitVariables = [];

var produitsimples = [];

var taillesARemplir = [];

var moreVariation = [];

var partiefinale = [];

var tailles = [];

var couleurs = [];

const urlAddProduit =
  window.location.protocol +
  "//" +
  window.location.host +
  "/api/v1/add/produit";

function afficherTaille(_tailles) {
  tailles = _tailles; // pour le redre global

  const tailleProduit = document.getElementById("tailleProduitSimple");

  if (_tailles.length > 0) {
    tailleProduit.innerHTML = "";
    _tailles.forEach((el) => {
      tailleProduit.innerHTML += `
                <option value='${el.id}'>${el.taille}
                </option>`;
    });
  }
}

function afficherCouleur(_couleurs) {
  couleurs = _couleurs;

  const selectCouleur = document.getElementById("selectCouleur");

  if (_couleurs.length > 0) {
    selectCouleur.innerHTML = "";
    _couleurs.forEach((el) => {
      selectCouleur.innerHTML += `
                 <option value='${el.id}'>${el.libelle}
                 </option>`;
    });
  }
}

function refreshVariation(array) {
  const bodyVariation = document.getElementById("body-variation");
  if (array.length > 0) {
    bodyVariation.innerHTML = "";
    array.forEach((element) => {
      bodyVariation.innerHTML += `<tr>
      <td>${element.id}</td>
      <td>${element.taille}</td>
      <td>${element.tarifRegulier + " $"}</td>
      <td>${element.tarifPromo + " $"}</td>
      <td>${element.disponibilite}</td>
      <td id="tooltip-container1">
            <a href="javascript:void(0);" class="me-3 text-primary" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                <i class="mdi mdi-pencil font-size-18"></i>
            </a>
            <a href="javascript:void(0);" class="text-danger" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                <i class="mdi mdi-trash-can font-size-18"></i>
            </a>
      </td>
    </tr>`;
    });
  }
}

function refreshTableauProduitSimple(array) {
  const bodyVariation = document.getElementById("body-variation");
  if ((array.length = 1)) {
    bodyVariation.innerHTML = "";
    array.forEach((element) => {
      bodyVariation.innerHTML += `<tr>
      <td>${element.id}</td>
      <td>${element.tarifRegulier}$</td>
      <td>${element.tarifPromo}</td>
      <td>${element.disponibilite}</td>
      <td id="tooltip-container1">
            <a href="javascript:void(0);" class="me-3 text-primary" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                <i class="mdi mdi-pencil font-size-18"></i>
            </a>
            <a href="javascript:void(0);" class="text-danger" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                <i class="mdi mdi-trash-can font-size-18"></i>
            </a>
      </td>
    </tr>`;
    });
  }
}

function refreshTableauForMoreVariation(array) {
  var bodyMoreVariation = document.querySelector("#body-more-variation");
  if (array.length > 0) {
    bodyMoreVariation.innerHTML = "";
    array.map((element, i) => {
      bodyMoreVariation.innerHTML += `<tr><td>${i + 1}</td><td>${
        element.libelle
      }</td><td>${element.couleur}</td>
             <td id="tooltip-container1">
            <a href="javascript:void(0);" class="me-3 text-primary" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                <i class="mdi mdi-pencil font-size-18"></i>
            </a>
            <a href="javascript:void(0);" class="text-danger" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                <i class="mdi mdi-trash-can font-size-18"></i>
            </a>
            </td>
            </tr>`;
    });
  }
}

function pushToArrayWithoutDuplicateSize(objectToPush, myArray) {
  let isDuplicate = false;
  for (let i = 0; i < myArray.length; i++) {
    if (myArray[i].taille === objectToPush.taille) {
      isDuplicate = true;
      break;
    }
  }
  if (!isDuplicate) {
    myArray.push(objectToPush);
    produitVariables = myArray;
    refreshVariation(produitVariables);
    afficherTaille(produitVariables);
  }
}

function addVariation() {
  document.querySelector("#tab-content2").style = "height:100% !important";

  let choix = document.querySelector('input[name="choix"]:checked').value;

  if (choix == "produitsimple") {
    const estIlDisponiblle = document.getElementById("EstDisponible");
    const taille = document.getElementById("taille");
    const tarifRegulier = document.getElementById("Tarif-regulier");

    const tarifPromo = document.getElementById("Tarif-promo");

    const disponibilite = estIlDisponiblle.checked ? "oui" : "non";

    let i = produitVariables.length + 1;
    const newObject = {
      id: i++,
      taille: taille.value,
      disponibilite: disponibilite,
      tarifRegulier: tarifRegulier.value,
      tarifPromo: tarifPromo.value,
    };

    produitsimples.push(newObject);

    refreshTableauProduitSimple(produitsimples);

    document.getElementById("btn-ajouter-produit").setAttribute("disabled", "");

    //vider les champs

    document.getElementById("EstDisponible").checked = "false";
    document.getElementById("taille").value = "";
    document.getElementById("Tarif-regulier").value = "";

    document.getElementById("Tarif-promo").value = "";
    document.querySelector('input[name="choix"]').setAttribute("disabled");
  } else {
    const estIlDisponiblle = document.getElementById("EstDisponible");
    const taille = document.getElementById("taille");
    const tarifRegulier = document.getElementById("Tarif-regulier");

    const tarifPromo = document.getElementById("Tarif-promo");

    const disponibilite = estIlDisponiblle.checked ? "oui" : "non";

    let i = produitVariables.length;
    const newObject = {
      id: i++,
      taille: taille.value,
      disponibilite: disponibilite,
      tarifRegulier: tarifRegulier.value,
      tarifPromo: tarifPromo.value,
    };

    pushToArrayWithoutDuplicateSize(newObject, produitVariables);

    taille.value = "";
    estIlDisponiblle.checked = false;
    tarifRegulier.value = "";
    tarifPromo.value = "";
    document.querySelector('input[name="choix"]').setAttribute("disabled");
  }
}

function addSimple() {
  document.querySelector("#tab-content2").style = "height:100% !important";

  const estIlDisponiblle = document.getElementById("EstDisponibleSimple");

  const tarifRegulier = document.getElementById("Tarif-regulier-simple");

  const tarifPromo = document.getElementById("Tarif-promo-simple");

  const disponibilite = estIlDisponiblle.checked ? "oui" : "non";

  let i = 0;
  const newObject = {
    id: i++,
    disponibilite: disponibilite,
    tarifRegulier: tarifRegulier.value,
    tarifPromo: tarifPromo.value,
  };

  produitsimples.push(newObject);

  estIlDisponiblle.checked = false;

  tarifRegulier.value = "";

  tarifPromo.value = "";

  refreshTableauProduitSimple(produitsimples);
}

function desactiveTaille() {
  document.getElementById("taille").setAttribute("disabled", "");
  document.getElementById("tailleProduitSimple").setAttribute("disabled", "");
  document.getElementById("enteteTaille").classList.add("d-none");
}

function activeTaille() {
  document.getElementById("taille").removeAttribute("disabled");
  document.getElementById("tailleProduitSimple").removeAttribute("disabled");
  document.getElementById("enteteTaille").classList.remove("d-none");
}

//Informations concernant l'expedition

const poids = document.getElementById("Poids");

const longeur = document.getElementById("Longeur");

const largeur = document.getElementById("Largeur");

const hauteur = document.getElementById("Hauteur");

function addMoreVariation() {
  document.querySelector("#tab-content2").style = "height:100% !important";
  const libCouleur = document.getElementById("lib-couleur");

  const couleur = document.getElementById("couleur");

  let i = moreVariation.length;

  i++;

  let objectForMoreVariation = {
    id: i,
    libelle: libCouleur.value,
    couleur: couleur.value,
  };

  moreVariation.push(objectForMoreVariation);

  libCouleur.value = "";

  refreshTableauForMoreVariation(moreVariation);

  afficherCouleur(moreVariation);
}

//Partie final avec des photos

var gallerie = [];

let finalPhoto = document.getElementById("finalPhoto");

var base64StringPhoto = "";

finalPhoto.addEventListener("change", (e) => {
  let filesArray = e.target.files;

  let finalTailleProduitSimple = document.getElementById(
    "tailleProduitSimple"
  ).value;
  // recuperation du libelle de la taille
  tailles.map((e, i) => {
    if (e.id == finalTailleProduitSimple) {
      finalTailleProduitSimple = e.taille;
    }
  });

  let finalSelectCouleur = document.getElementById("selectCouleur").value;
  // recuperation du libelle de la couleur
  couleurs.map((e, i) => {
    if (e.id == finalSelectCouleur) {
      finalSelectCouleur = e.libelle;
    }
  });

  for (let flIndex = 0; flIndex < filesArray.length; flIndex++) {
    const file = filesArray[flIndex];
    if (file) {
      let fileReader = new FileReader();
      fileReader.onload = (base) => {
        base64StringPhoto = base.target.result;

        gallerie.push({
          taille: finalTailleProduitSimple,
          couleur: finalSelectCouleur,
          image: base64StringPhoto,
        });
      };
      fileReader.readAsDataURL(file);
    }
  }
});

var afficheImage = () => {
  var tableImage = document.querySelector("#table-images");

  tableImage.innerHTML = "";

  gallerie.map((item, index) => {
    tableImage.innerHTML += `<tr>
            <td>${index + 1}</td>
            <td>${item.taille}</td>
            <td>${item.couleur}</td>
            <td>
                <img src="${item.image}" width="50px">
            </td>
            <td id="tooltip-container1">
                <a href="javascript:void(0);" class="me-3 text-primary" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                    <i class="mdi mdi-pencil font-size-18"></i>
                </a>
                <a href="javascript:void(0);" class="text-danger" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                    <i class="mdi mdi-trash-can font-size-18"></i>
                </a>
            </td>
        </tr>`;
  });

  finalPhoto.value = null;
};

var addPhoto = () => {
  if (gallerie.length == 0) {
    Lobibox.notify("danger", {
      title: "Notification",
      msg: "champs taille, couleurs sont neccessaire",
      position: "bottom right",
      delay: 5000,
    });
  } else {
    afficheImage();
  }
};

// Objet final a envoyer via API
//$("productdesc").trumbowyg("html")
//$("productCaracteristic").trumbowyg("html")
function finalValidation() {
  var finalPorduitObject = {
    etiquette_id: document.getElementById("selectEtiquette").value,
    titre: document.getElementById("producttitre").value,
    sous_categorie_id: document.getElementById("categories").value,
    description: "la Description",
    quantite: document.getElementById("productQte").value,
    caracteristique: "Carateristique",
    specification: "specif. du produit",
    mediaDescription:
      "descript du media fichier ( image / video ) a mettre en avant",
    accepte_garanti: document.getElementById("AccepteGaranti").checked,
    accepte_livraison: document.getElementById("AccepteLivraison").checked,
    min_prix_pour_reservation_gratuite: document.getElementById(
      "MinPrixPourReservationGratuite"
    ).value,
    prix_livraison: document.getElementById("PrixLivraison").value,
    marque_id: "1",
    couleurs: couleurs,
    information_livraison_a_savoir: " info. du produit",
    est_produit_simple: produitsimples.length > 0 ? 1 : 0,
    est_produit_variable: produitVariables.length > 0 ? 1 : 0,
    produit_simple: produitsimples.length > 0 ? produitsimples[0] : null,
    produit_variable: produitVariables,
    donnee_expedition: {
      largeur: document.getElementById("Largeur").value,
      longeur: document.getElementById("Longueur").value,
      hauteur: document.getElementById("Hauteur").value,
      poids: document.getElementById("Poids").value,
    },

    gallerie: gallerie,
  };

  // console.log(JSON.stringify(finalPorduitObject));

  fetch(urlAddProduit, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(finalPorduitObject),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);

      if (data.success) {
        alert("Produit cree avec succes");
        window.history.back();
      } else {
        alert(data.message);
      }
    })
    .catch((error) => alert(error));
}