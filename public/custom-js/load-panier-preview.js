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

  var panier = JSON.parse(localStorage.getItem("panier"));
  console.log(panier);
  var panier_container = document.querySelector(".preview-panier");

  document.querySelector(".preview-panier-total").innerText = panier.length

  panier_container.innerHTML = null;
  document.querySelector("#preview-subtotal-price").innerText = 0

  panier.map((produit, i) => {
    let total = document.querySelector("#preview-subtotal-price").innerText;
    total =
      Number(total) +
      Number(produit.prix_promo ? produit.prix_promo : produit.prix_regulier) * Number( produit.quantite);

      
      document.querySelector(".preview-panier-total-prix").innerText = total

    document.querySelector("#preview-subtotal-price").innerText = total

    panier_container.innerHTML += `<div class="product product-cart">
    <figure class="product-media">
      <a href="{{path('app_product',{'id':${produit.produit_id}})}}">
        <img src="${produit.image}" alt="product" width="80" height="88"/>
      </a>
      <button class="btn btn-link btn-close" onClick="deletePanier(${produit.produit_id})">
        <i class="fas fa-times"></i>
        <span class="sr-only">Fermer</span>
      </button>
    </figure>
    <div class="product-detail">
      <a href="{{path('app_product',{'id':${produit.produit_id}})}}" class="product-name">${produit.nom}</a>
      <div class="price-box">
        <span class="product-quantity">${produit.quantite}</span>
        <span class="product-price">€${produit.prix_promo ? produit.prix_promo : produit.prix_regulier}</span>
      </div>
    </div>

  </div>`;
  });
}
