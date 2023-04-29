console.log("le ficher loader est charger");

// Récupération du loader dans le HTML
// const loader = document.querySelector(".loader");
function loaderFunction() {
  loader = document.getElementById("loader");
  console.log("le loader");
}
console.log("un loader-------");

// Masquage du loader par défaut

// Affichage du loader pendant le parsing de la page
// document.addEventListener("DOMContentLoaded", () => {
//   const loader = document.querySelector(".loader");
//   loader.style.display = "block";
//   console.log("affichage du loader");
// });

// // Masquage du loader lorsque la page est entièrement chargée
// window.addEventListener("load", () => {
//   loader.style.display = "none";

//   console.log("La page est entierement chargee");
// });
