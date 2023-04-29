function createContent() {

    let cgv = quill_cgv.root.innerHTML;
    let mention_legale = quill_mention.root.innerHTML;
    let cgu = quill_cgu.root.innerHTML;
    let politique = quill_politique.root.innerHTML;

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
    let protocol = window.location.protocol;
    let host = window.location.host;

    let payload = {
        configuration_id: configuration_id,
        createur_id: createur_id,
        cgv:cgv,
        mention_legale:mention_legale,
        cgu:cgu,
        politique:politique
    };

    let json  = JSON.stringify( payload );
    let url = `${protocol}//${host}/api/v1/create/createur/contenu`

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