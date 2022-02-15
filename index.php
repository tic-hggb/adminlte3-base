<?php session_start() ?>
<?php $_login = false ?>
<?php $_admin = false ?>

<?php require("class/classMyDBC.php") ?>
<?php require("src/Random/random.php") ?>
<?php require("class/classCounter.php") ?>
<?php require("src/sessionControl.php") ?>
<?php require("src/fn.php") ?>

<?php extract($_GET) ?>
<?php if (isset($_SESSION['prm_userid'])): $_login = true; endif ?>
<?php if (isset($_SESSION['prm_useradmin']) and $_SESSION['prm_useradmin']): $_admin = true; endif ?>

<?php include('class/classMenu.php'); ?>
<?php $m = new Menu(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SISPlan</title>

	<link rel="apple-touch-icon" sizes="57x57" href="dist/img/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="dist/img/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="dist/img/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="dist/img/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="dist/img/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="dist/img/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="dist/img/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="dist/img/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="dist/img/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="dist/img/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="dist/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="dist/img/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="dist/img/favicon/favicon-16x16.png">
	<link rel="manifest" href="dist/img/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="dist/img/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.css">
	<!-- Tempusdominus Bootstrap 4 -->
	<link rel="stylesheet" href="node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="node_modules/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- JQVMap -->
	<link rel="stylesheet" href="node_modules/jqvmap-novulnerability/dist/jqvmap.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="node_modules/overlayscrollbars/css/OverlayScrollbars.min.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="node_modules/daterangepicker/daterangepicker.css">
	<!-- summernote -->
	<link rel="stylesheet" href="node_modules/summernote/dist/summernote-bs4.min.css">
	<!-- Noty -->
	<link rel="stylesheet" href="node_modules/noty/lib/noty.css">
	<link rel="stylesheet" href="node_modules/noty/lib/themes/sunset.css">
	<!-- Dropzone -->
	<link rel="stylesheet" href="node_modules/dropzone/dist/dropzone.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/adminlte.css">

	<!-- jQuery -->
	<script src="node_modules/jquery/dist/jquery.min.js"></script>
</head>

<?php if (isset($_SESSION['prm_userid'])): ?>
	<body class="hold-transition layout-fixed layout-navbar-fixed text-sm">
	<div class="wrapper">

		<div class="preloader flex-column justify-content-center align-items-center">
			<img class="animation__shake" src="dist/img/sisplan_gray.png" alt="SisplanLogo" height="60" width="60">
		</div>

		<nav class="main-header navbar navbar-expand navbar-info navbar-dark">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="#" id="btn-help" class="nav-link">Ayuda</a>
				</li>
			</ul>

			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" data-widget="fullscreen" href="#" role="button">
						<i class="fas fa-expand-arrows-alt"></i>
					</a>
				</li>

				<li class="nav-item dropdown user-menu">
					<a href="#" class="nav-link" data-toggle="dropdown">
						<img src="dist/img/<?php echo $_SESSION['prm_userpic'] ?>" class="user-image img-circle elevation-2" alt="User Image">
						<span class="d-none d-md-inline"><?php echo $_SESSION['prm_userfname'] . ' ' . $_SESSION['prm_userlnamep'] ?></span>
					</a>

					<ul class="dropdown-menu">
						<li class="user-header">
							<img src="dist/img/<?php echo $_SESSION['prm_userpic'] ?>" class="img-circle elevation-2" alt="User Image">
							<p><?php echo $_SESSION['prm_userfname'] . ' ' . $_SESSION['prm_userlnamep'] ?></p>
						</li>

						<li class="dropdown-item">
							<div class="row">
								<div class="btn-group-vertical col-12">
									<a href="index.php?section=adminusers&sbs=editprofile" class="btn btn-sm btn-block btn-default"><i class="fa fa-user"></i> Ver perfil</a>
									<a href="index.php?section=adminusers&sbs=changepass" class="btn btn-sm btn-block btn-default"><i class="fa fa-key"></i> Cambiar contraseña</a>
								</div>
							</div>
						</li>

						<li class="dropdown-footer">
							<button type="button" id="btn-logout" class="btn btn-danger btn-block">
								<i class="fa fa-power-off"></i> Salir
							</button>
						</li>
					</ul>
				</li>
			</ul>
		</nav>

		<aside class="main-sidebar sidebar-dark-info elevation-4">
			<a href="index.php" class="brand-link">
				<img src="dist/img/sisplan_white.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
				<span class="brand-text font-weight-light"><b>SIS</b>Plan</span>
			</a>

			<div class="sidebar">
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
						<li class="nav-header">PRINCIPAL</li>
						<li class="nav-item">
							<a href="index.php?section=home" class="nav-link <?php if (!isset($section) or $section == 'home' or $section == 'adminusers' or $section == 'forgotpass'): ?> active<?php endif ?>">
								<i class="nav-icon fa fa-home"></i>
								<p>Inicio</p>
							</a>
						</li>

						<?php $men = $m->getByProfile($_SESSION['prm_rol']['per']) ?>
						<?php foreach ($men as $mn): ?>
							<?php if ($mn->men_tipo == 1 and $mn->men_parent == ''): ?>
								<li class="nav-item">
									<a href="index.php?section=<?php echo $mn->men_link ?>"<?php if (isset($section) and $section == $mn->men_section): ?> class="active"<?php endif ?>>
										<i class="nav-icon fa <?php echo $mn->men_icon ?>"></i>
										<p><?php echo $mn->men_descripcion ?></p>
									</a>
								</li>

							<?php elseif ($mn->men_tipo == 2): ?>
								<li class="nav-item<?php if (isset($section) and $section == $mn->men_section): ?> menu-open<?php endif ?>">
									<a href="#" class="nav-link<?php if (isset($section) and $section == $mn->men_section): ?> active<?php endif ?>">
										<i class="nav-icon fa <?php echo $mn->men_icon ?>"></i>
										<p><?php echo $mn->men_descripcion ?><i class="right fas fa-angle-left"></i></p>
									</a>

									<ul class="nav nav-treeview">
										<?php $subm = $m->getChildByProfile($mn->men_id, $_SESSION['prm_rol']['per']) ?>
										<?php foreach ($subm as $smn): ?>
											<li class="nav-item">
												<a href="index.php?section=<?php echo $mn->men_section ?>&sbs=<?php echo $smn->men_link ?>" class="menu-item nav-link<?php if (isset($sbs) and $sbs == $smn->men_link): ?> active<?php endif ?>">
													<i class="far fa-circle nav-icon"></i>
													<p><?php echo $smn->men_descripcion ?></p>
												</a>
											</li>
										<?php endforeach ?>
									</ul>
								</li>
							<?php endif ?>
						<?php endforeach ?>

						<?php if ($_admin): ?>
							<li class="nav-header">PANEL DE CONTROL</li>

							<li class="nav-item<?php if (isset($section) and $section == 'users'): ?> menu-open<?php endif ?>">
								<a href="#" class="nav-link<?php if (isset($section) and $section == 'users'): ?> active<?php endif ?>">
									<i class="nav-icon fa fa-user"></i>
									<p>Usuarios<i class="right fas fa-angle-left"></i></p>
								</a>

								<ul class="nav nav-treeview">
									<li class="nav-item">
										<a href="index.php?section=users&sbs=createuser" class="nav-link<?php if (isset($sbs) and $sbs == 'createuser'): ?> active<?php endif ?>">
											<i class="far fa-circle nav-icon"></i>
											<p>Creación de usuarios</p>
										</a>
									</li>

									<li class="nav-item">
										<a href="index.php?section=users&sbs=manageusers" class="nav-link<?php if (isset($sbs) and $sbs == 'manageusers'): ?> active<?php endif ?>">
											<i class="far fa-circle nav-icon"></i>
											<p>Usuarios creados</p>
										</a>
									</li>
								</ul>
							</li>

							<li class="nav-item<?php if (isset($section) and $section == 'groups'): ?> menu-open<?php endif ?>">
								<a href="#" class="nav-link<?php if (isset($section) and $section == 'groups'): ?> active<?php endif ?>">
									<i class="nav-icon fa fa-users"></i>
									<p>Grupos<i class="right fas fa-angle-left"></i></p>
								</a>

								<ul class="nav nav-treeview">
									<li class="nav-item">
										<a href="index.php?section=groups&sbs=creategroup" class="nav-link<?php if (isset($sbs) and $sbs == 'creategroup'): ?> active<?php endif ?>">
											<i class="far fa-circle nav-icon"></i>
											<p>Creación de grupos</p>
										</a>
									</li>

									<li class="nav-item">
										<a href="index.php?section=groups&sbs=managegroups" class="nav-link<?php if (isset($sbs) and $sbs == 'managegroups'): ?> active<?php endif ?>">
											<i class="far fa-circle nav-icon"></i>
											<p>Grupos creados</p>
										</a>
									</li>
								</ul>
							</li>

							<li class="nav-item<?php if (isset($section) and $section == 'production'): ?> menu-open<?php endif ?>">
								<a href="#" class="nav-link<?php if (isset($section) and $section == 'production'): ?> active<?php endif ?>">
									<i class="nav-icon fa fa-user"></i>
									<p>Producción<i class="right fas fa-angle-left"></i></p>
								</a>

								<ul class="nav nav-treeview">
									<li class="nav-item">
										<a href="index.php?section=production&sbs=loadfile" class="nav-link<?php if (isset($sbs) and $sbs == 'loadfile'): ?> active<?php endif ?>">
											<i class="far fa-circle nav-icon"></i>
											<p>Carga de producción</p>
										</a>
									</li>
								</ul>
							</li>
						<?php endif ?>
					</ul>
				</nav>
			</div>
		</aside>

		<div class="content-wrapper">
			<?php include('src/routes.php'); ?>
		</div>

		<footer class="main-footer">
			<strong>Diseñado y desarrollado en HGGB - SDM/TIC <a target="_blank" href="https://www.hospitalregional.cl/sisplan">SISPLAN</a>.</strong> Todos los derechos reservados.
			<div class="float-right d-none d-sm-inline-block">
				<b>Version</b> 2.2.8
			</div>
		</footer>

	</div>

	<!-- jQuery UI 1.11.4 -->
	<script src="node_modules/jquery-ui-dist/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>$.widget.bridge('uibutton', $.ui.button)</script>
	<!-- Bootstrap 4 -->
	<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<!-- jQueryForm -->
	<script src="node_modules/jquery-form/dist/jquery.form.min.js"></script>
	<!-- DataTables  & Plugins -->
	<script src="node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="node_modules/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
	<script src="node_modules/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
	<script src="node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
	<script src="node_modules/jszip/dist/jszip.min.js"></script>
	<script src="node_modules/pdfmake/build/pdfmake.min.js"></script>
	<script src="node_modules/pdfmake/build/vfs_fonts.js"></script>
	<script src="node_modules/datatables.net-buttons/js/buttons.html5.min.js"></script>
	<script src="node_modules/datatables.net-buttons/js/buttons.print.min.js"></script>
	<script src="node_modules/datatables.net-buttons/js/buttons.colVis.min.js"></script>
	<!-- ChartJS -->
	<!--<script src="node_modules/chart.js/dist/Chart.min.js"></script>-->
	<!-- Sparkline -->
	<!--<script src="node_modules/sparklines/source/sparkline.js"></script>-->
	<!-- JQVMap -->
	<script src="node_modules/jqvmap-novulnerability/dist/jquery.vmap.min.js"></script>
	<script src="node_modules/jqvmap-novulnerability/dist/maps/jquery.vmap.usa.js"></script>
	<!-- jQuery Knob Chart -->
	<!--<script src="node_modules/jquery-knob-chif/dist/jquery.knob.min.js"></script>-->
	<!-- daterangepicker -->
	<script src="node_modules/moment/min/moment-with-locales.min.js"></script>
	<script src="node_modules/daterangepicker/daterangepicker.js"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"></script>
	<!-- Summernote -->
	<script src="node_modules/summernote/dist/summernote-bs4.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="node_modules/overlayscrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- SweetAlert -->
	<script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
	<!-- Noty -->
	<script src="node_modules/noty/lib/noty.min.js"></script>
	<!-- Morris.js -->
	<script src="node_modules/raphael/raphael.min.js"></script>
	<script src="node_modules/morris.js06/dist/morris.min.js"></script>
	<!-- Dropzone.js -->
	<script src="node_modules/dropzone/dist/min/dropzone.min.js"></script>
	<script type="text/javascript">// Immediately after the js include
      Dropzone.autoDiscover = false;
	</script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.js"></script>
	<script src="dist/js/jquery.Rut.min.js"></script>
	<script src="dist/js/fn.js"></script>
	<script src="dist/js/index.js"></script>
	</body>
<?php elseif (isset($section) and $section == 'forgotpass'): ?>
	<?php include('admin/users/retrieve-password.php') ?>
<?php else: ?>
	<?php include('src/login.php') ?>
<?php endif ?>
</html>
