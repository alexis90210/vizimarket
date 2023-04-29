var baseurl =
  window.location.protocol + "//" + window.location.host + "/api/v1/";

// ---------------------------------------------------------------------------------------------------
// create vendeur
// ---------------------------------------------------------------------------------------------------

var form_vendeur = document.querySelector("#form-create-vendeur");

form_vendeur?.addEventListener("submit", (event) => {
  event.preventDefault();

  const formData = new FormData( form_vendeur );
  const formObject = {};

  for (let [key, value] of formData.entries()) {
    formObject[key] = value;
  }

  console.log( formObject );

  let fileReader = new FileReader();
  fileReader.onload = (base) => {
    savevendeur({
      ...formObject,
      logo: base.target.result,
    });
  };
  fileReader.readAsDataURL(formObject.logo);
});

// save vendeur
const savevendeur = async (formObject) => {
  var payload = JSON.stringify(formObject);

  await fetch(baseurl + "creation/vendeur", {
    method: "POST",
    body: payload,
  })
    .then((res) => res.json())
    .then((data) => {
      console.log(data);

      if (data.code == "error") {
        if (data.message.includes("SQLSTATE[23000]")) {
          alert("Email deja pris, veuillez changer d adresse");
        } else {
          alert(data.message);
        }
      } else {
        alert(data.message);
        setTimeout(() => {
          window.history.back();
        }, 500);
      }
    });
};

// ---------------------------------------------------------------------------------------------------
// create createur
// ---------------------------------------------------------------------------------------------------

var form_createur = document.querySelector("#form-create-createur");

form_createur?.addEventListener("submit", (event) => {
  event.preventDefault();

  const formData = new FormData(form_createur);
  const formObject = {};

  for (let [key, value] of formData.entries()) {
    formObject[key] = value;
  }

  let fileReader = new FileReader();
  fileReader.onload = (base) => {
    saveCreateur({
      ...formObject,
      logo: base.target.result,
    });
  };
  fileReader.readAsDataURL(formObject.logo);
});

// save createur
const saveCreateur = async (formObject) => {
  var payload = JSON.stringify(formObject);

  await fetch(baseurl + "creation/createur", {
    method: "POST",
    body: payload,
  })
    .then((res) => res.json())
    .then((data) => {
      console.log(data);

      if (data.code == "error") {
        if (data.message.includes("SQLSTATE[23000]")) {
          alert("Email deja pris, veuillez changer d adresse");
        } else {
          alert(data.message);
        }
      } else {
        alert(data.message);
        setTimeout(() => {
          window.history.back();
        }, 500);
      }
    });
};
// ---------------------------------------------------------------------------------------------------