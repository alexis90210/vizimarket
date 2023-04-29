loadPanier();


// delete item

function deletePanier( id ) {
    var panier = JSON.parse(localStorage.getItem("panier"));
    let final = []
    panier.map( pro => {
        if (pro.produit_id != id) {
            final.push(pro)
        }
    })

    localStorage.setItem("panier", JSON.stringify(final))

    loadPanier()

}

// Load panier
function loadPanier() {
  document.querySelector(".summary-subtotal-price").innerText = 0;
  document.querySelector(".summary-total-price").innerText = 0;

  var panier = JSON.parse(localStorage.getItem("panier"));
  console.log(panier);
  var panier_container = document.querySelector("#panier-container");
  panier_container.innerHTML = null;

  panier.map((produit, i) => {
    let total = document.querySelector(".summary-subtotal-price").innerText;
    total =
      Number(total) +
      Number(produit.prix_promo ? produit.prix_promo : produit.prix_regulier)* Number( produit.quantite);

    document.querySelector(".summary-subtotal-price").innerText = total;
    document.querySelector(".summary-total-price").innerText = total;

    let sous_total = Number( produit.prix_promo ? produit.prix_promo : produit.prix_regulier ) * Number( produit.quantite)

    panier_container.innerHTML += `<tr>
    <td class="product-thumbnail">
        <figure>
            <a href="{{path('app_product',{'id':${Number(
              produit.produit_id
            )}})}}">
                <img src="${
                  produit.image
                }" width="100" height="100" alt="product">
            </a>
        </figure>
    </td>
    <td class="product-name">
        <div class="product-name-section">
            <a href="{{path('app_product',{'id':${Number(
              produit.produit_id
            )}})}}">${produit.nom}</a>
        </div>
    </td>
    <td class="product-subtotal">
        <span class="amount">€${
          produit.prix_promo ? produit.prix_promo : produit.prix_regulier
        }</span>
    </td>
    <td class="product-quantity">
        actuelle : ${produit.quantite}
        <div class="input-group">
            <button class="quantity-minus d-icon-minus"></button>
            <input class="quantity form-control" type="number" min="1" value="${
              produit.quantite
            }" max="1000000">
            <button class="quantity-plus d-icon-plus"></button>
        </div>
    </td>
    <td class="product-price">
        <span class="amount">€${sous_total}</span>
    </td>
    <td class="product-close"  onclick="deletePanier(${produit.produit_id})">
        <button class="product-remove" title="Enlever ce produit">
            <i class="fas fa-times"></i>
        </button>
    </td>
</tr>`;
  });
}
