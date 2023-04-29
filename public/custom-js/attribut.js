function addCouleur() {
  const addCouleurUrl =
    window.location.protocol +
    "//" +
    window.location.host +
    "/api/attribut/add/sous/attribut";

  const obj = {
    titre: document.getElementById("titre").value,
    valeur: document.getElementById("valeur").value,
    idAttribut: document.getElementById("c-idAttribut").value,
  };

  console.log(obj);

  document.getElementById("titre").value = "";
  document.getElementById("valeur").value = "";

  fetch(addCouleurUrl, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(obj),
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      let sousAttributs = data.message;

      console.log(sousAttributs);
      displayDataForColor(sousAttributs);
    })
    .catch((error) => {
      console.log(error);
    });
}

function addSousVariation() {
  const addSousVariationUrl =
    window.location.protocol +
    "//" +
    window.location.host +
    "/api/attribut/add/sous/attribut";

  const obj = {
    titre: document.getElementById("s-titre").value,
    valeur: document.getElementById("s-valeur").value,
    idAttribut: document.getElementById("s-idAttribut").value,
  };

  (document.getElementById("s-titre").value = ""),
    (document.getElementById("s-valeur").value = "");

  console.log(obj);

  fetch(addSousVariationUrl, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(obj),
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      console.log(data);

      let sousAttributs = data.message;

      displayTaillOrLabel(sousAttributs);
    })
    .catch((error) => {
      console.log(error);
    });
}

function changeColor() {
  let hexa = document.getElementById("hexa").value;

  if (hexa.length < 6) {
    var color = hexa.replace("#", "");
    color = color.replace(/./g, "$&$&");
    document.getElementById("valeur").value = "#" + color;
  } else {
    document.getElementById("valeur").value = hexa;
  }
}

function changeColorEdit(id) {
  console.log(id);
  let hexa = document.getElementById("m-hexa-" + id).value;

  if (hexa.length < 6) {
    var color = hexa.replace("#", "");
    color = color.replace(/./g, "$&$&");
    document.getElementById("m-valeur-" + id).value = "#" + color;
  } else {
    document.getElementById("m-valeur-" + id).value = hexa;
  }
}

function deleteTerme(id) {
  const addDeleteUrl =
    window.location.protocol +
    "//" +
    window.location.host +
    "/api/attribut/delete";

  const obj = {
    idTerme: id,
    idAttribut: document.getElementById("s-idAttribut").value,
  };

  fetch(addDeleteUrl, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(obj),
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      console.log(data);

      let sousAttributs = data.message;
      displayTaillOrLabel(sousAttributs);
    })
    .catch((error) => {
      console.log(error);
    });
}

function deleteTermeColor(id) {
  const addDeleteUrl =
    window.location.protocol +
    "//" +
    window.location.host +
    "/api/attribut/delete";

  const obj = {
    idTerme: id,
    idAttribut: document.getElementById("c-idAttribut").value,
  };

  console.log(obj);

  document.getElementById("titre").value = "";
  document.getElementById("valeur").value = "";

  fetch(addDeleteUrl, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(obj),
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      let sousAttributs = data.message;

      console.log(sousAttributs);
      displayDataForColor(sousAttributs);
    })
    .catch((error) => {
      console.log(error);
    });
}

function updateTerme(id) {
  const updateTermeUrl =
    window.location.protocol +
    "//" +
    window.location.host +
    "/api/attribut/update";

  const obj = {
    idTerme: id,
    idAttribut: document.getElementById("ms-idAttribut-" + id).value,
    titre: document.getElementById("ms-titre-" + id).value,
    valeur: document.getElementById("ms-valeur-" + id).value,
  };

  fetch(updateTermeUrl, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(obj),
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      console.log(data);

      let sousAttributs = data.message;
      displayTaillOrLabel(sousAttributs);
    })
    .catch((error) => {
      console.log(error);
    });
}

