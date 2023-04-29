var quill_cgv = new Quill("#cgv", {
  modules: {
    toolbar: [
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      ["bold", "italic", "underline", "strike"],
      ["image", "code-block"],
      ["link"],
      [{ script: "sub" }, { script: "super" }],
      [{ list: "ordered" }, { list: "bullet" }],
      ["clean"],
    ],
  },
  placeholder: "Remplir ici",
  theme: "snow",
});

var quill_mention = new Quill("#mention-legale", {
  modules: {
    toolbar: [
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      ["bold", "italic", "underline", "strike"],
      ["image", "code-block"],
      ["link"],
      [{ script: "sub" }, { script: "super" }],
      [{ list: "ordered" }, { list: "bullet" }],
      ["clean"],
    ],
  },
  placeholder: "Remplir ici",
  theme: "snow",
});

var quill_cgu = new Quill("#cgu", {
  modules: {
    toolbar: [
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      ["bold", "italic", "underline", "strike"],
      ["image", "code-block"],
      ["link"],
      [{ script: "sub" }, { script: "super" }],
      [{ list: "ordered" }, { list: "bullet" }],
      ["clean"],
    ],
  },
  placeholder: "Remplir ici",
  theme: "snow",
});

var quill_politique = new Quill("#politique-config", {
  modules: {
    toolbar: [
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      ["bold", "italic", "underline", "strike"],
      ["image", "code-block"],
      ["link"],
      [{ script: "sub" }, { script: "super" }],
      [{ list: "ordered" }, { list: "bullet" }],
      ["clean"],
    ],
  },
  placeholder: "Remplir ici",
  theme: "snow",
});
