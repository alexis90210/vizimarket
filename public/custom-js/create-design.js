


function createDesign() {

    $('#configuration-createur').waitMe({
        effect: "bounce",
        text: "",
        bg: "rgba(255,255,255,0.7)",
        color: "#556CE5",
        maxSize: "",
        waitTime: -1,
        textPos: "vertical",
        fontSize: "",
        source: "",
        onClose: function () {},
      });


    let configuration_id = document.getElementById('configuration-createur').getAttribute('configuration-id');
    let createur_id = document.getElementById('configuration-createur').getAttribute('createur-id');
    let couleur_fond = document.getElementById('couleur-fond-2').value;
    let couleur_header = document.getElementById('couleur-header-2').value;
    let couleur_footer = document.getElementById('couleur-footer-2').value;
    let police = document.getElementById('police').value;
    let taille = document.getElementById('taille').value;
    let protocol = window.location.protocol;
    let host = window.location.host;

    let payload = {
        configuration_id: configuration_id,
        createur_id: createur_id,
        couleur_fond:couleur_fond,
        couleur_header: couleur_header,
        couleur_footer: couleur_footer,
        police: police,
        taille: taille
    };

    let json  = JSON.stringify( payload );
    let url = `${protocol}//${host}/api/v1/create/createur/design`

    console.log(url);

    console.log(json);

    let options = {
        method:"POST",
        body:json,
        headers: { "Content-Type": "application/json", },
    }



    fetch(url , options)
    .then( res => res.json())
    .then( (reponse) => {
        $('#configuration-createur').waitMe('hide')
        console.log(reponse);
        if ( reponse.code == "success") {
            spop("Success, " + reponse.message, "success")
        } else {
            spop("Erreur, mise a jour impossible", "danger")
        }
    })
    .catch( error => {
        $('#configuration-createur').waitMe('hide')
        console.log(error);
        spop(error, "danger")
    })


}