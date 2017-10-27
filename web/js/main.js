$(document).on("submit", "#finances-form", function() {
    if (confirm("Вы действительно хотите списать средства?")) {
        return true;
    } else {
        return false;
    }
    return false;
});