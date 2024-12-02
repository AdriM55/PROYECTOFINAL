function reservar() {
    const horario = document.getElementById("horario").value;
    const asientosSeleccionados = Array.from(document.querySelectorAll(".asiento.seleccionado"))
                                       .map(asiento => asiento.dataset.asiento);
    const pelicula = document.getElementById('titulo-pelicula').textContent;

    fetch("reservas.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ horario_id: horario, asientos: asientosSeleccionados })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) alert("¡Reserva completada!");
        else alert(data.error || "Error en la reserva");
    });
}
