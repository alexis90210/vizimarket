console.log("ficher load createur configuration");

const url =
  window.location.protocol +
  "//" +
  window.location.host +
  "/api/v1/load/createur/configuration/1";

fetch(url)
  .then((response) => response.json())
  .then((data) => {
    //console.log(data.message[0]);
    let configuration = data.message[0];

    console.log(configuration.couleur_fond);

    if (configuration.couleur_fond != null) {
      document.body.style.backgroundColor =
        configuration.couleur_fond + "!important";

      if (document.getElementById("main-home") != undefined) {
        document.getElementById("main-home").style.backgroundColor =
          configuration.couleur_fond + "!important";
      }
    }

    //console.log(document.body.style);

    if (configuration.font_size != null) {
      if (document.getElementById("main-home") != undefined) {
        document.getElementById("main-home").style.backgroundColor = fontSize =
          configuration.font_size + "px" + " !important";
      }
    }

    if (configuration.font_family != null) {
      document.body.style.fontFamily =
        configuration.font_family + "px" + " !important";
    }

    // --------------

    if (configuration.couleur_footer != null) {
      document.footer.style.backgroundColor =
        configuration.couleur_footer + " !important";
    }

    if (configuration.couleur_header != null) {
      document.couleur_header.style.backgroundColor =
        configuration.couleur_header + "!important";
    }

    if (configuration.font_family != null) {
      document.body.style.fontFamily =
        configuration.font_family + "px" + " !important";
    }
  })
  .catch((error) => console.error(error));
