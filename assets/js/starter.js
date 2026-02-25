$(document).ready(function () {
	getCart();
	//agregado

	$('#btnDescuento').click(function (e) {
		e.preventDefault();
		var cupon = $('#codDescuento').val();
		var seccion = $('#codDescuento').data('seccion');
		$.post('inc/aplicar_cupon.php', { cupon: cupon, seccion: seccion }, function (data) {
			console.log(data);
			if (data.success) {
				location.reload();
			} else {
				alert(data.msg);
			}
		}, 'json');
	});



	$('.btn-recuperar').click(function (e) {
		e.preventDefault();
		var boton = $(this).html();
		$(this).html('<i class="fa fa-spin fa-spinner" aria-hidden="true"></i>');
		var email = $('#recuperar-clave').val();
		$.post('inc/recuperar_clave.php', { email: email }, function (data) {
			$('.btn-recuperar').html('Enviar');
			console.log(data);
			if (data.success) {
				$('.respuesta').html('<p class="alert alert-success">Recibira un correo electronico para validar su cuenta y cambiar su clave de accesso</p>');
				setTimeout(function () { location.reload(); }, 5000);
			} else {
				$('.respuesta').html('<p class="alert alert-info">' + data.msg + '</p>');
				setTimeout(function () { limpiarDiv('.respuesta'); }, 3000);
			}
		}, 'json');
	});
	$('.addtoCart').click(function (e) {
		e.preventDefault();
		var cantidad = $('#cantidad').val();
		var color = $('#color').val();
		var id = $(this).data('id');
		var cat = $(this).data('categoria');
		var precio = $(this).data('precio');

		$.post('inc/set_cart.php', { id: id, color: color, cant: cantidad, cat: cat, precio: precio }, function(data) {
			console.log('Respuesta del carrito:', data);
			if (data.success) {
				getCart();
				alert('Producto agregado al carrito exitosamente');
			} else {
				//alert(data.msg);
			}
		}, 'json').fail(function(xhr, status, error) {
			console.error('Error al agregar al carrito:', error);
			console.log('Respuesta:', xhr.responseText);
		});
	});
	$('.cerrarSesion').click(function (e) {
		e.preventDefault();
		$.post('inc/salir.php', function (data) {
			window.location.reload();
		});

	});

	$('#nuevoRegistro').submit(function (e) {
		e.preventDefault();

		// Limpiar errores previos
		$('.form-text.text-danger').addClass('d-none');
		$('.form-control, select').removeClass('is-invalid');

		var esValido = true;

		// Validar Nombre
		if ($('#nombre').val().trim() === '') {
			mostrarError('nombre', 'El nombre es obligatorio');
			esValido = false;
		}

		// Validar Apellido
		if ($('#apellido').val().trim() === '') {
			mostrarError('apellido', 'El apellido es obligatorio');
			esValido = false;
		}

		// Validar DNI
		var dni = $('#DNI').val().trim();
		if (dni === '') {
			mostrarError('DNI', 'El DNI es obligatorio');
			esValido = false;
		} else if (!/^\d{7,8}$/.test(dni)) {
			mostrarError('DNI', 'El DNI debe tener 7 u 8 dígitos');
			esValido = false;
		}

		// Validar Email
		var email = $('#inputEmail4').val().trim();
		if (email === '') {
			mostrarError('inputEmail4', 'El email es obligatorio');
			esValido = false;
		} else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
			mostrarError('inputEmail4', 'Ingrese un email válido');
			esValido = false;
		}

		// Validar Contraseña
		var pass1 = $('#reg-pass1').val();
		if (pass1 === '') {
			mostrarError('reg-pass1', 'La contraseña es obligatoria');
			esValido = false;
		} else if (pass1.length < 6) {
			mostrarError('reg-pass1', 'La contraseña debe tener al menos 6 caracteres');
			esValido = false;
		}

		// Validar Confirmar Contraseña
		var pass2 = $('#reg-pass2').val();
		if (pass2 === '') {
			mostrarError('reg-pass2', 'Debe confirmar la contraseña');
			esValido = false;
		} else if (pass1 !== pass2) {
			mostrarError('reg-pass2', 'Las contraseñas no coinciden');
			esValido = false;
		}

		// Validar Dirección
		if ($('#Dirección').val().trim() === '') {
			mostrarError('Dirección', 'La dirección es obligatoria');
			esValido = false;
		}

		// Validar Provincia
		if ($('#Provincia').val() === '' || $('#Provincia').val() === 'Seleccione...') {
			mostrarError('Provincia', 'Debe seleccionar una provincia');
			esValido = false;
		}

		// Validar Ciudad
		if ($('#Ciudad').val().trim() === '') {
			mostrarError('Ciudad', 'La ciudad es obligatoria');
			esValido = false;
		}

		// Validar CP
		var cp = $('#CP').val().trim();
		if (cp === '') {
			mostrarError('CP', 'El código postal es obligatorio');
			esValido = false;
		} else if (!/^\d{4,8}$/.test(cp)) {
			mostrarError('CP', 'El código postal debe tener entre 4 y 8 dígitos');
			esValido = false;
		}

		// Validar Altura
		var altura = $('#Altura').val().trim();
		if (altura === '') {
			mostrarError('Altura', 'La altura es obligatoria');
			esValido = false;
		} else if (!/^\d+$/.test(altura) || parseInt(altura) < 1) {
			mostrarError('Altura', 'La altura debe ser un número válido');
			esValido = false;
		}

		// Si todo es válido, enviar el formulario
		if (esValido) {
			var data = new FormData(this);

			$.ajax({
				type: 'POST',
				url: 'inc/guardar_registro.php',
				data: data,
				contentType: false,
				dataType: "json",
				cache: false,
				processData: false,
				success: function (data) {
					if (data.success) {
						$('#modal-registrarse').modal('hide');
						$('body').removeClass('modal-open');
						$('.modal-backdrop').remove();
						alert('Tu cuenta ah sido creada exitosamente. Ya podes ingresar con tus datos');
						location.reload();
					} else {
						alert(data.msg);
					}
				}
			});
		}
	});

	// Función auxiliar para mostrar errores
	function mostrarError(campoId, mensaje) {
		$('#' + campoId).addClass('is-invalid');
		$('#error-' + campoId).text(mensaje).removeClass('d-none');
	}

	$('#act-form').submit(function (e) {
		e.preventDefault();
		var pass1 = $('#act-pass1').val();
		var pass2 = $('#act-pass2').val();
		if (pass1 === pass2) {
			var data = new FormData(this);

			$.ajax({
				type: 'POST',
				url: 'inc/update_clave.php',
				data: data,
				contentType: false,
				dataType: "json",
				cache: false,
				processData: false,
				success: function (data) {
					if (data.success) {
						$('#modal-actualizar-contraseña').modal('hide');
						$('body').removeClass('modal-open');
						$('.modal-backdrop').remove();
						alert('Tu clave fue actualizada exitosamente. Ya podes ingresar con tus datos');
						location.href = '/';

					} else {
						alert(data.msg);
					}

				}
			});
		} else {
			alert('No coinciden las claves');
			$('#act-pass1').focus();
		}
	});

	$('#loginForm').submit(function (e) {
		e.preventDefault();
		var data = new FormData(this);
		$.ajax({
			type: 'POST',
			url: 'inc/login_cliente.php',
			data: data,
			contentType: false,
			dataType: "json",
			cache: false,
			processData: false,
			success: function (data) {
				if (data.success) {
					$('#iniciar-sesion').modal('hide')
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					window.location.reload();
				} else {
					alert(data.msg);
					console.log(data.error);
				}

			}
		});
	});
	//fin agregado


	var sync1 = $("#sync1");
	var sync2 = $("#sync2");
	var slidesPerPage = 6; //globaly define number of elements per page
	var syncedSecondary = true;

	sync1.owlCarousel({
		items: 1,
		slideSpeed: 2000,
		nav: true,
		autoplay: false,
		dots: false,
		loop: true,
		responsiveRefreshRate: 200,
		navText: ['<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #DA0000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>', '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #DA0000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'],
	}).on('changed.owl.carousel', syncPosition);

	sync2
		.on('initialized.owl.carousel', function () {
			sync2.find(".owl-item").eq(0).addClass("current");
		})
		.owlCarousel({
			items: slidesPerPage,
			dots: false,
			nav: false,
			smartSpeed: 200,
			slideSpeed: 500,
			slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
			responsiveRefreshRate: 100
		}).on('changed.owl.carousel', syncPosition2);

	function syncPosition(el) {
		var index = el.item.index;
		$('#sync2 .owl-item').removeClass('current');
		$('#sync2 .owl-item').eq(index).addClass('current');
	}

	function syncPosition2(el) {
		if (syncedSecondary) {
			var number = el.item.index;
			sync1.data('owl.carousel').to(number, 100, true);
		}
	}

	$('#sync2').on('click', '.owl-item', function (e) {
		e.preventDefault();
		var index = $(this).index();
		$('#sync1').trigger('to.owl.carousel', [index, 300, true]);
	});
});

