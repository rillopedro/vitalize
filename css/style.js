const input = document.getElementById("imagem");
const preview = document.getElementById("preview");
const botao = document.querySelector(".btn-upload");
const texto = document.getElementById("texto");

input.addEventListener("change", function(){

    if(this.files && this.files[0]){

        preview.src = URL.createObjectURL(this.files[0]);

        preview.style.display = "block";
        botao.style.display = "none";
        texto.style.display = "none";
    }

});