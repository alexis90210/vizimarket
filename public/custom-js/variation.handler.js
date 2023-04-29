var current_variation = [];
var boxFields = document.querySelector('#generated-field-attributes');
var compositionVar = []
var box_variations = document.querySelector("#box-variations");

function deleteVariation(id) {
  document.querySelector(`#${id}`).remove();

  var final = [];

  current_variation.map((el) => {
    if (el != id) final.push(el);
  });

  current_variation = final;
  console.log('Loading attributs now');
  loadFieldAttribut()
}


function loadFieldAttribut() {
  
  boxFields = document.querySelector('#generated-field-attributes');
  
  boxFields.innerHTML = null

  current_attr.map(el => {

    let selected_termes = document.querySelectorAll(`#produit-attribut-termes-${el} > *`)

    console.log('termes:', selected_termes);

    let _boxFields = ''
    
    _boxFields += `<div class="col-lg-3 mb-3">
    <select name="selected-produit-attribut-box-${el}" id="selected-produit-attribut-box-${el}" class="form-select">
     `

     for ( let index = 0; index < selected_termes.length; index++ ) {
      const val = selected_termes[index].getAttribute('val');
      let terme_name = ''
      let isColor = false


      if ( val.toString().includes('#')) {
		    terme_name = selected_termes[index].getAttribute('terme-name');
        isColor=true
      }
      
      console.log(val);
      if ( isColor ) {
        _boxFields +=` <option value="${val}" title="${val}" selected>${terme_name}</option>`
      } else {
        _boxFields +=` <option value="${val}" title="${val}" selected>${val}</option>`
      }
      
     }

     _boxFields +=`</select>
  </div>`

  boxFields.innerHTML += _boxFields
  })

  boxFields.innerHTML += `<div class="mb-3 col-lg-3">
    <p class="btn btn-success" onclick="addVariation()">Ajouter</p>
  </div>`

}


