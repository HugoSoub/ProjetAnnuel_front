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

$('body').on('click', '.js-add-user-session', function() {
    session_id = $(this).data('id');
    $.get(Routing.generate('user_all'), function(result) {
		users = result;

        Swal.fire({
            title: "Ajouter un nouveau candidat",
            // input: 'select',
            // inputPlaceholder: 'Sélectionnez un candidat',
            // inputOptions: users,
            // inputAttributes: {
            //     'id': 'js-select2'
            // },
            html:'<select class="swal2-select js-select2" style="display: flex;"><option value="" disabled="">Sélectionnez un candidat</option><option value="8">DuJardin mireille</option><option value="9">LoupeSonTir Kylian</option><option value="10">Poutre gerard</option></select>',
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            didOpen: () => {
                $(".js-select2").select2();
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
					type: 'POST',
					url: Routing.generate('session_user_add', { 'session_id': session_id, 'user_id': result.value }),
					success: function(result) {}
				});
            }
        });
    });
})



$(".js-select2").select2();