function displayTaillOrLabel(data) {
  document.getElementById("body-sousVariations").innerHTML = "";
  data.forEach((el) => {
    document.getElementById("body-sousVariations").innerHTML += `
                  <tr>
                              <td>
                                <h5 class="font-size-16">${el.titre}</h5>
                              </td>
    
                              <td>
                                <h5 class="font-size-16"4>${el.valeur}</h5>
                              </td>
                              <td id="tooltip-container0">
                                <btn data-bs-toggle="modal" data-bs-target="${
                                  "#attribut-" + el.id
                                }" class="me-3 text-primary" data-bs-container="#tooltip-container0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                  <i class="mdi mdi-pencil font-size-18"></i>
                                </btn>
                                <a  onclick="deleteTerme(${
                                  el.id
                                })" class="text-danger" data-bs-container="#tooltip-container0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="mdi mdi-trash-can font-size-18"></i></a>
                              </td>
                  </tr>

                  <div class="modal fade" id="${
                    "attribut-" + el.id
                  }" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier ce terme</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <div class="my-3">
                                  <label for="titre">Titre</label>
                                  <input type="text" id="${
                                    "ms-titre-" + el.id
                                  }" class="form-control" name="titre" value="${
      el.titre
    }" />
                                </div>

                                <div class="my-3">
                                  <label for="valeur">Valeur</label>
                                  <input type="text" id="${
                                    "ms-valeur-" + el.id
                                  }" class="form-control" name="valeur" value="${
      el.valeur
    }" />
                                </div>
                                <input type="hidden" value="${
                                  el.idAttribut
                                }" id="${"ms-idAttribut-" + el.id}" />
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button onclick="updateTerme(${
                                  el.id
                                })" class="btn btn-primary" data-bs-dismiss="modal">Modifier</button>
                              </div>
                            </div>
                          </div>
                        </div>


                `;
  });
}

function displayDataForColor(data) {
  document.getElementById("body-couleur").innerHTML = "";
  data.forEach((el) => {
    document.getElementById("body-couleur").innerHTML += `
                        <tr>
                            <td>
                              <h5 class="font-size-16">${el.titre}</h5>
                            </td>

                            <td>
                              <h5 class="font-size-16"><div title="${
                                el.titre
                              }" style="width:30px;height:30px; border-radius:10px; background-color:${
      el.valeur
    }"></div></h5>
                            </td>

                            <td id="tooltip-container0">
                              <btn data-bs-toggle="modal" data-bs-target="${
                                "#attribut-" + el.id
                              }" class="me-3 text-primary" data-bs-container="#tooltip-container0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                <i class="mdi mdi-pencil font-size-18"></i>
                              </btn>
                              <a onclick="deleteTermeColor(${
                                el.id
                              })" class="text-danger" data-bs-container="#tooltip-container0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="mdi mdi-trash-can font-size-18"></i></a>
                            </td>
                          </tr>

                          <div class="modal fade" id="${
                            "attribut-" + el.id
                          }" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier ce terme</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <div class="my-3">
                                  <label for="">Titre</label>
                                  <input type="text" id="${
                                    "m-titre-" + el.id
                                  }" class="form-control" name="titre" value="${
      el.titre
    }" />
                                </div>

                                <div class="my-3">
                                  <label for="">Valeur</label>
                                  <input type="color" id="${
                                    "m-valeur-" + el.id
                                  }" class="form-control" name="valeur" value="${
      el.valeur
    }" />
                                </div>
                                <input type="hidden" value="${
                                  el.idAttribut
                                }" id="${"mc-idAttribut-" + el.id}" />
                                <hr />
                                <h4 class="text-center">Ou bien</h4>
                                <div>
                                  <p style="margin-bottom: 0px;">Entrer votre couleur en hexa ici</p>
                                  <div class="mt-1 col-5">
                                    <input type="text" oninput="changeColorEdit(${
                                      el.id
                                    })" class="form-control" id="${
      "m-hexa-" + el.id
    }" name="hexa" placeholder="Votre couleur en hexa" required />
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button onclick="updateTermeColor(${
                                  el.id
                                })" class="btn btn-primary" data-bs-dismiss="modal">Modifier</button>
                              </div>
                            </div>
                          </div>
                        </div>
                  `;
  });
}

function updateTermeColor(id) {
  console.log(id);
  const updateTermeUrl =
    window.location.protocol +
    "//" +
    window.location.host +
    "/api/attribut/update";

  const obj = {
    idTerme: id,
    idAttribut: document.getElementById("mc-idAttribut-" + id).value,
    titre: document.getElementById("m-titre-" + id).value,
    valeur: document.getElementById("m-valeur-" + id).value,
  };

  console.log(obj);

  fetch(updateTermeUrl, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(obj),
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      console.log(data);

      let sousAttributs = data.message;
      displayDataForColor(sousAttributs);
    })
    .catch((error) => {
      console.log(error);
    });
}
