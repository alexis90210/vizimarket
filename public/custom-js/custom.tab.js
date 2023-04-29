// produit simple

const tabElt = ["tab-detail", "tab-expedition", "tab-complement"];

const openTab = (id) => {
  closeAllTab();
  let elt = document.querySelector(`#${id}`);
  elt.classList.remove("d-none");
  let title = document.querySelector(`#${id}-title`);
  if (!title.classList.contains(`active-c-tab`)) {
    title.classList.add("active-c-tab");
  }
};
const closeAllTab = () => {
  tabElt.map((item) => {
    let elt = document.querySelector(`#${item}`);
    if (!elt.classList.contains("d-none")) {
      let title = document.querySelector(`#${item}-title`);
      if (title.classList.contains(`active-c-tab`)) {
        title.classList.remove("active-c-tab");
      }

      elt.classList.add("d-none");
    }
  });
};

// produit variable
const tabEltVariable = ["tab-variable-attribut", "tab-variable-variation"];

const openTabVariable = (id) => {
  closeAllTabVariable();
  let elt = document.querySelector(`#${id}`);
  elt.classList.remove("d-none");
  let title = document.querySelector(`#${id}-title`);
  if (!title.classList.contains(`active-c-tab`)) {
    title.classList.add("active-c-tab");
  }
};
const closeAllTabVariable = () => {
  tabEltVariable.map((item) => {
    let elt = document.querySelector(`#${item}`);
    if (!elt.classList.contains("d-none")) {
      let title = document.querySelector(`#${item}-title`);
      if (title.classList.contains(`active-c-tab`)) {
        title.classList.remove("active-c-tab");
      }

      elt.classList.add("d-none");
    }
  });
};

