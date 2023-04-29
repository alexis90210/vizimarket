var $host = window.location;
var current_attr = [];
var selectTerme = [];

function supprimeTerme(id) {
  let box = document.querySelector(`#${id}`).remove();
}

function addAttributItem(attribut, injectedItemAreaId, selectedItem) {
  let area = document.getElementById(`${injectedItemAreaId}`);

  let terme = document.getElementById(`${selectedItem}`).value;
  let title = '';
  
  let isColor = false;

  if (terme.toString().includes("#")) {
    title = document.getElementById(`${selectedItem}`).value.toString().split(';')[1];
    terme = document.getElementById(`${selectedItem}`).value.toString().split(';')[0];
    isColor = true;
  }
  let terme_id = terme.replaceAll("#", "");

  if (!document.getElementById(`badge-item-${selectedItem}-${terme_id}`)) {
    if (isColor) {
      area.innerHTML += `<button id="badge-item-${selectedItem}-${terme_id}" val="${terme}" terme-name="${title}" type="button" class="btn p-1 m-2" style="border: 1px dashed #${terme_id}" id="produit-attribut-termes">
      <span class="px-3">${title}</span> 
      <span class="btn btn rounded p-1" style="border: 1px dashed #${terme_id}"  onclick="supprimeTerme('badge-item-${selectedItem}-${terme_id}')">
      &times;
    </span>
    </button>`;
    } else {
      area.innerHTML += `<button id="badge-item-${selectedItem}-${terme_id}" val="${terme}" terme-name="${terme}" type="button" class="btn btn-success p-1 m-2" id="produit-attribut-termes">
      <span class="px-3">${terme}</span> 
      <span class="btn btn-light rounded p-1" onclick="supprimeTerme('badge-item-${selectedItem}-${terme_id}')">
      &times;
    </span>
    </button>`;
    }

    loadFieldAttribut();
  } else {
    spop("Terme deja existant", "warning");
  }
}

function scrollToElement(id) {
  var element = document.getElementById(`${id}`);
  element.scrollIntoView({
    behavior: "smooth",
    block: "start",
    inline: "nearest",
  });
}

function deleteAttribut(id, value) {
  document.querySelector(`${id}`).remove();

  var final = [];

  current_attr.map((el) => {
    if (el != value) final.push(el);
  });

  current_attr = final;
  console.log("Loading attributs now");
  loadFieldAttribut();
}

function addAttribut() {
  let type_attr = document.querySelector("#produit-attribut-selection").value;

  if (!current_attr.includes(type_attr)) {
    //---------------------------------------------------

    fetch(
      `${$host.protocol}//${$host.host}/api/v1/get/sous/attribut/by/${type_attr}`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      }
    )
      .then((res) => res.json())
      .then((data) => {
        console.log("les termes : ", data);
        if (data.code == "success") {
          current_attr.push(type_attr);

          // ...........................................

          let OptionsList = document.querySelectorAll('#produit-attribut-selection > option');

          for (let x = 0; x < OptionsList.length; x++) {
            const option = OptionsList[x];

            if (option.getAttribute('value').toString() == type_attr.toString()) {
              option.setAttribute('disabled',true)
              option.removeAttribute('selected')
            } else {
              option.setAttribute('selected',true)
            }           
          }

          //---------------------------------------------------
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
          data.message.map((sous) => {
            if (sous.type == "Couleur") {
              attribut += `<option value="${sous.valeur};${sous.titre}" title="${sous.titre}">${sous.titre}</option>`;
            }

            if (sous.type == "Taille") {
              attribut += `<option value="${sous.valeur}" title="${sous.titre}">${sous.valeur}</option>`;
            }
          });
          attribut += `
          </select> 
          </div>
          <div class="col-lg-3">
            <p class="btn btn-success" id="btn-produit-attribut-${type_attr}" onclick="addAttributItem('${type_attr}', 'produit-attribut-termes-${type_attr}', 'produit-attribut-${type_attr}' )">Ajouter</p>
          </div>
        </div>
        
        <div id="produit-attribut-termes-${type_attr}"></div>

          <details class="mt-3">
              <summary class="text-success">
                  demande de creation d'un attribut (type ${type_attr})
              </summary>
              <div class="border border-3 p-3 mb-2 border-rounded border-success" style="border: 1px dashed #43C590 !important">
                  <div class="row">
                      <div class="mb-1 col-lg-8">
                          <input id="nom-${type_attr}" name="nom-${type_attr}" type="text" class="form-control" placeholder="Entrer le Nom ">
                      </div>      
                      <div class="col-lg-4 mb-1">
                          <button class="btn btn-success">Soumettre</button>
                      </div>
                  </div>      
              </div>
          </details>    
        </div>`;

          box_attr.innerHTML += attribut;

          console.log("Loading attributs now");

          scrollToElement(`selected-attribut-${type_attr}`);
        }
      })
      .catch((error) => {
        console.log("Loading terme", error);
      });
  } else {
    spop("Attribut deja existant", "warning");
  }
}
