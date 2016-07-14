/**
 * 
 */
function subir_fichero(nombre) {
	$('[name=' + nombre + ']').trigger('click');
}
/*
 * 
 */
function add_modal() {
	if ($('#modal').length == 0) {
		$('body').append("<div id='modal' class='mimodal'></div>");
	}
}
/*
 * 
 */
function remove_modal() {
	if ($('#modal').length > 0) {
		$('#modal').remove();
	}
}
/*
 * 
 */
function add_dialogo(cerrar) {
	add_modal();
	if ($('#modal .dialog').length == 0) {
		if(cerrar == true){
			$('#modal').append("<div class='content_dialog'><div class='cerrar' onclick='remove_modal();'></div><div class='dialog'></div></div>");
		}else{
			$('#modal').append("<div class='content_dialog'><div class='dialog'></div></div>");
		}
	}else if(cerrar == true  && $('#modal .content_dialog .cerrar').length == 0){		
		$('#modal .content_dialog').append("<div class='cerrar' onclick='remove_modal();'></div>");
	}else if(cerrar == false && $('#modal .content_dialog .cerrar').length != 0){		
		$('#modal .content_dialog .cerrar').remove();
	}
}
/*
 * 
 */
function cargando_indefinido(texto) {
    console.log('cargando indefinido');
    add_dialogo();
	$('#modal .dialog').html('');
	$('#modal .dialog').append('<h3 style="width:100%;text-align:center;">' + texto + '</h3>');
	$('#modal .dialog').append('<div class="img_porc"><img src="/css/iconos/cargando.gif" width="80" /></div>');
}
/*
 * 
 */
function cargando_porcentaje(texto,porcentaje) {
	add_dialogo();	
	$('#modal .dialog').html('');
	$('#modal .dialog').append('<h3 style="width:100%;text-align:center;">' + texto + '</h3>');
	$('#modal .dialog').append('<div class="img_porc"><div class="n_porcentaje">'+porcentaje+'%</div><img src="/css/iconos/cargando.gif" width="130" /></div>');
}
/*
 * 
 */
function error_modal(msg) {
	add_dialogo(true);
	$('#modal .dialog').html('');
	$('#modal .dialog').append('<p style="width:100%;text-align:center;">' + msg + '</p>');
	$('#modal .dialog').append('<div style="width:100%;text-align:center;margin-top:40px;"><img src="/css/iconos/error.png" width="80" /></div>');
}
/*
 * 
 */
function comprueba_valor_bd(op, element) {
	$(element.parentNode.getElementsByClassName("estado")[0]).removeClass('ok');
	$(element.parentNode.getElementsByClassName("estado")[0]).removeClass('no');
	if (element.value != '') {
		$.ajax({
			type: 'POST', url: "/modulos/formularios/comprueba_bd.php", data: 'op=' + op + '&value=' + element.value, dataType: "JSON", success: function(datos) {
				if (datos[0] == 'ok') {
					$(element.parentNode.getElementsByClassName("estado")[0]).addClass('ok');
				} else {
					$(element.parentNode.getElementsByClassName("estado")[0]).addClass('no');
				}
			}
		});
	} else $(element.parentNode.getElementsByClassName("estado")[0]).addClass('no');
}
/*
 * 
 */
function add_cargando_segundo(msg) {
	if ($('#c_segundo_plano').length == 0) {
		$('body').append("<div id='c_segundo_plano' class='c_segundo_plano'><div><label></label></div></div>");
	}
		$('body #c_segundo_plano div label').html(msg);
	
}
/*
 * 
 */
function remove_cargando_segundo() {
	if ($('#c_segundo_plano').length > 0) {
		$('#c_segundo_plano').remove();
	}
}
