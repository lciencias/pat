var id;
var tmp;
var valor;
$(document).ready(function(){
	$(".mas").hide();
	
	$(".buttonComentarios").click(function(){
		id  = $(this).attr('id');
		tmp = id.split('-');
		valor = $(this).val();
		if(parseInt(tmp[1])>0){
			if(parseInt(valor) === 1){
				$("#renglonActividad"+tmp[1]).show();
				$(this).removeClass("glyphicon-plus");
				$(this).html("");
				$(this).addClass("glyphicon-minus");
				$(this).val(0);
			}else{
				$("#renglonActividad"+tmp[1]).hide();
				$(this).removeClass("glyphicon-minus");
				$(this).html("");
				$(this).addClass("glyphicon-plus");
				$(this).val(1);
			}			
		}
	});
	
	$(".comentarios").click(function(){
		id  = $(this).attr('id');
		tmp = id.split('-');
		if(parseInt(tmp[1])>0){
			$("#comen").html($("#coment-"+tmp[1]).val());
			$("#myModalComentarios").modal('show');
		}
	});

	$(".adjuntos").click(function(){
		id  = $(this).attr('id');
		tmp = id.split('-');
		if(parseInt(tmp[1])>0){
			$("#adjun").html($("#adjuntos-"+tmp[1]).val());
			$("#myModalAdjuntos").modal('show');
		}		
	});	
	$(".cerrarAccion").click(function(){
		 $(".modal").modal('hide');
	});

});