<section class="content-header">
	<div class="callout callout-danger">
		<h4><i class="fa fa-times"></i> Error de acceso</h4>
		<p>Ha intentado ingresar a una sección restringida o inexistente. Si no está seguro de la razón, contacte con el administrador para mayor información.</p>
		<a class="btn btn-warning btn-lg" id="btnback"><i class="fa fa-undo"></i> Volver atrás</a> <a href="index.php" class="btn btn-info btn-lg" id="btnback"><i class="fa fa-home"></i> Volver al inicio</a>
	</div>
</section>

<script>
    $(document).ready( function() {
        $('#btnback').click( function() {
            window.history.back();
        });
    });
</script>