function addVariation() {
  
  console.log('adding variation...');
  
  var group = 'variation';
  var _compositionVar = []
  current_attr.map( el => {
    let $el = document.querySelector(
      `#selected-produit-attribut-box-${el}`
    ).value.toString()

    _compositionVar.push($el)
    group = group + '-' + $el.replaceAll('#','');
  })

  // compositionVar.push(_compositionVar)

  console.log('current_variation' , current_variation);

  if (!current_variation.includes(group)) {
    compositionVar.push(_compositionVar)
    current_variation.push(group);

    let variation = `<div class="border border-3 p-3 mb-2 border-rounded border-light" 
      style="border: 1px dashed #43C590 !important" id="${group}">
    <div class="row d-flex justify-content-between align-items-center">
      <div class="row col-12" id="row-${group}">`;   

      current_attr.map(el => {
        let selected_termes = document.querySelectorAll(`#produit-attribut-termes-${el} > *`)
        variation += `<div class="col-3 mb-3 col-${group}">
        <select disabled name="produit-attribut-variation-box-${el}" id="produit-attribut-variation-box-${el}" type="${el}" class="form-select select2">`
         for ( let index = 0; index < selected_termes.length; index++ ) {
            
            const val = selected_termes[index].getAttribute('val');
            console.log(val);
            let terme_name = ''
            if ( val.toString().includes('#')) {
              terme_name = selected_termes[index].getAttribute('terme-name');
              isColor=true
              if ( val == document.getElementById(`selected-produit-attribut-box-${el}`).value) {           
                variation +=` <option value="${val}" title="${val}" selected>${terme_name}</option>`
              } else {
                variation +=` <option value="${val}" title="${val}">${terme_name}</option>`
              }  
            } else {
              if ( val == document.getElementById(`selected-produit-attribut-box-${el}`).value) {           
                variation +=` <option value="${val}" title="${val}" selected>${val}</option>`
              } else {
                variation +=` <option value="${val}" title="${val}">${val}</option>`
              }  
            }

                   
         }

         variation +=  `
        </select>
      </div>`;

      })

      variation += `<div class="col-3">
          <p title="supprimer la variation" style="cursor:pointer;" onclick="deleteVariation('${group}')" class="text-danger btn ">supprimer</p>
        </div>
      </div>
    </div>
    
    <!-- gallerie -->
    <div  id="gallerie-${group}">

      <center id="center-gallerie-${group}">
        <img id="preview-cover-produit-file-${group}" width="100" alt="">
      </center>
      <h4 class="header-title">Image de couverture du produit</h4>
      <div class="fallback" id="fallback-${group}">
        <input type="file" name="final-photo-cover-${group}" id="finalPhotoCover-${group}" required class="form-control" accept="image/*"/>
      </div>

      <hr>
      <h4 class="header-title">Gallerie du produit (max 5)</h4>

      <div class="row col-lg-12 mt-1" id="finalPhotoFullBox-${group}"></div>

      <div class="fallback"  id="fallback-2-${group}">
        <input name="final-gallerie-${group}[]" type="file" id="finalPhotoFull-${group}" class="form-control" accept="image/*" multiple/>
      </div>
    </div>

    <!-- detail -->

    <div class="row mt-3"  id="row-detail-${group}">
      <div class="mb-3 col-lg-6" id="mb-col-${group}">
        <label for="Tarif-regulier">Tarif régulier (€)</label>
        <input name="tarif-regulier-${group}" type="number" class="form-control" id="Tarif-regulier-${group}" placeholder="Enter Tarif" name="tarif-regulier">
      </div>
      <div class="mb-3 col-lg-6" id="mb-col-lg-${group}">
        <label for="Tarif-promo">Tarif promo (€)</label>
        <input name="tarif-promo-${group}" type="number" class="form-control" id="Tarif-promo-${group}" placeholder="Enter Tarif" name='tarif-promo'>
      </div>
    </div>

    <hr>

    <!-- expedition -->
    <div class="mb-3" id="mb-poids-${group}">
      <label for="Poids">Poids (kg)</label>
      <input type="Number" class="form-control" id="Poids-${group}" name='poids-${group}' placeholder="Enter Poids">
    </div>
    <div class="mb-3" id="mb-dimension-${group}">
      <label for="">Dimensions (cm)</label>
      <div class="d-flex gap-3" id="mb-expedition-${group}">
        <input type="number" class="form-control" id="Longueur-${group}" name="Longueur-${group}" placeholder="Longueur" name="longueur">
        <input type="number" class="form-control" id="Largeur-${group}" name="Largeur-${group}" placeholder="Largeur" name="largeur">
        <input type="number" class="form-control" id="Hauteur-${group}" name="Hauteur-${group}" placeholder="Hauteur" name="hauteur">
      </div>
    </div>

    <hr>

    <!-- infos. compl. -->
    <div class="row" id="row-PrixLivraison-${group}">																																																		
      <div class="mb-3">
        <label for="PrixLivraison">Prix Livraison (€)</label>
        <input type="number" class="form-control" id="PrixLivraison-${group}" name="PrixLivraison-${group}" placeholder="Enter Prix" name="prix-livraison">
      </div>		
    </div>

    <div class="row" id="row-InformationLivraisonAsavoir-${group}">
      <div class="mb-3 col-lg-12">
        <label class="form-label" for="InformationLivraisonAsavoir">Information Livraison A Savoir</label>
        <textarea class="form-control" name='informaton-a-savoir-${group}' id="InformationLivraisonAsavoir-${group}" rows="2" placeholder="Enter info."></textarea>
      </div>
    </div>

    <a href="#generated-field-attributes">Ajouter une variation</a>
  </div>`;

    // box_variations.innerHTML += variation ;

    // parse as HTML DOM
    const parser = new DOMParser();

    // Parse the string and get the document element
    const parsedHtmlVariation = parser.parseFromString(variation, 'text/html');
    const nodeVariation = parsedHtmlVariation.documentElement;


    box_variations.appendChild(nodeVariation)
    console.log('inserted');

    // handle image preview
    let finalPhotoCover = document.getElementById(`finalPhotoCover-${group}`);
    finalPhotoCover.addEventListener("change", (e) => {
      var file = e.target.files[0];
      document.getElementById(`preview-cover-produit-file-${group}`).src =
        window.URL.createObjectURL(file);
    });

    let finalPhotoFull = document.getElementById(`finalPhotoFull-${group}`);
    finalPhotoFull.addEventListener("change", (e) => {
      var files = e.target.files;
      let finalPhotoFullBox = document.getElementById(
        `finalPhotoFullBox-${group}`
      );
      finalPhotoFullBox.innerHTML = null;
      for (let i = 0; i < files.length; i++) {
        finalPhotoFullBox.innerHTML += `<div class="col-lg-3 p-2">
    <img width="100" alt="" class="rounded" src="${window.URL.createObjectURL(
      files[i]
    )}">
    </div>`;
      }
    });

    scrollToElement(`${group}`)
  } else {
    spop('Variation deja existant', 'warning')
  }
}
