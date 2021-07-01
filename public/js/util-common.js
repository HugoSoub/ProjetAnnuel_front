// - Secured link (delete, archive)
$('body').on('click', '.util-confirm-link, a[data-toggle="confirm"]', function(e) {
    e.preventDefault();

    var url = $(this).attr('href');
    if (typeof $(this).attr('title') != 'undefined' && $(this).attr('title').length > 0)
        var title = $(this).attr('title');
    else
        var title = "Êtes-vous sûr de vouloir continuer ?";

    var text = $(this).attr('data-text');

    swal({
        title: title,
        text: text,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Oui, continuer !",
        cancelButtonText: "Annuler",
    }, function(result) {
        if (result) {
            document.location = url;
        }
    });
});
