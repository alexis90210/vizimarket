function loadCategories() {
  console.log("oncharge les categories");
  const allCategorieUrl =
    window.location.protocol + "//" + window.location.host + "/api/categories";

  fetch(allCategorieUrl)
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      const categories = data.message;

      //console.log(categories);

      if (document.getElementById("cat-select") != undefined) {
        document.getElementById("cat-select").innerHTML = "";

        document.getElementById("cat-select").innerHTML =
          "<option value='toute-cat' selected>toutes catégories</option>";

        categories.forEach((element) => {
          document.getElementById("cat-select").innerHTML += `

          <option><a href="#">${element.nom}</a></option>
          
          `;
        });

        if (document.getElementById("cat-vertical") != undefined) {
          document.getElementById("cat-vertical").innerHTML = "";

          categories.forEach((element) => {
            let premierePartie =
              "<li><a href='#'><i class='d-icon-home'></i>" +
              element.nom +
              "</a>";

            if (element.sousCategorie.length > 0) {
              premierePartie += "<ul class=''>";

              element.sousCategorie.forEach((el) => {
                premierePartie +=
                  "<li><a href='#'>" + el.nom + "</a><hr class='divider'>";
              });

              premierePartie += "</ul>";
            } else {
              premierePartie += "</li>";
            }

            document.getElementById(
              "cat-vertical"
            ).innerHTML += `${premierePartie}`;
          });
        }
      }
    })
    .catch((error) => console.error(error));
}

loadCategories();