const currentLocation = location.href;
const menuItem = document.querySelectorAll('.navbar.navbar-desktop a.nav-link'); //dejar a solamente 'a' en caso de no funcionar para el header.
const menuLength = menuItem.length;
for (let i = 0; i < menuLength; i++) {
	if (menuItem[i].href === currentLocation) {
		menuItem[i].className = "active"
	}
}

$('.color-selector').on('click', function () {
	$('.color-selector').removeClass('current');
	$(this).addClass('current');
});

$('#success').on('click', function (e) {
	e.preventDefault()
	var cantidad = $('#cantidad').val();
	var color = $('#color').val();
	var id = $(this).data('id');

	// Debug
	console.log('Agregando al carrito - ID:', id, 'Color:', color, 'Cantidad:', cantidad);

	// Limpiar mensaje previo
	$('#color-error-message').remove();

	// Verificar si hay múltiples colores disponibles
	var imagenesPorColorJson = $('#imagenesPorColor').val();
	if (imagenesPorColorJson) {
		try {
			var imagenesPorColor = JSON.parse(imagenesPorColorJson);
			var coloresDisponibles = [];

			// Contar cuántos colores válidos hay (excluyendo 'sin_color')
			for (var colorKey in imagenesPorColor) {
				if (colorKey !== 'sin_color') {
					coloresDisponibles.push(colorKey);
				}
			}

			// Si hay más de 1 color disponible y no se ha seleccionado ninguno (o se seleccionó 'sin_color')
			if (coloresDisponibles.length > 0 && (color === 'sin_color' || color === '')) {
				$('#success').after('<p id="color-error-message" class="text-danger mt-2 mb-0">Por favor, seleccione un color antes de agregar el producto al carrito.</p>');
				return false;
			}
		} catch (e) {
			console.error('Error al validar colores:', e);
		}
	}

	$.post('inc/set_cart.php', { id: id, color: color, cant: cantidad }, function(data) {
		console.log('Respuesta del carrito (botón #success):', data);
		if (data.success) {
			getCart();
			$('#message').html('<div class="alert alert-success alert-dismissible fade show" role="alert">Se agregó tu producto al carrito! <a href="mi-pedido" class="alert-link">Ver pedido</a><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		} else {
			$('#message').html('<div class="alert alert-warning alert-dismissible fade show" role="alert">' + data.msg + ' <a href="mi-pedido" class="alert-link">Ver carrito</a><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		}
	}, 'json').fail(function(xhr, status, error) {
		console.error('Error al agregar al carrito:', error);
		console.log('Respuesta:', xhr.responseText);
	});
})

function getCart() {
	$.post('inc/get_cart.php', function (data) {
		$('#cartContador').html(data);
	});
}
function limpiarDiv(div) {
	console.log('limpiar ' + div);
	$(div).empty();
	$(div).html();
}