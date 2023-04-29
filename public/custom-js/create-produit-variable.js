let $formProduit = document.querySelector("#form-create-produit");

let $ButtonCreation = document.querySelector("#submit-add-produit");

$ButtonCreation.addEventListener("click", async (e) => {

  $("#submit-add-produit").waitMe({
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

  let $produitType = document.querySelector("#produit-type");

  console.log($produitType.value);

  if ($produitType.value == 1) {

    var produitTitre = $("#producttitre").val();
    var produitQuantite = $("#productQte").val();
    var produitDescription = $("#productdesc").val();
    var produitCaracteristique = $("#productcaracteristique").val();

    if (produitTitre && produitQuantite ) {
      $formProduit.submit();
    } else {
      spop('Remplir les champs', 'warning')
    }
  }

  if ($produitType.value == 2) {
    await LoadProduitVariableData();
  }
});

async function LoadProduitVariableData() {


  var produitTitre = $("#producttitre").val();
  var produitQuantite = $("#productQte").val();
  var produitDescription = $("#productdesc").val();
  var produitCaracteristique = $("#productcaracteristique").val();
  var produitType = $("#produit-type").val();

  if (!(produitTitre && produitQuantite ) ) {
    spop('Remplir les champs', 'warning')
    return;
  }



  // get Attributs

  var attributs = [];

  let attributsBox = document.querySelectorAll("#box-attributs > *");

  for (let a = 0; a < attributsBox.length; a++) {
    let attr = attributsBox[a];
    attributs.push(attr.getAttribute("attribut"));
  }

  // ajouter les termes

  var finalAttributs = [];

  attributs.map((attribut) => {
    let attributBoxTermes = document.querySelectorAll(
      `#produit-attribut-termes-${attribut} > *`
    );
    let attrTermes = [];

    for (let a = 0; a < attributBoxTermes.length; a++) {
      let termes = attributBoxTermes[a];
      attrTermes.push(termes.getAttribute("val"));
    }

    finalAttributs.push({
      attribut: attribut,
      termes: attrTermes,
    });
  });

  // variations

  var variations = [];

  var finalData = [];

  for (let b = 0; b < compositionVar.length; b++) {
    let group = current_variation[b];

    console.log("group : ", group);

    // generate variations
    let _selectedTermesVariations = "col-" + group;
    let _var = [];

    let stv = document.querySelectorAll(`.${_selectedTermesVariations} > select`);
    let typeVariation = []
    for (let c = 0; c < stv.length; c++) {
      let stvItem = stv[c];

      console.log('stvItem' ,stvItem);
      typeVariation.push( {
        attribut: stvItem.getAttribute("type"),
        terme: stvItem.value
      })
    }


      // get Photo couverture

      let imageCouverture = ''

      try {
        imageCouverture = document.getElementById(`finalPhotoCover-${group}`)
        .files[0];
        imageCouverture = await readFileAsDataURL(imageCouverture);
      } catch (error) {
        console.log('error 1', error);
      }

      // get galleries

      let ImageGalleriesNode = '';
      let ImageGalleries = [];
      try {

        ImageGalleriesNode = document.getElementById(
          `finalPhotoFull-${group}`
        ).files;     

      for (let d = 0; d < ImageGalleriesNode.length; d++) {
        let galerieItem = ImageGalleriesNode[d];
       
          ImageGalleries.push(await readFileAsDataURL(galerieItem));
        
      }

    } catch (error) {
      console.log('error2', error);
    }

      let prixRegulier = document.getElementById(
        `Tarif-regulier-${group}`
      ).value;
      let prixPromo = document.getElementById(`Tarif-promo-${group}`).value;
      let poids = document.getElementById(`Poids-${group}`).value;
      let Longueur = document.getElementById(`Longueur-${group}`).value;
      let Largeur = document.getElementById(`Largeur-${group}`).value;
      let Hauteur = document.getElementById(`Hauteur-${group}`).value;
      let PrixLivraison = document.getElementById(
        `PrixLivraison-${group}`
      ).value;
      let InformationLivraisonAsavoir = document.getElementById(
        `InformationLivraisonAsavoir-${group}`
      ).value;

      variations.push({   
        codeVariation: group,     
        imageCouverture: imageCouverture,
        ImageGalleries: ImageGalleries,
        prixRegulier: prixRegulier,
        prixPromo: prixPromo,
        poids: poids,
        Longueur: Longueur,
        Largeur: Largeur,
        Hauteur: Hauteur,
        PrixLivraison: PrixLivraison,
        InformationLivraisonAsavoir: InformationLivraisonAsavoir,
        combinaison: typeVariation
      });

  }

  // get Photo couverture

  let _imageCouverture = ''

  try {
    _imageCouverture = document.getElementById(`finalPhotoCover`).files[0];
    _imageCouverture = await readFileAsDataURL(_imageCouverture) 
  } catch (error) {
    console.log('error 3' , error);
  }

  // get galleries

  let _ImageGalleriesNode = ''
  let _ImageGalleries = [];
  try {
    _ImageGalleriesNode = document.getElementById(`finalPhotoFull`).files;
  for (let d = 0; d < _ImageGalleriesNode.length; d++) {
    let galerieItem = _ImageGalleriesNode[d];

    
      _ImageGalleries.push(await readFileAsDataURL(galerieItem));
   
  }

} catch (error) {
  console.log('error 4' , error);
}

  // categories
  let categoriesLen = document
    .getElementById("categorie-length")
    .getAttribute("length");
  categoriesLen = Number(categoriesLen);

  let categories = [];



  for (let e = 1; e <= categoriesLen; e++) {

    categories.push({
      categorie_id: e,
      checked: document.querySelector(`#categorie-${e} > input`) ? document.querySelector(`#categorie-${e} > input`).checked : false,
    });
  }

  let selectMarque = document.querySelector(`#selectMarque`).value;
  let vendeur_id = document.getElementById('produit-type').getAttribute('vendeur')

  let payload = JSON.stringify({
    produitTitre: produitTitre,
    produitQuantite: produitQuantite,
    produitDescription: produitDescription,
    produitCaracteristique: produitCaracteristique,
    produitType: produitType,
    variations: variations,
    imageCouverture: _imageCouverture,
    ImageGalleries: _ImageGalleries,
    categories: categories,
    marque: selectMarque,
    attributsProduit: finalAttributs,
    vendeur_id : vendeur_id
  });

    fetch(
      `${$host.protocol}//${$host.host}/api/v1/produit/variable/create`,
      {
        method: "POST",
        body: payload,
        headers: {
          "Content-Type": "application/json",
        },
      }
    )
    .then((res) => res.json())
    .then((res) => {
      console.log(res);
      $("#submit-add-produit").waitMe("hide");
      if( res.code == "success") {
        spop(res.message)
        window.location.href = `${window.location.protocol}//${window.location.host}/vendeur/dashboard/produits`
      } else {
        spop(res.message)
      }
    })
    .catch(error => {
      console.log(error);
    })
}

// Image to base64
async function readFileAsDataURL(file) {
  console.log('file : ', file);
  return file ? new Promise((resolve, reject) => {
    const reader = new FileReader();

    reader.onload = (event) => {
      resolve(event.target.result);
    };

    reader.onerror = (event) => {
      reject(event.target.error);
    };

    reader.readAsDataURL(file);
  }) : {};
}
