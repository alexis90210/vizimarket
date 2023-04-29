const recupererCategorieEnAvantUrl =
  window.location.protocol +
  "//" +
  window.location.host +
  "/api/v1/get/categories/enAvant";

const allProduitUrl =
  window.location.protocol +
  "//" +
  window.location.host +
  "/api/v1/get/produits";

// const produitParCategorieUrl =
//   window.location.protocol +
//   "//" +
//   window.location.host +
//   "/api/v1/affiche/produit/categorie/1";

fetch(recupererCategorieEnAvantUrl)
  .then((response) => response.json())
  .then((data) => {
    let categoriesPrincipales = data.message;

    if (categoriesPrincipales[0]) {
      const produitEtSousCategorieParCategoriePrincipalUrl =
        window.location.protocol +
        "//" +
        window.location.host +
        "/api/v1/affiche/souscategorie/produit/" +
        categoriesPrincipales[0].id;

      fetch(produitEtSousCategorieParCategoriePrincipalUrl)
        .then((response) => response.json())
        .then((data) => {
          let nomsSousCategorie = data.nom_sous_categorie;

          let produits = data.produit;

          let nomCategoriePrincipal = data.nom_categorie_principal;

          document.getElementById("categoriePrincipal").innerHTML =
            nomCategoriePrincipal;

          nomsSousCategorie.forEach((element) => {
            document.getElementById(
              "souscategorieDeCategoriePrincipal"
            ).innerHTML += `<li>
      <a href="{{path('app_boutique')}}">${element.nom}</a>
    </li>`;
          });

          produits.forEach((element) => {
            document.getElementById(
              "produitDeCategoriePrincipal"
            ).innerHTML += `
        <div class="product-wrap h-100 mb-0">
				<div class="product product-border text-center h-100">
					<figure class="product-media">
						<a href="href="{{path('app_product',{'id':${element.id}})}}">
							<img src="${element.image}" alt="product" width="260" height="293">
						</a>
						<div class="product-action-vertical">
							<a href="#" class="btn-product-icon btn-cart" data-toggle="modal" data-target="#addCartModal" title="Add to cart">
								<i class="d-icon-bag"></i>
							</a>
							<a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
								<i class="d-icon-heart"></i>
							</a>
							<a href="#" class="btn-product-icon btn-quickview" title="Aperçu rapide">
								<i class="d-icon-search"></i>
							</a>
							<a href="#" class="btn-product-icon btn-compare" title="Comparer">
								<i class="d-icon-compare"></i>
							</a>
						</div>
					</figure>
					<div class="product-details">
						<h3 class="product-name">
							<a href="href="{{path('app_product',{'id':${element.id}})}}">${element.nom}</a>
						</h3>
						<div class="product-price">
							<ins class="new-price">€198.00</ins>
							<del class="old-price">${element.devise + element.prix}</del>
						</div>
						<div class="ratings-container">
							<div class="ratings-full">
								<span class="ratings" style="width:100%"></span>
								<span class="tooltiptext tooltip-top"></span>
							</div>
							<a href="href="{{path('app_product',{'id':${element.id}})}}" class="rating-reviews">( 6 avis
																																							                                                        )</a>
						</div>
					</div>
				</div>
			</div>
        `;
          });
        });
    }

    if (categoriesPrincipales[1]) {
      const produitEtSousCategorieParCategoriePrincipalSectionDeuxUrl =
        window.location.protocol +
        "//" +
        window.location.host +
        "/api/v1/affiche/souscategorie/produit/" +
        categoriesPrincipales[1].id;

      fetch(produitEtSousCategorieParCategoriePrincipalSectionDeuxUrl)
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          let nomsSousCategorie = data.nom_sous_categorie;

          let produits = data.produit;

          let nomCategoriePrincipal = data.nom_categorie_principal;

          document.getElementById(
            "titreSectiondeuxCategoriePrincipal"
          ).innerHTML = nomCategoriePrincipal;

          nomsSousCategorie.forEach((element) => {
            document.getElementById(
              "sousCategorieSectionDeux"
            ).innerHTML += `<li>
        <a href="{{path('app_boutique')}}">${element.nom}</a>
      </li>`;
          });

          produits.forEach((element) => {
            document.getElementById("produitsSectionDeux").innerHTML += `
        <div class="product-wrap h-100">
          <div class="product product-border text-center h-100">
            <figure class="product-media">
            <a href="href="{{path('app_product',{'id':${element.id}})}}">
              <img src="${
                element.image
              }" alt="product" width="260" height="293">
            </a>
            <div class="product-action-vertical">
              <a href="#" class="btn-product-icon btn-cart" data-toggle="modal" data-target="#addCartModal" title="Add to cart">
                <i class="d-icon-bag"></i>
              </a>
              <a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
                <i class="d-icon-heart"></i>
              </a>
              <a href="#" class="btn-product-icon btn-quickview" title="Aperçu rapide">
                <i class="d-icon-search"></i>
              </a>
              <a href="#" class="btn-product-icon btn-compare" title="Comparer">
                <i class="d-icon-compare"></i>
              </a>
            </div>
          </figure>
          <div class="product-details">
            <h3 class="product-name">
              <a href="href="{{path('app_product',{'id':${element.id}})}}">${element.nom}</a>
            </h3>
            <div class="product-price">
              <ins class="new-price">€200.00</ins>
              <del class="old-price">${element.devise + element.prix}</del>
            </div>
            <div class="ratings-container">
              <div class="ratings-full">
                <span class="ratings" style="width:100%"></span>
                <span class="tooltiptext tooltip-top"></span>
              </div>
              <a href="href="{{path('app_product',{'id':${element.id}})}}" class="rating-reviews">( 6 avis
                                                                                                                                                      )</a>
            </div>
          </div>
        </div>
      </div>
          `;
          });
        });
    }

    if (categoriesPrincipales[2]) {
      const produitEtSousCategorieParCategoriePrincipalSectionTroisUrl =
        window.location.protocol +
        "//" +
        window.location.host +
        "/api/v1/affiche/souscategorie/produit/" +
        categoriesPrincipales[2].id;
      fetch(produitEtSousCategorieParCategoriePrincipalSectionTroisUrl)
        .then((response) => response.json())
        .then((data) => {
          //console.log(data);
          let nomsSousCategorie = data.nom_sous_categorie;

          let produits = data.produit;

          let nomCategoriePrincipal = data.nom_categorie_principal;

          document.getElementById(
            "titreCategoriePrincipalSectionTrois"
          ).innerHTML = nomCategoriePrincipal;

          nomsSousCategorie.forEach((element) => {
            document.getElementById(
              "SousCategorieSectionTrois"
            ).innerHTML += `<li>
            <a href="{{path('app_boutique')}}">${element.nom}</a>
          </li>`;
          });

          produits.forEach((element) => {
            document.getElementById("ProduitsSectionTrois").innerHTML += `
            <div class="product-wrap h-100">
              <div class="product product-border text-center h-100">
                <figure class="product-media">
                  <a href="href="{{path('app_product',{'id':${element.id}})}}">
                    <img src="${element.image}" alt="product" width="260" height="293">
                  </a>
                  <div class="product-action-vertical">
                    <a href="#" class="btn-product-icon btn-cart" data-toggle="modal" data-target="#addCartModal" title="Add to cart">
                      <i class="d-icon-bag"></i>
                    </a>
                    <a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
                      <i class="d-icon-heart"></i>
                    </a>
                    <a href="#" class="btn-product-icon btn-quickview" title="Quick View">
                      <i class="d-icon-search"></i>
                    </a>
                    <a href="#" class="btn-product-icon btn-compare" title="Comparer">
                      <i class="d-icon-compare"></i>
                    </a>
                  </div>
                </figure>
                <div class="product-details">
                  <h3 class="product-name">
                    <a href="href="{{path('app_product',{'id':${element.id}})}}">${element.nom}</a>
                  </h3>
                  <div class="product-price">
                    <ins class="new-price">€23.12</ins>
                    <del class="old-price">${element.prix}</del>
                  </div>
                  <div class="ratings-container">
                    <div class="ratings-full">
                      <span class="ratings" style="width:80%"></span>
                      <span class="tooltiptext tooltip-top"></span>
                    </div>
                    <a href="href="{{path('app_product',{'id':${element.id}})}}" class="rating-reviews">( 4 avis
                                                                                                                                            )</a>
                  </div>
                </div>
              </div>
            </div>
              `;
          });
        });
    }
  });

