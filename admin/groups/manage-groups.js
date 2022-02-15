$(document).ready( function() {
    var tableUsr = $("#tgroups").DataTable({
        "columns": [ null, { className: "text-center" }, { "orderable": false, width: "90px", className: "text-center" } ]
    });

    $(".groupModal").tooltip();
    $(".groupEdit").tooltip();
    $(".groupDelete").tooltip();

    $(".groupModal").click( function() {
        var uid = $(this).attr('id').split("_").pop();

        $.ajax( {
            url:        'admin/groups/ajax.getGroup.php',
            type:       'POST',
            dataType:   'json',
            data:       { id: uid }
        })
        .done( function(d) {
            console.log(d);
            $("#g_nombre").html( '' );
            $("#g_fecha").html( '' );

            if (d.us_id !== null) {
                $("#g_name").html( ':: ' + d.gr_nombre );
                $("#g_nombre").html( d.gr_nombre );
                $("#g_fecha").html( getDateBD(d.gr_fecha) );
                $("#g_pnombre").html( d.gr_pnombre );
            }
        });
    });

    $(".groupDelete").click( function() {
        var uid = $(this).attr('id').split("_").pop();
        $(this).parent().parent().addClass('selected');

        swal({
            title: "¿Está seguro de querer eliminar el grupo?",
            text: "Esta acción borrará todos los registros relacionados al grupo.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí"
		}).then((result) => {
			if(result.value) {
                $.ajax( {
                    url:        'admin/groups/ajax.delGroup.php',
                    type:       'POST',
                    dataType:   'json',
                    data:       { id: uid }
                }).done( function(response) {
                    if (response.type === true) {
                        new Noty({
                            text: 'El grupo ha sido eliminado con éxito.',
                            type: 'success'
                        }).show();
                        tableUsr.row('.selected').remove().draw( false );
                    }
                    else {
                        new Noty({
                            text: 'Hubo un problema al eliminar el grupo. <br>' + response.msg,
                            type: 'error'
                        }).show();
                        tableUsr.$('tr.selected').removeClass('selected');
                    }
                });
            }
			else if (result.dismiss === swal.DismissReason.cancel) {
                tableUsr.$('tr.selected').removeClass('selected');
            }
        });
    });
    
    //Check to see if the window is top if not then display button
    $(window).scroll( function() {
        if ($(this).scrollTop() > 200) {
            $('.scrollToTop').fadeIn();
        } 
        else {
            $('.scrollToTop').fadeOut();
        }
    });

    //Click event to scroll to top
    $('.scrollToTop').click( function() {
        $('html, body').animate({scrollTop : 0}, 800);
        return false;
    });
});