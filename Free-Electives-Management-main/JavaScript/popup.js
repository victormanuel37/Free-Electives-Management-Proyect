function confirmDelete() {
    if (confirm("Are you sure you want to delete this item?")) {
        setTimeout(function() {
            location.reload(); // Recargar la página después de enviar el formulario
        }, 500); // Espera un breve momento antes de recargar
        return true;
    }
    return false;
}