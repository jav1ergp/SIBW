document.addEventListener('DOMContentLoaded', function() {  
    const campoBusqueda = document.getElementById('busqueda');  
    const resultados = document.getElementById('resultados-busqueda');
  
    campoBusqueda.addEventListener('input', function () {
        const peli = this.value;

        fetch(`buscar.php?peli=${encodeURIComponent(peli)}`)
            .then(response => response.json())
            .then(peliculas => {
                mostrarResultados(peliculas);
            });
    });
      
    function mostrarResultados(peliculas) {
    if (peliculas.length == 0) {
        resultados.innerHTML = '<div>No se encontraron películas</div>';
    } else {
        let html = '';
        peliculas.forEach(pelicula => {
            html += `<div class="resultado" data_id="${pelicula.id}">
                        ${pelicula.titulo}
                    </div>`
        });

        resultados.innerHTML = html;
        
        // Añadir event listeners a cada resultado
        resultados.querySelectorAll('.resultado').forEach(item => {
            item.addEventListener('click', function() {
                const id = this.getAttribute('data_id');
                window.location.href = "evento.php?id=" + id;
            });  
        });  
    }
}
});