fetch(allProduitUrl)
  .then((response) => response.json())
  .then((data) => {
    const produits = data.message;

    const premierCarousel = document.getElementById("premierCarousel");

    produits.forEach((element, index) => {
      premierCarousel.innerHTML += `<div class="product text-center cart-full">
                    <figure class="product-media">
                        <a href="{{path('app_product', {'id': ${element.id}})}}>
                            <img src="${
                              element.image
                            }" alt="product" width="240" height="270">
                        </a>
                    <div class="product-action-vertical">
                    <a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
                        <i class="d-icon-heart"></i>
                    </a>
                    <a href="#" class="btn-product-icon btn-quickview" title="Aperçu rapide">
                        <i class="d-icon-search"></i>
                    </a>
                    <a href="#" class="btn-product-icon btn-compare" title="Compare">
                        <i class="d-icon-compare"></i>
                    </a>
                </div>
            </figure>
            <div class="product-details">
            <h3 class="product-name">
            <a href="href="{{path('app_product',{'id':${element.id}})}}">${element.nom}</a>
            </h3>
            <div class="product-price">
            <ins class="new-price">€20.45</ins>
            <del class="old-price">${element.devise + element.prix}</del>
            </div>
            <div class="ratings-container">
           <div class="ratings-full">
               <span class="ratings" style="width:100%"></span>
               <span class="tooltiptext tooltip-top"></span>
           </div>
           <a href="href="{{path('app_product',{'id':${element.id}})}}" class="rating-reviews">( 6 Commentaires )</a>
            </div>
            <a href="#" class="btn-product btn-cart" data-toggle="modal" data-target="#addCartModal" title="Ajouter au panier">
            <i class="d-icon-bag"></i>Ajouter au panier
            </a>
            </div>
        </div>`;
    });
  });

