var _taille = {
  terme: null,
  attribut: null,
};

var _couleur = {
  terme: null,
  attribut: null,
};

var produitId = document
  .querySelector("#produit-id")
  .getAttribute("produit-id");
var attributLen = document
  .querySelector("#produit-id")
  .getAttribute("attribut-length");

function taille(obj) {
  _taille = obj;
  console.log(_taille);
}

function couleur(obj) {
  _couleur = obj;
  console.log(_couleur);
}

function clearFiltre() {
  _taille = {
    terme: null,
    attribut: null,
  };

  _couleur = {
    terme: null,
    attribut: null,
  };
}

setInterval(() => {
  if (_taille.terme && _couleur.terme) {
    let objFinal = { idProduit: produitId, terme: [_taille, _couleur] };
    $("#produit-id").waitMe({
      effect: "bounce",
      text: "",
      bg: "rgba(255,255,255,0.7)",
      color: "#000",
      maxSize: "",
      waitTime: -1,
      textPos: "vertical",
      fontSize: "",
      source: "",
      onClose: function () {},
    });
    clearFiltre();
    applyVariation(objFinal);
  }
}, 1000);

function applyVariation(objFinal) {
  const url = `${window.location.protocol}//${window.location.host}/api/v1/recup/variation`;

  console.log(JSON.stringify(objFinal));

  fetch(url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(objFinal),
  })
    .then((response) => response.json())
    .then((data) => {
      $("#produit-id").waitMe("hide");
      console.log(data);

      if (data.imageCouverture) {
        document.getElementById("product-form-product-qty").classList.remove('d-none')
        let z = document.getElementById("produit-image-couverture");
        z.src = data.imageCouverture;
        z.removeAttribute("data-zoom-image");
        z.getAttribute("data-zoom-image", data.imageCouverture);
      }

      if (data.galleries) {
        var ga = document.getElementById("gallerie-box-produit");
        ga.innerHTML = null;

        data.galleries.map((gallerie, i) => {
          document.getElementById("produit-promo-price").innerText =
            data.prixPromo;
          document.getElementById("produit-prix-regulier").innerText =
            data.prix;
          
          document.getElementById("product-price-toggle").classList.remove('d-none')
          
          if (i == 0) {
            ga.innerHTML += `<div class="product-thumb active">
          <img src="${gallerie.image}" alt="product thumbnail" width="118" height="135" />
        </div>`;
          } else {
            ga.innerHTML += `<div class="product-thumb">
          <img src="${gallerie.image}" alt="product thumbnail" width="118" height="135" />
        </div>`;
          }
        });
      } else {
        document.getElementById("product-price-toggle").classList.add('d-none')
        document.getElementById("product-form-product-qty").classList.add('d-none')
        
        spop("Aucune variation correspondante, ", "warning")
      }
    })
    .catch((error) => {
      console.log(error);
    });
}
