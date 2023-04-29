var getConversationUrl =
  window.location.protocol +
  "//" +
  window.location.host +
  "/api/message/prive/get/conversation/for/one/client";

var getCurrentConversationUrl =
  window.location.protocol +
  "//" +
  window.location.host +
  "/api/message/prive/get/current/conversation";

function displayActiveConversation() {
  // Créez un objet FormData pour stocker les données que vous souhaitez envoyer

  const obj = {
    idConversation: document.getElementById("active-conversation").value,
  };

  // Configurer les options de la requête, y compris la méthode, l'en-tête et le corps de la requête
  const options = {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(obj),
  };

  // Utiliser la méthode fetch pour envoyer la requête
  fetch(getConversationUrl, options)
    .then((response) => response.json())
    .then((data) => {
      // Traiter la réponse ici
      displayConversation(data);
    })
    .catch((error) => console.error(error));
}

function displayConversation(conversation) {
  let messages = conversation.messagePrives;
  var client = conversation.client;
  var vendeur = conversation.vendeur;
  var idConversation = conversation.id;

  document.getElementById("user-chat-contenu").innerHTML = "";

  document.getElementById("user-chat-contenu").innerHTML = `<div class="card">
  <div class="p-4 border-bottom">
    <div class="row">
      <div class="col-md-4 col-9">
        <h5 class="font-size-15 mb-1 text-truncate">${client.nom}</h5>
        <p class="text-muted mb-0 text-truncate">
          <i class="mdi mdi-circle text-success align-middle me-1"></i>
          Active now
        </p>
      </div>
      <div class="col-md-8 col-3">
        <ul class="list-inline user-chat-nav text-end mb-0">
          <li class="list-inline-item d-none d-sm-inline-block">
            <div class="dropdown">
              <button
                class="btn nav-btn dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="mdi mdi-magnify font-size-18"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-md">
                <form class="p-3">
                  <div class="form-group m-0">
                    <div class="input-group">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Search ..."
                        aria-label="Recipient's username"
                      />

                      <button class="btn btn-primary" type="submit">
                        <i class="mdi mdi-magnify"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </li>
          <li class="list-inline-item d-none d-sm-inline-block">
            <div class="dropdown">
              <button
                class="btn nav-btn dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="mdi mdi-cog-outline font-size-18"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="#">
                  View Profile
                </a>
                <a class="dropdown-item" href="#">
                  Clear chat
                </a>
                <a class="dropdown-item" href="#">
                  Muted
                </a>
                <a class="dropdown-item" href="#">
                  Delete
                </a>
              </div>
            </div>
          </li>

          <li class="list-inline-item">
            <div class="dropdown">
              <button
                class="btn nav-btn dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="mdi mdi-dots-horizontal font-size-18"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="#">
                  Action
                </a>
                <a class="dropdown-item" href="#">
                  Another action
                </a>
                <a class="dropdown-item" href="#">
                  Something else
                </a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div>
    <div id='chat-conversation-scroll' class="chat-conversation p-3">
      <ul
        class="list-unstyled"
        data-simplebar
        style="max-height: 600px;"
        id="chat-contenu-message"
      >
        <li>
          <div class="chat-day-title">
            <span class="title">Today</span>
          </div>
        </li>
      </ul>
    </div>
    <div class="p-3 chat-input-section">
      <div class="row">
        <div class="col">
          <div class="position-relative">
            <input
            id="chat-input"
              type="text"
              class="form-control chat-input"
              placeholder="Enter Message..."
            />
            <div class="chat-input-links">
              <ul class="list-inline mb-0">
                <li class="list-inline-item">
                  <a
                    href="#"
                    data-bs-toggle="tooltip"
                    data-placement="top"
                    title="Emoji"
                  >
                    <i class="mdi mdi-emoticon-happy-outline"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a
                    href="#"
                    data-bs-toggle="tooltip"
                    data-placement="top"
                    title="Images"
                  >
                    <i class="mdi mdi-file-image-outline"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a
                    href="#"
                    data-bs-toggle="tooltip"
                    data-placement="top"
                    title="Add Files"
                  >
                    <i class="mdi mdi-file-document-outline"></i>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-auto" id="idConversationEnCours" data-idConversationEnCours="${idConversation}">
          <button onclick="sendMessage(${idConversation})"
            class="btn btn-primary btn-rounded chat-send w-md waves-effect waves-light"
          >
            <span class="d-none d-sm-inline-block me-2">Envoyer</span>
            <i class="mdi mdi-send"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>`;

  messages.forEach((element, index) => {
    var lastIndex = messages.length - 1;
    if (element.sens == 1) {
      if (lastIndex == index) {
        document.getElementById(
          "chat-contenu-message"
        ).innerHTML += `<li message-id='${element.id}'>
        <div class="conversation-list">
          <div class="media">
            <img
              src="${client.photo}"
              class="rounded-circle avatar-xs"
              alt=""
            />
            <div class="media-body arrow-left ms-3">
              <div class="dropdown">
                <a
                  class="dropdown-toggle"
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <i class="bx bx-dots-vertical-rounded"></i>
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#">
                    Copy
                  </a>
                  <a class="dropdown-item" href="#">
                    Save
                  </a>
                  <a class="dropdown-item" href="#">
                    Forward
                  </a>
                  <a class="dropdown-item" href="#">
                    Delete
                  </a>
                </div>
              </div>
  
              <div class="ctext-wrap">
                <div class="conversation-name">${client.nom}</div>
                <p>${element.contenu}</p>
                <p class="chat-time mb-0">
                  <i class="bx bx-time-five align-middle me-1"></i>
                  ${element.date}
                </p>
              </div>
            </div>
          </div>
        </div>
      </li>
     `;
      } else {
        document.getElementById(
          "chat-contenu-message"
        ).innerHTML += `<li message-id='${element.id}'>
        <div class="conversation-list">
          <div class="media">
            <img
              src="${client.photo}"
              class="rounded-circle avatar-xs"
              alt=""
            />
            <div class="media-body arrow-left ms-3">
              <div class="dropdown">
                <a
                  class="dropdown-toggle"
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <i class="bx bx-dots-vertical-rounded"></i>
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#">
                    Copy
                  </a>
                  <a class="dropdown-item" href="#">
                    Save
                  </a>
                  <a class="dropdown-item" href="#">
                    Forward
                  </a>
                  <a class="dropdown-item" href="#">
                    Delete
                  </a>
                </div>
              </div>
  
              <div class="ctext-wrap">
                <div class="conversation-name">${client.nom}</div>
                <p>${element.contenu}</p>
                <p class="chat-time mb-0">
                  <i class="bx bx-time-five align-middle me-1"></i>
                  ${element.date}
                </p>
              </div>
            </div>
          </div>
        </div>
      </li>`;
      }
    } else {
      if (lastIndex == index) {
        document.getElementById("chat-contenu-message").innerHTML += `
      
      <li class="right" message-id='${element.id}'>
            <div class="conversation-list">
              <div class="media">
                <div class="media-body arrow-right me-3">
                  <div class="dropdown">
                    <a
                      class="dropdown-toggle"
                      href="#"
                      role="button"
                      data-bs-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="#">
                        Copy
                      </a>
                      <a class="dropdown-item" href="#">
                        Save
                      </a>
                      <a class="dropdown-item" href="#">
                        Forward
                      </a>
                      <a class="dropdown-item" href="#">
                        Delete
                      </a>
                    </div>
                  </div>
                  <div class="ctext-wrap">
                    <div class="conversation-name">Vous</div>
                    <p>${element.contenu}</p>

                    <p class="chat-time mb-0">
                      <i class="bx bx-time-five align-middle me-1"></i>
                      ${element.date}
                    </p>
                  </div>
                </div>

                <img
                  src="${vendeur.ImageProfile}"
                  class="rounded-circle avatar-xs"
                  alt=""
                />
              </div>
            </div>
          </li>
      `;
      } else {
        document.getElementById("chat-contenu-message").innerHTML += `
      
      <li class="right" message-id='${element.id}'>
            <div class="conversation-list">
              <div class="media">
                <div class="media-body arrow-right me-3">
                  <div class="dropdown">
                    <a
                      class="dropdown-toggle"
                      href="#"
                      role="button"
                      data-bs-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="#">
                        Copy
                      </a>
                      <a class="dropdown-item" href="#">
                        Save
                      </a>
                      <a class="dropdown-item" href="#">
                        Forward
                      </a>
                      <a class="dropdown-item" href="#">
                        Delete
                      </a>
                    </div>
                  </div>
                  <div class="ctext-wrap">
                    <div class="conversation-name">Vous</div>
                    <p>${element.contenu}</p>

                    <p class="chat-time mb-0">
                      <i class="bx bx-time-five align-middle me-1"></i>
                      ${element.date}
                    </p>
                  </div>
                </div>

                <img
                  src="${vendeur.ImageProfile}"
                  class="rounded-circle avatar-xs"
                  alt=""
                />
              </div>
            </div>
      </li>
      `;
      }
    }
  });
}