//   <div class="product text-center cart-full">
// 			<figure class="product-media">
// 				<a href="href="{{path('app_product',{'id':${element.id}})}}">
// 					<img src="{{ asset('theme/images/demos/demo-market2/product/1.jpg') }}" alt="product" width="240" height="270">
// 				</a>
// 				<div class="product-action-vertical">
// 					<a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
// 						<i class="d-icon-heart"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-quickview" title="Aperçu rapide">
// 						<i class="d-icon-search"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-compare" title="Compare">
// 						<i class="d-icon-compare"></i>
// 					</a>
// 				</div>
// 			</figure>
// 			<div class="product-details">
// 				<h3 class="product-name">
// 					<a href="href="{{path('app_product',{'id':${element.id}})}}">Vêtements d'été 3x</a>
// 				</h3>
// 				<div class="product-price">
// 					<ins class="new-price">€20.45</ins>
// 					<del class="old-price">€25.68</del>
// 				</div>
// 				<div class="ratings-container">
// 					<div class="ratings-full">
// 						<span class="ratings" style="width:100%"></span>
// 						<span class="tooltiptext tooltip-top"></span>
// 					</div>
// 					<a href="href="{{path('app_product',{'id':${element.id}})}}" class="rating-reviews">( 6 Commentaires )</a>
// 				</div>
// 				<a href="#" class="btn-product btn-cart" data-toggle="modal" data-target="#addCartModal" title="Ajouter au panier">
// 					<i class="d-icon-bag"></i>Ajouter au panier
// 				</a>
// 			</div>
// 		</div>
// 		<div class="product text-center cart-full">
// 			<figure class="product-media">
// 				<a href="href="{{path('app_product',{'id':${element.id}})}}">
// 					<img src="{{ asset('theme/images/demos/demo-market2/product/2.jpg') }}" alt="product" width="240" height="270">
// 				</a>
// 				<div class="product-action-vertical">
// 					<a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
// 						<i class="d-icon-heart"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-quickview" title="Aperçu rapide">
// 						<i class="d-icon-search"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-compare" title="Comparer">
// 						<i class="d-icon-compare"></i>
// 					</a>
// 				</div>
// 			</figure>
// 			<div class="product-details">
// 				<h3 class="product-name">
// 					<a href="href="{{path('app_product',{'id':${element.id}})}}">Drone de contrôle Wi-Fi</a>
// 				</h3>
// 				<div class="product-price">
// 					<span class="price">€235.68</span>
// 				</div>
// 				<div class="ratings-container">
// 					<div class="ratings-full">
// 						<span class="ratings" style="width:80%"></span>
// 						<span class="tooltiptext tooltip-top"></span>
// 					</div>
// 					<a href="href="{{path('app_product',{'id':${element.id}})}}" class="rating-reviews">( 2 avis )</a>
// 				</div>
// 				<a href="#" class="btn-product btn-cart" data-toggle="modal" data-target="#addCartModal" title="Ajouter au panier">
// 					<i class="d-icon-bag"></i>Ajouter au panier</a>
// 			</div>
// 		</div>
// 		<div class="product product-varialble text-center cart-full">
// 			<figure class="product-media">
// 				<a href="href="{{path('app_product',{'id':${element.id}})}}">
// 					<img src="{{ asset('theme/images/demos/demo-market2/product/3.jpg') }}" alt="product" width="240" height="270">
// 				</a>
// 				<div class="product-action-vertical">
// 					<a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
// 						<i class="d-icon-heart"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-quickview" title="Aperçu rapide">
// 						<i class="d-icon-search"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-compare" title="Comparer">
// 						<i class="d-icon-compare"></i>
// 					</a>
// 				</div>
// 			</figure>
// 			<div class="product-details">
// 				<h3 class="product-name">
// 					<a href="href="{{path('app_product',{'id':${element.id}})}}">Lampe de table lumineuse bleue</a>
// 				</h3>
// 				<div class="product-price">
// 					<span class="price">€60.23</span>
// 				</div>
// 				<div class="ratings-container">
// 					<div class="ratings-full">
// 						<span class="ratings" style="width:100%"></span>
// 						<span class="tooltiptext tooltip-top"></span>
// 					</div>
// 					<a href="href="{{path('app_product',{'id':${element.id}})}}" class="rating-reviews">( 8 Commentaires )</a>
// 				</div>
// 				<a href="#" class="btn-product btn-cart" data-toggle="modal" data-target="#addCartModal" title="Select Options">
// 					<span>Select Options</span>
// 				</a>
// 			</div>
// 		</div>
// 		<div class="product text-center cart-full">
// 			<figure class="product-media">
// 				<a href="{{path('app_product',{'id':'1'})}}">
// 					<img src="{{ asset('theme/images/demos/demo-market2/product/4.jpg') }}" alt="product" width="240" height="270">
// 				</a>
// 				<div class="product-action-vertical">
// 					<a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
// 						<i class="d-icon-heart"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-quickview" title="Aperçu rapide">
// 						<i class="d-icon-search"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-compare" title="Compare">
// 						<i class="d-icon-compare"></i>
// 					</a>
// 				</div>
// 			</figure>
// 			<div class="product-details">
// 				<h3 class="product-name">
// 					<a href="{{path('app_product',{'id':'1'})}}">Sac à main marron tendance</a>
// 				</h3>
// 				<div class="product-price">
// 					<ins class="new-price">€150.00</ins>
// 					<del class="old-price">€178.24</del>
// 				</div>
// 				<div class="ratings-container">
// 					<div class="ratings-full">
// 						<span class="ratings" style="width:60%"></span>
// 						<span class="tooltiptext tooltip-top"></span>
// 					</div>
// 					<a href="{{path('app_product',{'id':'1'})}}" class="rating-reviews">( 1 avis )</a>
// 				</div>
// 				<a href="#" class="btn-product btn-cart" data-toggle="modal" data-target="#addCartModal" title="Ajouter au panier">
// 					<i class="d-icon-bag"></i>Ajouter au panier</a>
// 			</div>
// 		</div>
// 		<div class="product text-center cart-full">
// 			<figure class="product-media">
// 				<a href="{{path('app_product',{'id':'1'})}}">
// 					<img src="{{ asset('theme/images/demos/demo-market2/product/5.jpg') }}" alt="product" width="240" height="270">
// 				</a>
// 				<div class="product-action-vertical">
// 					<a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
// 						<i class="d-icon-heart"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-quickview" title="Aperçu rapide">
// 						<i class="d-icon-search"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-compare" title="Compare">
// 						<i class="d-icon-compare"></i>
// 					</a>
// 				</div>
// 			</figure>
// 			<div class="product-details">
// 				<h3 class="product-name">
// 					<a href="{{path('app_product',{'id':'1'})}}">Chapeau d'alpinisme pour femme</a>
// 				</h3>
// 				<div class="product-price">
// 					<span class="price">€50.12</span>
// 				</div>
// 				<div class="ratings-container">
// 					<div class="ratings-full">
// 						<span class="ratings" style="width:100%"></span>
// 						<span class="tooltiptext tooltip-top"></span>
// 					</div>
// 					<a href="{{path('app_product',{'id':'1'})}}" class="rating-reviews">( 6 reviews )</a>
// 				</div>
// 				<a href="#" class="btn-product btn-cart" data-toggle="modal" data-target="#addCartModal" title="Ajouter au panier">
// 					<i class="d-icon-bag"></i>Ajouter au panier</a>
// 			</div>
// 		</div>
// 		<div class="product product-varialble text-center cart-full">
// 			<figure class="product-media">
// 				<a href="{{path('app_product',{'id':'1'})}}">
// 					<img src="{{ asset('theme/images/demos/demo-market2/product/3.jpg') }}" alt="product" width="240" height="270">
// 				</a>
// 				<div class="product-action-vertical">
// 					<a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
// 						<i class="d-icon-heart"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-quickview" title="Aperçu rapide">
// 						<i class="d-icon-search"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-compare" title="Compare">
// 						<i class="d-icon-compare"></i>
// 					</a>
// 				</div>
// 			</figure>
// 			<div class="product-details">
// 				<h3 class="product-name">
// 					<a href="{{path('app_product',{'id':'1'})}}">Lampe de table lumineuse bleue</a>
// 				</h3>
// 				<div class="product-price">
// 					<span class="price">€60.23</span>
// 				</div>
// 				<div class="ratings-container">
// 					<div class="ratings-full">
// 						<span class="ratings" style="width:100%"></span>
// 						<span class="tooltiptext tooltip-top"></span>
// 					</div>
// 					<a href="{{path('app_product',{'id':'1'})}}" class="rating-reviews">( 8 reviews )</a>
// 				</div>
// 				<a href="#" class="btn-product btn-cart" data-toggle="modal" data-target="#addCartModal" title="Select Options">
// 					<span>Select Options</span>
// 				</a>
// 			</div>
// 		</div>
// 		<div class="product text-center cart-full">
// 			<figure class="product-media">
// 				<a href="{{path('app_product',{'id':'1'})}}">
// 					<img src="{{ asset('theme/images/demos/demo-market2/product/4.jpg') }}" alt="product" width="240" height="270">
// 				</a>
// 				<div class="product-action-vertical">
// 					<a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
// 						<i class="d-icon-heart"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-quickview" title="Aperçu rapide">
// 						<i class="d-icon-search"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-compare" title="Compare">
// 						<i class="d-icon-compare"></i>
// 					</a>
// 				</div>
// 			</figure>
// 			<div class="product-details">
// 				<h3 class="product-name">
// 					<a href="{{path('app_product',{'id':'1'})}}">Sac à main marron tendance</a>
// 				</h3>
// 				<div class="product-price">
// 					<ins class="new-price">€150.00</ins>
// 					<del class="old-price">€178.24</del>
// 				</div>
// 				<div class="ratings-container">
// 					<div class="ratings-full">
// 						<span class="ratings" style="width:60%"></span>
// 						<span class="tooltiptext tooltip-top"></span>
// 					</div>
// 					<a href="{{path('app_product',{'id':'1'})}}" class="rating-reviews">( 1 avis )</a>
// 				</div>
// 				<a href="#" class="btn-product btn-cart" data-toggle="modal" data-target="#addCartModal" title="Ajouter au panier">
// 					<i class="d-icon-bag"></i>Ajouter au panier</a>
// 			</div>
// 		</div>
// 		<div class="product text-center cart-full">
// 			<figure class="product-media">
// 				<a href="{{path('app_product',{'id':'1'})}}">
// 					<img src="{{ asset('theme/images/demos/demo-market2/product/5.jpg') }}" alt="product" width="240" height="270">
// 				</a>
// 				<div class="product-action-vertical">
// 					<a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
// 						<i class="d-icon-heart"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-quickview" title="Aperçu rapide">
// 						<i class="d-icon-search"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-compare" title="Compare">
// 						<i class="d-icon-compare"></i>
// 					</a>
// 				</div>
// 			</figure>
// 			<div class="product-details">
// 				<h3 class="product-name">
// 					<a href="{{path('app_product',{'id':'1'})}}">Chapeau d'alpinisme pour femme</a>
// 				</h3>
// 				<div class="product-price">
// 					<span class="price">€50.12</span>
// 				</div>
// 				<div class="ratings-container">
// 					<div class="ratings-full">
// 						<span class="ratings" style="width:100%"></span>
// 						<span class="tooltiptext tooltip-top"></span>
// 					</div>
// 					<a href="{{path('app_product',{'id':'1'})}}" class="rating-reviews">( 6 reviews )</a>
// 				</div>
// 				<a href="#" class="btn-product btn-cart" data-toggle="modal" data-target="#addCartModal" title="Ajouter au panier">
// 					<i class="d-icon-bag"></i>Ajouter au panier</a>
// 			</div>
// 		</div>
// 		<div class="product product-varialble text-center cart-full">
// 			<figure class="product-media">
// 				<a href="{{path('app_product',{'id':'1'})}}">
// 					<img src="{{ asset('theme/images/demos/demo-market2/product/3.jpg') }}" alt="product" width="240" height="270">
// 				</a>
// 				<div class="product-action-vertical">
// 					<a href="#" class="btn-product-icon btn-wishlist" title="Ajouter à la liste de souhaits">
// 						<i class="d-icon-heart"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-quickview" title="Aperçu rapide">
// 						<i class="d-icon-search"></i>
// 					</a>
// 					<a href="#" class="btn-product-icon btn-compare" title="Compare">
// 						<i class="d-icon-compare"></i>
// 					</a>
// 				</div>
// 			</figure>
// 			<div class="product-details">
// 				<h3 class="product-name">
// 					<a href="{{path('app_product',{'id':'1'})}}">Lampe de table lumineuse bleue</a>
// 				</h3>
// 				<div class="product-price">
// 					<span class="price">€60.23</span>
// 				</div>
// 				<div class="ratings-container">
// 					<div class="ratings-full">
// 						<span class="ratings" style="width:100%"></span>
// 						<span class="tooltiptext tooltip-top"></span>
// 					</div>
// 					<a href="{{path('app_product',{'id':'1'})}}" class="rating-reviews">( 8 reviews )</a>
// 				</div>
// 				<a href="#" class="btn-product btn-cart" data-toggle="modal" data-target="#addCartModal" title="Select Options">
// 					<span>Select Options</span>
// 				</a>
// 			</div>
// 		</div>
