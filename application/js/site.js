$(document).ready ( function () {
    $("#rules-title").click(function(e) {
        $("#rules").toggle();
        e.preventDefault();
    });
});