function getSpecifiqueConversation(id) {
  // Créez un objet FormData pour stocker les données que vous souhaitez envoyer
  const obj = {
    idConversation: id,
  };
  // Configurer les options de la requête, y compris la méthode, l'en-tête et le corps de la requête
  const options = {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(obj),
  };

  // Utiliser la méthode fetch pour envoyer la requête
  fetch(getConversationUrl, options)
    .then((response) => response.json())
    .then((data) => {
      // Traiter la réponse ici
      displayConversation(data);
    })
    .catch((error) => console.error(error));
}

function sendMessage(idConversation) {
  console.log("On ajoute le message");
  const addMessageUrl =
    window.location.protocol +
    "//" +
    window.location.host +
    "/api/message/prive/add/message";
  const obj = {
    idConversation: idConversation,
    contenu: document.getElementById("chat-input").value,
    sens: 2,
  };

  document.getElementById("chat-input").value = "";

  //console.log(obj);

  fetch(addMessageUrl, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(obj),
  })
    .then((response) => response.json())
    .then((data) => {
      // Traiter la réponse ici
      // console.log(data);
    })
    .catch((error) => console.error(error));
}

function charge() {
  // Créez un objet FormData pour stocker les données que vous souhaitez envoyer
  console.log("ON COMMENCE LE CHARGEMENT");

  //console.log(document.getElementById("idConversationEnCours"));

  if (document.getElementById("idConversationEnCours") != undefined) {
    //console.log("ON recupere id onversation en cours");
    const idConversation = document
      .getElementById("idConversationEnCours")
      .getAttribute("data-idConversationEnCours");

    var obj = {
      idConversation: idConversation,
      idLastMessage: document
        .querySelector("#chat-contenu-message > div > div > div > div > div")
        .lastElementChild.getAttribute("message-id"),
    };

    //console.log(obj);

    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(obj),
    };

    // Utiliser la méthode fetch pour envoyer la requête
    fetch(getCurrentConversationUrl, options)
      .then((response) => response.json())
      .then((data) => {
        // Traiter la réponse ici
        const messages = data.message;

        //console.log(messages);

        if (messages.length > 0) {
          messages.forEach((element) => {
            if (element.sens == 1) {
              const newLi = document.createElement("li");
              newLi.setAttribute("message-id", element.id);
              newLi.innerHTML = `
              <div class="conversation-list">
                <div class="media">
                <img
                src="${element.photo}"
                class="rounded-circle avatar-xs"
                alt=""
                />
                <div class="media-body arrow-left ms-3">
                <div class="dropdown">
                  <a
                    class="dropdown-toggle"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">
                      Copy
                    </a>
                    <a class="dropdown-item" href="#">
                      Save
                    </a>
                    <a class="dropdown-item" href="#">
                      Forward
                    </a>
                    <a class="dropdown-item" href="#">
                      Delete
                    </a>
                  </div>
                </div>
    
                <div class="ctext-wrap">
                  <div class="conversation-name">${element.nom}</div>
                  <p>${element.contenu}</p>
                  <p class="chat-time mb-0">
                    <i class="bx bx-time-five align-middle me-1"></i>
                    ${element.date}
                  </p>
                </div>
              </div>
            </div>
          </div>`;

              document
                .querySelector(
                  "#chat-contenu-message > div > div > div > div > div"
                )
                .appendChild(newLi);
            } else {
              const newLi = document.createElement("li");
              newLi.classList.add("right");
              newLi.setAttribute("message-id", element.id);
              newLi.innerHTML = `
               <div class="conversation-list">
                <div class="media">
                <div class="media-body arrow-right me-3">
                  <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="#">Copy</a>
                      <a class="dropdown-item" href="#">Save</a>
                      <a class="dropdown-item" href="#">Forward</a>
                      <a class="dropdown-item" href="#">Delete</a>
                    </div>
                  </div>
                  <div class="ctext-wrap">
                    <div class="conversation-name">Vous</div>
                    <p>${element.contenu}</p>
                    <p class="chat-time mb-0"><i class="bx bx-time-five align-middle me-1"></i>${element.date}</p>
                  </div>
                </div>
                <img src="" alt="" class="rounded-circle avatar-xs" alt="" />
              </div>
            </div>
          `;

              document
                .querySelector(
                  "#chat-contenu-message > div > div > div > div > div"
                )
                .appendChild(newLi);
            }

            var element = document.querySelector(
              "#chat-contenu-message > div > div > div > div > div"
            );
            element.scrollIntoView({
              behavior: "smooth",
              block: "end",
              inline: "nearest",
            });
          });
        }

        setTimeout(() => {
          charge();
        }, 1000);
      })
      .catch((error) => console.log(error));
  }

  // Configurer les options de la requête, y compris la méthode, l'en-tête et le corps de la requête
}

displayActiveConversation();

setTimeout(() => {
  charge();
}, 5000);
