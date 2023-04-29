// add marque

// partie ajout de la marque dans le nouveau produit

const url =
  window.location.protocol + "//" + window.location.host + "/api/v1/add/marque";
function addMarque() {
  console.log("on commence -----------");
  const obj = {
    nom: document.getElementById("nomMarque").value,
  };

  console.log(obj);

  fetch(url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json", // type de contenu des données envoyées
    },
    body: JSON.stringify(obj), // les données à envoyer dans la requête
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);

      console.log(data);
      let response = data.message;
      document.getElementById("selectMarque").innerHTML = `
        <option disabled selected>Select une marque</option>
      `;
      response.forEach((element) => {
        document.getElementById("selectMarque").innerHTML += `
          <option value="${element.id}">${element.nom}</option>
        `;
      });
    })
    .catch((error) => console.error(error));
}

// handle image preview
let finalPhotoCover = document.getElementById("finalPhotoCover");
finalPhotoCover.addEventListener("change", (e) => {
  var file = e.target.files[0];
  document.getElementById("preview-cover-produit-file").src =
    window.URL.createObjectURL(file);
});

let finalPhotoFull = document.getElementById("finalPhotoFull");
finalPhotoFull.addEventListener("change", (e) => {
  var files = e.target.files;
  let finalPhotoFullBox = document.getElementById("finalPhotoFullBox");
  finalPhotoFullBox.innerHTML = null;
  for (let i = 0; i < files.length; i++) {
    finalPhotoFullBox.innerHTML += `<div class="col-lg-3 p-2">
    <img width="100" alt="" class="rounded" src="${window.URL.createObjectURL(
      files[i]
    )}">
    </div>`;
  }
});

function chargeImage() {
  const idPoduitSimple = document.getElementById("idProduitSimple")?.value;
  const urlGetGallerieOfProductSimple =
    window.location.protocol +
    "//" +
    window.location.host +
    "/api/v1/get/Gallerie/produitSimplecategorie/" +
    idPoduitSimple;

  fetch(urlGetGallerieOfProductSimple)
    .then((response) => response.json())
    .then((data) => {
      // console.log(data);
      let images = data.message;
      var files = images;
      let finalPhotoFullBox = document.getElementById("finalPhotoFullBoxEdit");
      finalPhotoFullBox.innerHTML = null;
      for (let i = 0; i < files.length; i++) {
        finalPhotoFullBox.innerHTML += `<div class="col-lg-3 p-2">
    <img width="100" alt="" class="rounded" src="${files[i].image}">
    <div class="btn btn-link btn-close" onclick="deleteImage(${files[i].id})">
      <i class="fas fa-times"></i>
      <span class="sr-only">Fermer</span>
    </div>
    </div>`;
      }
    });
}

function deleteImage(id) {
  const parent = document.getElementById("idProduitSimple").value;
  const urlDeleteGetGallerieOfProductSimple =
    window.location.protocol +
    "//" +
    window.location.host +
    "/api/v1/remove/" +
    parent +
    "/Gallerie/" +
    id;
  console.log("en cours de suppression--------");

  fetch(urlDeleteGetGallerieOfProductSimple)
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      let images = data.message;
      var files = images;
      let finalPhotoFullBox = document.getElementById("finalPhotoFullBoxEdit");
      finalPhotoFullBox.innerHTML = null;
      for (let i = 0; i < files.length; i++) {
        finalPhotoFullBox.innerHTML += `<div class="col-lg-3 p-2">
    <img width="100" alt="" class="rounded" src="${files[i].image}">
    <button class="btn btn-link btn-close" onclick="deleteImage(${files[i].id})">
      <i class="fas fa-times"></i>
      <span class="sr-only">Fermer</span>
    </button>
    </div>`;
      }
    });
}

chargeImage();
