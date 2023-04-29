console.log("paiement js charge");

var redirectUrl =
  window.location.protocol + "//" + window.location.host + "/order";

var panier;

var prixTotal;
if (
  localStorage.getItem("panier") != undefined &&
  localStorage.getItem("panier") != null
) {
  panier = JSON.parse(localStorage.getItem("panier"));

  somme = 0;
  panier.forEach((element) => {
    if (element.prix_promo != null) {
      somme += element.quantite * element.prix_promo;
    } else {
      somme += element.quantite * element.prix_regulier;
    }
  });

  prixTotal = somme;

  console.log(panier);
  document.getElementById("produit-body").innerHTML = "";
  panier.forEach((element) => {
    document.getElementById("produit-body").innerHTML += `<tr>
    <td class="product-name">${element.nom}
        <span class="product-quantity">×&nbsp;${element.quantite}</span>
    </td>
    <td class="product-total text-body">${
      element.prix_promo ?? $element.prix_regulier
    }</td>
</tr>`;
  });
}

function passerLaCommande() {
  const url =
    window.location.protocol +
    "//" +
    window.location.host +
    "/api/v1/commande/add";

  const object = {
    panier: panier,
    client: document.getElementById("id-client").value,
    adresseLivraison: document.getElementById("adresse-livraison").value,
    prixTotal: prixTotal,
  };

  console.log(object);

  const options = {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(object),
  };

  fetch(url, options)
    .then((response) => response.json())
    .then((data) => {
      // Traitement de la réponse JSON
      console.log(data);
      window.location.href = redirectUrl + "/cmu-" + data.idCommande;
    })
    .catch((error) => console.error(error));
}
