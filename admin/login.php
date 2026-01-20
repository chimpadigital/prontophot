<?php
session_start();
unset($_SESSION['pronto']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<title>Ingreso Sistema</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/jquery-3.6.0.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<script>
		$(function() {
			$('#login').submit(function(e) {
				e.preventDefault();
				$.ajax({
					url: 'inc/entrar.php',
					type: 'POST',
					data: $("#login").serialize(),
					dataType: 'json',
					xhrFields: {
						withCredentials: true // 👈 ESTO es lo importante
					},
					success: function(result) {
						if (result.success) {
							$('#aviso').show('slow')
								.removeClass('alert-warning')
								.addClass('alert-success')
								.html('Datos Aceptados, Bienvenido');

							setTimeout(function() {
								window.location = "index.php";
							}, 2000);
						} else {
							$('#aviso').show('slow')
								.removeClass('alert-success')
								.addClass('alert-warning')
								.html(result.msg)
								.delay(3000).fadeOut('slow');
						}
					}
				});


			});

		});
	</script>
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
				<div class="card card-signin my-5">
					<div class="card-header">
						<h5 class="card-title text-center">Login</h5>
					</div>
					<div class="card-body">
						<form class="form-signin" id="login">
							<div class="form-label-group">
								<label for="user">Usuario</label>
								<input type="text" id="user" class="form-control" name="usuario" placeholder="usuario" required autofocus>
							</div>
							<div class="form-label-group">
								<label for="password">Password</label>
								<input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
							</div>
							<div class="text-center w-100">
								<button class="btn btn-lg btn-primary btn-block my-3 ml-auto mr-auto text-uppercase" type="submit">Ingresar</button>
							</div>

							<p id="aviso" class="alert" style="display: none;"></p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>