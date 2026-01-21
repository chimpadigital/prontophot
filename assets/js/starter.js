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
		$.post('inc/set_cart.php', { id: id, color: color, cant: cantidad, cat: cat, precio: precio });
		getCart();
	});
	$('.cerrarSesion').click(function (e) {
		e.preventDefault();
		$.post('inc/salir.php', function (data) {
			window.location.reload();
		});

	});

	$('#nuevoRegistro').submit(function (e) {
		e.preventDefault();
		var pass1 = $('#reg-pass1').val();
		var pass2 = $('#reg-pass2').val();
		if (pass1 === pass2) {
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
		} else {
			alert('No coinciden las claves');
			$('#reg-pass1').focus();
		}
	});

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
		//if you set loop to false, you have to restore this next line
		//var current = el.item.index;

		//if you disable loop you have to comment this block
		var count = el.item.count - 1;
		var current = Math.round(el.item.index - (el.item.count / 2) - .5);

		if (current < 0) {
			current = count;
		}
		if (current > count) {
			current = 0;
		}

		//end block

		var owl2 = sync2.data('owl.carousel');

		if (owl2) {
			if (current > end) {
				owl2.to(current, 100, true);
			}
			if (current < start) {
				owl2.to(current - onscreen, 100, true);
			}
		}
	}

	function syncPosition2(el) {
		if (syncedSecondary) {
			var number = el.item.index;
			sync1.data('owl.carousel').to(number, 100, true);
		}
	}

	sync2.on("click", ".owl-item", function (e) {
		e.preventDefault();
		var number = $(this).index();
		sync1.data('owl.carousel').to(number, 300, true);
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
	$.post('inc/set_cart.php', { id: id, color: color, cant: cantidad });
	getCart();
	$('#message').html('<div class="alert alert-success alert-dismissible fade show" role="alert">Se agregó tu producto al carrito! <a href="mi-pedido" class="alert-link">Ver pedido</a><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
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