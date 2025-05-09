document.addEventListener("DOMContentLoaded", function () {    
    const comentarios = document.querySelector(".comentarios");
    const show_comentarios = document.getElementById("show_comentarios");
    show_comentarios.addEventListener('click', show_coments);
    var show = false;
    
    //Mostrar comentarios
    function show_coments() {
        show = !show;
        if(show) {
            comentarios.style.display = "flex";
        }else{
            comentarios.style.display = "none";
        }
    }
    
    
    // Mostrar cada comentario
    const seccion_coments = document.querySelector(".coments");
    const enviar = document.getElementById("form");
    enviar.addEventListener("submit", save_comments);
    
    function save_comments() {
        const nombre = document.getElementById("nombre").value.trim();
        const correo = document.getElementById("correo").value.trim();
        const comentario = document.getElementById("comentario").value.trim();
    
        if (nombre === "" || correo === "" || comentario === "") {
            event.preventDefault();
            alert("Completa todos los camaapos.");
            return;
        }
    
        // Validacion correo
        if (!/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]+$/.test(correo)) {
            event.preventDefault();
            document.getElementById("correo").value = "";
            alert("Correo Incorrecto");
            return;
        }        
    }
    
    
    // Palabras prohibidas
    const coments_box = document.getElementById("comentario");
    coments_box.addEventListener('input', change);

    const palabrasDiv = document.getElementById('palabras');
    const PALABRAS_PROHIBIDAS = palabrasDiv.textContent.trim().split(/\s+/);

    function change(event) {
        let texto = event.target.value;
        
        PALABRAS_PROHIBIDAS.forEach(palabra => {
            if (texto.toLowerCase().includes(palabra)) {
                const p_prohibida = new RegExp(palabra, 'i');     //mayus
                texto = texto.replace(p_prohibida, '*'.repeat(palabra.length));        
            }
        });
        
        event.target.value = texto;
    }
});