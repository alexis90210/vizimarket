//  update desing effect

var preview = document.getElementById('preview-design');

preview.style = constructStyle(13)

function updateStyle() {

    let police = document.getElementById('police').value
    let policeperso = document.getElementById('police-personnalisee').value
    let taille = document.getElementById('taille').value

    preview.style = constructStyle(taille, policeperso)
}

function constructStyle( police , fontfamily ) {

    if(!fontfamily) fontfamily = 'Inter'
    let defaultstyle = `border:1px dashed #2F8038; border-radius:10px; padding:10px;font-size:${police}px;font-family:${fontfamily}`;
    return defaultstyle;
}

function setColor(id) {
   let selected =  document.getElementById(`${id}`).value
   document.getElementById(`${id}-2`).value = selected

}