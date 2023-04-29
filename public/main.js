// WebFontConfig

WebFontConfig = {
  google: {
    families: ["Poppins:300,400,500,600,700,800"],
  },
};
(function (d) {
  var wf = d.createElement("script"),
    s = d.scripts[0];
  wf.src = window.location.protocol + "//" + window.location.host + "/theme/js/webfont.js";
  wf.async = true;
  s.parentNode.insertBefore(wf, s);
})(document);


