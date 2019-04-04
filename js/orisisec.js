 var url = "ajax/catalogos.php";
var urlFormatos = "ajax/tiposFormatos.php";
var urlAyudas   = "ajax/tiposAyudas.php";
var urlCatalogo = "ajax/insertaCatalogos.php";

var title="Secretaria de Cultura del D.F.";
var ideje;
var idPolitica;
var idarea;
var idprograma;
var idproyecto;
var idactividad;
var idobjetivog;
var userId;
var aplicacion;
var apli_com;
var id;
var valor;
var min_size;
var random;
var path;
var datos;
var exito;
var exitoLetras;
var exitoNumeros;
var exitoAlfanum;
var array_tmp;
var idValue;
var opc;
var chars     ="ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyzÁÉÍÓÚáéíóú,.&; .-";
var chars_Nums="ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyzÁÉÍÓÚáéíóú0123456789+-*/'@!\"#$%&/()=??'?. ";
var charsNum  ="0123456789,.";
var numsValor ="0123456789";
var charTels  ="0123456789 ()-";
var charMail  ="abcdefghijklmnopqrstuvwxyz!#_-.@";
var script;
var opcion;
var contador;
var idCatalogo;
var inputNombre;
var idunidadoperativa;
var ponderacion;
var cadenaCoordinacion;
var encoordinacion;
var idano;
var presupuesto;
var estimado;
var noActividades;
var arrayNoActividades;
var user_nombre;
var user_login;
var user_pass;
var user_email;
var rol;

$(document).ready(function(){

	$("#trespecifique").hide();
	if(parseInt($("#valueId").val())>0){
		$("#trespecifique").show();
	}	
	arrayNoActividades = new Array(100);
	for(i=0; i < arrayNoActividades.length; i++)
		arrayNoActividades[i] = 0;
	random=0;
	min_size=3;
	$("#procesando").hide();
	$("#t_procesando").hide();
	$("#MyTableActividades").tablesorter();
	$('#tabs').tab();
	$("#btn-6").hide();
	
	$("#ideje").change(function(){
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		ideje     = $("#ideje").val();
		if(parseInt(ideje) != 0)
			$.get(url,{ejeId:ideje,opcion:4},function(data){$("#idpolitica").html(data);})
			else
				$("#idpolitica").html('<option value="" selected="selected">Pol&iacute;tica P&uacute;blica</option>');
	});

	$("#idpolitica").change(function(){
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		idPolitica= $("#idpolitica").val();
		if(parseInt(idPolitica) != 0)

			$.get(url,{politicaId:idPolitica,opcion:5},function(data){$("#idarea").html(data);})
			else
				$("#idarea").html('<option value="" selected="selected">&Aacute;rea</option>');		
	})

	$("#idarea").change(function(){
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		idarea  = $("#idarea").val();
		if(parseInt(idarea) != 0)
			$.get(url,{areaId:idarea,opcion:1},function(data){$("#idprograma").html(data);})
			else
				$("#idprograma").html('<option value="" selected="selected">Programa</option>');
	});

	$("#idprograma").change(function(){
		idarea     = $("#idarea").val();
		idprograma = $("#idprograma").val()
		if( (parseInt(idarea) != 0) && (parseInt(idprograma) != 0))
		{
			$.get(url,{areaId:idarea,programaId:idprograma,opcion:2},function(data){$("#idobjetivog").html(data);})
		}
		else{
			$("#idobjetivog").html('<option value="" selected="selected">Objetivo General</option>');    
		}
	});

	$("#idobjetivog").change(function(){
		idarea     = $("#idarea").val();
		idprograma = $("#idprograma").val();
		idobjetivog= $("#idobjetivog").val();
		if( (parseInt(idarea) != 0) && (parseInt(idprograma) != 0)  && (parseInt(idobjetivog) != 0))
		{
			$.get(url,{areaId:idarea,programaId:idprograma,idobjetivog:idobjetivog,opcion:3},function(data){
				$("#idproyecto").html(data);
			})
		}
		else{
			$("#idproyecto").html('<option value="" selected="selected">Proyecto</option>');    
		}
	});

	$("#idunidadoperativa").change(function(){
		idunidadoperativa = $("#idunidadoperativa").val();
		$("#btn-6").hide();
		if(parseInt(idunidadoperativa) > 0){
			$("#btn-6").show();
			url = "ajax/catalogos.php";
			$.get(url,{idunidadoperativa:idunidadoperativa,opcion:7},function(data){
				$("#idresponsableunidado").html(data);
			})
		}
	})
	
	$(".validatexto").change(function(){
		return RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars);
	});

	$(".validanums").change(function(){
		return RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,charsNum);
	});	

	$(".validatextonumero").change(function(){
		return RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars_Nums);
	});	
	
	/*$(".validanumsM").change(function(){
		return RevisaCaracteres($(this).attr('id'),$(this).val(),1,chars_Nums);
	});	*/

	//guardar catalogo
	$(".savecatalogo").click(function(){
		valor=$(this).attr('id');
		idCatalogo=0;
		switch (String(valor)) {
		case "guardarEje":			
			url="ajax/ejepolitica.php";
			break;
		case "guardarPolitica":
			url="ajax/politicapublica.php";
			idCatalogo=$("#ideje").val();
			break;
		case "guardarArea":
			idCatalogo=$("#idpolitica").val();
			url="ajax/unidadresponsable.php";
			break;
		case "guardarPrograma":
			idCatalogo=$("#idarea").val();
			url="ajax/programas.php";
			break;
		case "guardarObjGen":
			idCatalogo=$("#idarea").val()+"|"+$("#idprograma").val();
			url="ajax/objetivosgenerales.php";
			break;
		case "guardarEventoArt":
			url="ajax/eventosartisticos.php";
			break;
		case "guardarHospital":
			url="ajax/hospitales.php";
			break;
		case "guardarRecinto":
			url="ajax/recintos.php";
			break;
		case "guardarMedida":
			url="ajax/medidas.php";
			break;
		case "guardarProyecto":
			idCatalogo=$("#idarea").val()+"|"+$("#idprograma").val()+"|"+$("#idobjetivog").val();
			url="ajax/proyectos.php";
			break;
		case "guardarUnidadOpe":
			idCatalogo=$("#idarea").val()+"|"+$("#idprograma").val()+"|"+$("#idobjetivog").val()+"|"+$("#idproyecto").val();
			url="ajax/unidadoperativa.php";
			break;
		}
		exitoLetras  = true;
		exitoNumeros = true;
		$(".validatexto").each(function(){
			exitoLetras=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars);
		});

		//validamos numeros
		$(".validanums").each(function(){
			exitoNumeros=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,charsNum);
		});

		if ( (exitoLetras) && (exitoNumeros) )
		{
			idValue=$("#valueId").val();
			opc=7;
			if (parseInt(idValue)== 0) {
				opc=2;
			}
			bootbox.confirm("<br><br><b>&iquest;Desea grabar los datos?</b>", function(result) {
				if(result)
				{
					$("#procesando").show();
					$("#t_procesando").show();
					random= Math.round(Math.random() * 1000);
					$("#opc").val(1);
					$("#random").val(random);
					$.post(url,{random:random,idSec:idValue,nombre:$("#nomCatalogo").val(),eje_id:idCatalogo,active:$("#edoId").val(),opc:opc},function(bufferAct){
						if(parseInt(bufferAct) > 0){                            
							$("#resultado").css({ color: "#006600", background: "#ffffff" });
							$("#resultado").html("Se ha almacenado el folio: "+bufferAct+".");
							setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},1200);   
						}
						else
						{
							$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
							$("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							return false;
						}
					})
				}
			}); 
		}
	});

	$(".actualiza").click(function(){
		id=$(this).attr('id');
		array_tmp=id.split('-');
		if ( (parseInt(array_tmp[1])>0) && (parseInt(array_tmp[2])>0)) {
			location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=5&id="+array_tmp[2];
		}
	});

	$(".consulta").click(function(){
		id=$(this).attr('id');
		array_tmp=id.split('-');
		if ( (parseInt(array_tmp[1])>0) && (parseInt(array_tmp[2])>0)) {
			location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=6&id="+array_tmp[2];
		}
	});

	$(".deshacer").click(function(){
		id=$(this).attr('id');
		array_tmp=id.split('-');
		if ( (parseInt(array_tmp[1])>0) && (parseInt(array_tmp[2])>0)) {
			url="ajax/eliminar.php";
			bootbox.confirm("<br><br><b>&iquest;Desea restaurar el registro?</b>", function(result) {
				if(result)
				{
					$("#procesando").show();
					$("#t_procesando").show();
					random= Math.round(Math.random() * 1000);
					$("#opc").val(3);
					$("#random").val(random);
					$.post(url,{random:random,tableId:array_tmp[1],id:array_tmp[2],opc:4},function(bufferAct){
						if(parseInt(bufferAct) > 0){                            
							$("#resultado").css({ color: "#006600", background: "#ffffff" });
							$("#resultado").html("Se ha restaurado el registro: "+bufferAct+".");
							setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},1200);   
						}
						else
						{
							$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
							$("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							return false;
						}
					})
				}
			}); 
		}				
	});

	$(".ordenes").change(function(){
		id=$(this).attr('id');
		valor=$(this).val();
		array_tmp=id.split('-');
		if ( (parseInt(array_tmp[1])>0) && (parseInt(array_tmp[2])>0)) {
			url="ajax/eliminar.php";
			bootbox.confirm("<br>&iquest;Desea cambiar el orden el registro?</b>", function(result) {
				if(result)
				{
					$("#procesando").show();
					$("#t_procesando").show();
					random= Math.round(Math.random() * 1000);
					$("#opc").val(8);
					$("#random").val(random);
					$.post(url,{random:random,tableId:array_tmp[1],id:parseInt(array_tmp[2]),orden:valor,opc:8},function(bufferAct){
						if(parseInt(bufferAct) > 0){                            
							$("#resultado").css({ color: "#006600", background: "#ffffff" });
							$("#resultado").html("Se ha modificado el orden del registro.");
							$("#procesando").hide();
							$("#t_procesando").hide();
							//setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},1200);   
						}
						else
						{
							$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
							$("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							return false;
						}
					})
				}
			}); 
		}		
	});


	$(".elimina").click(function(){
		id=$(this).attr('id');
		array_tmp=id.split('-');
		if ( (parseInt(array_tmp[1])>0) && (parseInt(array_tmp[2])>0)) {
			url="ajax/eliminar.php";
			bootbox.confirm("<br>&iquest;Desea eliminar el registro?</b>", function(result) {
				if(result)
				{
					$("#procesando").show();
					$("#t_procesando").show();
					random= Math.round(Math.random() * 1000);
					$("#opc").val(3);
					$("#random").val(random);
					$.post(url,{random:random,tableId:array_tmp[1],id:array_tmp[2],opc:3},function(bufferAct){
						if(parseInt(bufferAct) > 0){                            
							$("#resultado").css({ color: "#006600", background: "#ffffff" });
							$("#resultado").html("Se ha eliminado el registro: "+bufferAct+".");
							setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},1200);   
						}
						else
						{
							$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
							$("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							return false;
						}
					})
				}
			}); 
		}		
	});

	$(".pasoslink").click(function(){
		id=$(this).attr('id');
		if(parseInt(id)>0)
		{
			$("#procesando").show();
			$("#t_procesando").show();
			random= Math.round(Math.random() * 1000);
			$.post(urlFormatos,{random:random,id:id},function(buffer){
				$("#contenidoFormato").html(buffer)
			})
		}
	});
	
	$("#btnNvoProyecto").click(function(){
		$("#error").css({ color: "#ffff99", background: "#ffff99" });
		userId    = $("#userId").val();
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		idarea    = $("#idarea").val();
		idprograma= $("#idprograma").val();
		if( (parseInt(idarea)>0) && (parseInt(idprograma)>0) ){
			location.href="aplicacion.php?aplicacion="+aplicacion+"&apli_com="+apli_com+"&idarea="+idarea+"&idprograma="+idprograma+"&opc=3";
		}else{
			$("#error").css({ color: "#610B0B", background: "#ffff99" });
			$("#error").html("Favor de seleccionar el area y programa");
		}
		
	});
	
	$("#btnBuscarProyecto").click(function(){
		$("#error").css({ color: "#ffff99", background: "#ffff99" });
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		idarea    = $("#idarea").val();
		idprograma= $("#idprograma").val();
		idano= $("#idano").val();
		if( (parseInt(idarea)>0) && (parseInt(idprograma)>0) ){
			location.href="aplicacion.php?aplicacion="+aplicacion+"&apli_com="+apli_com+"&idarea="+idarea+"&idprograma="+idprograma+"&idano="+idano+"&opc=8";
		}else{
			$("#error").css({ color: "#610B0B", background: "#ffff99" });
			$("#error").html("Favor de seleccionar el area y programa");
		}
	});
	
	$("#btnConProyecto").click(function(){
		$("#error").css({ color: "#ffff99", background: "#ffff99" });
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		idarea    = $("#idarea").val();
		idprograma= $("#idprograma").val();
		if( (parseInt(idarea)>0) && (parseInt(idprograma)>0) ){
			location.href="aplicacion.php?aplicacion="+aplicacion+"&apli_com="+apli_com+"&idarea="+idarea+"&idprograma="+idprograma+"&opc=8";
		}else{
			$("#error").css({ color: "#610B0B", background: "#ffff99" });
			$("#error").html("Favor de seleccionar el area y programa");
		}
	});
	
	
	$(".help").click(function(){
		contador=0;
		valor=$(this).attr('id');
		if(String(valor) != ""){
			random= Math.round(Math.random() * 1000);
			$.get(urlAyudas,{random:random,id:valor},function(buffer){
				bootbox.dialog({
					message: buffer,
					title: title,
					closeButton: true,
					 buttons: {
						 success: {
							 label: "Cerrar ventana",
							 className: "tdcenter btn-default",
							 callback: function() {
								 contador++;
						 	}
						 }
					 }
				});
			})
		}
	});
	
	$(".cerrarAccion").click(function(){
		 $(".modal").modal('hide');
	});
	
	$("#pSsaveProyecto1").click(function(){
		min_size= 3;
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		idarea    = $("#idarea").val();
		idprograma= $("#idprograma").val();
		opcion    = $("#opcModulo1").val();
		inputNombre=$("#inputNombre1").val();
		exitoLetras  = true;
		exitoLetras=RevisaCaracteres("inputNombre1",inputNombre,min_size,chars);
		if( (parseInt(idarea) > 0 ) && (parseInt(idprograma) > 0) && (exitoLetras) ){
			bootbox.confirm("<br><br><b>&iquest;Desea grabar los datos?</b>", function(result) {
				if(result)
				{
					random= Math.round(Math.random() * 1000);
					$("#random").val(random);
					$.post(urlCatalogo,{random:random,idarea:idarea,idprograma:idprograma,inputNombre:inputNombre,opcion:opcion},function(bufferAct){
						if(parseInt(bufferAct) > 0){                            
							$("#resultadoModal1").css({ color: "#006600", background: "#ffff99" });
							$("#resultadoModal1").html("Se ha almacenado el proyecto: "+bufferAct+".");
							$.get(url,{areaId:idarea,programaId:idprograma,opcion:3},function(data){
								$("#idproyecto").html(data);
								$("#inputNombre1").val("");
								setTimeout(function(){$(".modal").modal('hide');},1000);  
							});
						}
						else
						{
							$("#resultadoModal1").css({ color: "#ff0000", background: "#ffff99" });
							$("#resultadoModal1").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							return false;
						}
					})
				}
			});
		}
	});
	
	$("#pSsaveProyecto2").click(function(){
		min_size= 3;
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		idarea    = $("#idarea").val();
		idprograma= $("#idprograma").val();
		opcion    = $("#opcModulo2").val();
		inputNombre=$("#inputNombre2").val();
		exitoLetras  = true;
		exitoLetras=RevisaCaracteres("inputNombre2",inputNombre,min_size,chars);
		if( (parseInt(idarea) > 0 ) && (parseInt(idprograma) > 0) && (exitoLetras) ){
			bootbox.confirm("<br><br><b>&iquest;Desea grabar los datos?</b>", function(result) {
				if(result)
				{
					random= Math.round(Math.random() * 1000);
					$("#random").val(random);
					$.post(urlCatalogo,{random:random,idarea:idarea,idprograma:idprograma,inputNombre:inputNombre,opcion:opcion},function(bufferAct){
						if(parseInt(bufferAct) > 0){                            
							$("#resultadoModal2").css({ color: "#006600", background: "#ffff99" });
							$("#resultadoModal2").html("Se ha almacenado la Unidad Operativa: "+bufferAct+".");
							$.get(url,{areaId:idarea,programaId:idprograma,opcion:6},function(data){
								$("#idunidadoperativa").html(data);
								$("#inputNombre2").val("");
								setTimeout(function(){$(".modal").modal('hide');},1000);  
							});
						}
						else
						{
							$("#resultadoModal2").css({ color: "#ff0000", background: "#ffff99" });
							$("#resultadoModal2").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							return false;
						}
					})
				}
			});
		}
	});
	
	$("#pSsaveProyecto3").click(function(){
		min_size= 3;
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		idarea    = $("#idarea").val();
		idprograma= $("#idprograma").val();
		opcion    = $("#opcModulo3").val();
		inputNombre=$("#inputNombre3").val();
		idunidadoperativa =$("#idunidadoperativa").val();
		exitoLetras  = true;
		exitoLetras=RevisaCaracteres("inputNombre3",inputNombre,min_size,chars);
		if( (parseInt(idarea) > 0 ) && (parseInt(idprograma) > 0) && (exitoLetras) ){
			bootbox.confirm("<br><br><b>&iquest;Desea grabar los datos?</b>", function(result) {
				if(result)
				{
					random= Math.round(Math.random() * 1000);
					$("#random").val(random);
					$.post(urlCatalogo,{random:random,idarea:idarea,idprograma:idprograma,idunidadoperativa:idunidadoperativa,inputNombre:inputNombre,opcion:opcion},function(bufferAct){
						if(parseInt(bufferAct) > 0){                            
							$("#resultadoModal3").css({ color: "#006600", background: "#ffffff" });
							$("#resultadoModal3").html("Se ha almacenado el responsable: "+bufferAct+".");
							if($("#idunidadoperativa").val()>0){
								$.get(url,{idunidadoperativa:$("#idunidadoperativa").val(),opcion:7},function(data){
									$("#idresponsableunidado").html(data);
									$("#inputNombre3").val("");
									setTimeout(function(){$(".modal").modal('hide');},1000);  
								});
							}
						}
						else
						{
							$("#resultadoModal3").css({ color: "#ff0000", background: "#ffffff" });
							$("#resultadoModal3").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							return false;
						}
					})
				}
			});
		}
	});

	$("#pSsaveProyecto4").click(function(){
		min_size= 3;
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		idarea    = $("#idarea").val();
		idprograma= $("#idprograma").val();
		opcion    = $("#opcModulo4").val();
		inputNombre=$("#inputNombre4").val();
		exitoLetras  = true;
		exitoLetras=RevisaCaracteres("inputNombre1",inputNombre,min_size,chars);
		if( (parseInt(idarea) > 0 ) && (parseInt(idprograma) > 0) && (exitoLetras) ){
			bootbox.confirm("<br><br><b>&iquest;Desea grabar los datos?</b>", function(result) {
				if(result)
				{
					random= Math.round(Math.random() * 1000);
					$("#random").val(random);
					$.post(urlCatalogo,{random:random,idarea:idarea,idprograma:idprograma,inputNombre:inputNombre,opcion:opcion},function(bufferAct){
						if(parseInt(bufferAct) > 0){                            
							$("#resultadoModal4").css({ color: "#006600", background: "#ffffff" });
							$("#resultadoModal4").html("Se ha almacenado el proyecto: "+bufferAct+".");
							$.get(url,{areaId:idarea,programaId:idprograma,opcion:9},function(data){
								$("#idopcion").html(data);
								$("#inputNombre4").val("");
								setTimeout(function(){$(".modal").modal('hide');},1000);  
							});
						}
						else
						{
							$("#resultadoModal4").css({ color: "#ff0000", background: "#ffffff" });
							$("#resultadoModal4").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							return false;
						}
					})
				}
			});
		}
	});
	
	/***** INSERTAR PROYECTO *****/	
	$("#saveProyecto").click(function(){
		ponderacion=0;
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		idarea    = $("#idarea").val();
		idprograma= $("#idprograma").val();
		valor=$(this).attr('id');
		exitoLetras  = true;
		exitoNumeros = true;
		exitoAlfanum = true;
		$("#resultado").css({ color: "#000000", background: "#ffffFF" });
		$("#idarea").css({ color: "#000000", background: "#ffffFF" });
		$("#idprograma").css({ color: "#000000", background: "#ffffFF" });
		$("#idunidadoperativa").css({ color: "#000000", background: "#FFFFFF" });
		$("#idresponsableunidado").css({ color: "#000000", background: "#FFFFFF" });
		if(parseInt($("#idarea").val()) <= 0){
			$("#idarea").css({ color: "#610B0B", background: "#ffff99" });
			$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
			$("#resultado").html('Seleccione el area');
			return false;
		}
		if(parseInt($("#idprograma").val()) <= 0){
			$("#idprograma").css({ color: "#610B0B", background: "#ffff99" });
			$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
			$("#resultado").html('Seleccione el programa');
			return false;
		}
		
		if($("#ponderacion5").is(':checked')){
			ponderacion=5;
		}
		if($("#ponderacion4").is(':checked')){
			ponderacion=4;
		}
		if($("#ponderacion3").is(':checked')){
			ponderacion=3;
		}
		if($("#ponderacion2").is(':checked')){
			ponderacion=2;
		}
		if($("#ponderacion1").is(':checked')){
			ponderacion=1;
		}		
		if(parseInt(ponderacion)<=0){
			$("#ponderacion1").css({ color: "#610B0B", background: "#ffff99" });
			$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
			$("#resultado").html('Seleccione la ponderacion');
			return false;
		}
		
		//validamos letras
		$(".validatexto").each(function(){
			exitoLetras=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars);
		});

		//validamos numeros
		$(".validanums").each(function(){
			exitoNumeros=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,charsNum);
		});
		//validamos alfanumericos
		$(".validatextonumero").each(function(){
			exitoAlfanum=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars_Nums);
		});
		
		if(parseInt($("#idunidadoperativa").val()) <= 0){
			$("#idunidadoperativa").css({ color: "#610B0B", background: "#ffff99" });
			$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
			$("#resultado").html('Seleccione la unidad operativa');
			return false;
		}
		if(parseInt($("#idresponsableunidado").val()) <= 0){
			$("#idresponsableunidado").css({ color: "#610B0B", background: "#ffff99" });
			$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
			$("#resultado").html('Seleccione el responsable');
			return false;
		}
		
		if ( (exitoLetras) && (exitoNumeros) && (exitoAlfanum) && parseInt(ponderacion) > 0)
		{
			encoordinacion="";
			$(".coordinacion").each(function(){
				if($(this).is(':checked')){
					encoordinacion+=$(this).val()+"|";
				}
			});
			bootbox.confirm("<br><br><b>&iquest;Desea grabar los datos del proyecto?</b>", function(result) {
				if(result)
				{
					$("#procesando").show();
					$("#t_procesando").show();
					random= Math.round(Math.random() * 1000);
					$.post("ajax/salvarProyecto.php",{random:random,idarea:idarea,idprograma:idprograma,
						inputNombre:$("#inputNombre").val(),
						idproyecto:$("#valueId").val(),
						idunidadoperativa:$("#idunidadoperativa").val(),
						idresponsableunidado:$("#idresponsableunidado").val(),ponderacion:ponderacion,
						descripcion:$("#descripcion").val(),
						resultados:$("#resultados").val(),
						en_coordinacion:encoordinacion,
						especifique:$("#especifique").val(),
						participacion:$("#idopcion").val(),
						presupuesto:$("#presupuesto_1").val(),
						estimado:$("#estimado_1").val(),
						opc:1},function(bufferAct){
						if(parseInt(bufferAct) > 0){                            
							$("#resultado").css({ color: "#006600", background: "#ffffff" });
							$("#resultado").html("Se ha almacenado el registro.");
							setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},500);   
						}
						else
						{
							if(parseInt(bufferAct)== -1){
								$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
								$("#resultado").html('El proyecto ya fue registrado');
							}else{
								$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
								$("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							}
							return false;
						}
					});
				}
			}); 
		}
	});
	
	/***** ELIMINA ACTIVIDADES DEL PROYECTO *****/
	$(".deleteActividadesProyecto").click(function(){
		id = $(this).attr('id');
		array_tmp=id.split('-');
		if( (String(id) != "")  && (parseInt(array_tmp[0]) > 0) ){
			
			userId    = $("#userId").val();
			aplicacion= $("#aplicacion").val();
			apli_com  = $("#apli_com").val();
			bootbox.confirm("<br><br><b>&iquest;Desea eliminar la Actividad del Proyecto?</b>", function(result) {
				if(result)
				{
					$("#procesando").show();
					$("#t_procesando").show();
					random= Math.round(Math.random() * 1000);
					$.post("ajax/salvarProyecto.php",{random:random,folio:id,opc:6},function(bufferAct){
						if(parseInt(bufferAct) > 0){                            
							$("#resultado").css({ color: "#006600", background: "#ffffff" });
							$("#resultado").html("Se ha eliminado el registro.");
							setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=3&folio="+array_tmp[1]+"-"+array_tmp[2]},500);   
						}
						else
						{
							$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
							$("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
						}
					});					
				}
			})
		}
		return false;
	});
	
	/***** ELIMINA   PROYECTO *****/
	$(".deleteProyecto").click(function(){
		id = $(this).attr('id');
		if(String(id) != ""){
			userId    = $("#userId").val();
			aplicacion= $("#aplicacion").val();
			apli_com  = $("#apli_com").val();
			bootbox.confirm("<br><br><b>&iquest;Desea eliminar el proyecto?</b>", function(result) {
				if(result)
				{
					$("#procesando").show();
					$("#t_procesando").show();
					random= Math.round(Math.random() * 1000);
					$.post("ajax/salvarProyecto.php",{random:random,folio:id,opc:5},function(bufferAct){
						if(parseInt(bufferAct) > 0){                            
							$("#resultado").css({ color: "#006600", background: "#ffffff" });
							$("#resultado").html("Se ha eliminado el registro.");
							setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},500);   
						}
						else
						{
							$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
							$("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
						}
					});					
				}
			})
		}
		return false;
	});
	
	/***** ACTUALIZA PROYECTO *****/
	$("#updateProyecto").click(function(){
		ponderacion=0;
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		idarea    = $("#idarea").val();
		idprograma= $("#idprograma").val();
		valor      =$("#valueId").val();
		exitoLetras  = true;
		exitoNumeros = true;
		exitoAlfanum = true;
		$("#resultado").css({ color: "#000000", background: "#ffffFF" });
		$("#idarea").css({ color: "#000000", background: "#ffffFF" });
		$("#idprograma").css({ color: "#000000", background: "#ffffFF" });
		$("#idunidadoperativa").css({ color: "#000000", background: "#FFFFFF" });
		$("#idresponsableunidado").css({ color: "#000000", background: "#FFFFFF" });
		if(parseInt($("#idarea").val()) <= 0){
			$("#idarea").css({ color: "#610B0B", background: "#ffff99" });
			$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
			$("#resultado").html('Seleccione el area');
			return false;
		}
		if(parseInt($("#idprograma").val()) <= 0){
			$("#idprograma").css({ color: "#610B0B", background: "#ffff99" });
			$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
			$("#resultado").html('Seleccione el programa');
			return false;
		}
		
		if($("#ponderacion5").is(':checked')){
			ponderacion=5;
		}
		if($("#ponderacion4").is(':checked')){
			ponderacion=4;
		}
		if($("#ponderacion3").is(':checked')){
			ponderacion=3;
		}
		if($("#ponderacion2").is(':checked')){
			ponderacion=2;
		}
		if($("#ponderacion1").is(':checked')){
			ponderacion=1;
		}		
		if(parseInt(ponderacion)<=0){
			$("#ponderacion1").css({ color: "#610B0B", background: "#ffff99" });
			$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
			$("#resultado").html('Seleccione la ponderacion');
			return false;
		}
		
		//validamos letras
		$(".validatexto").each(function(){
			exitoLetras=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars);
		});

		//validamos numeros
		$(".validanums").each(function(){
			exitoNumeros=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,charsNum);
		});
		//validamos alfanumericos
		$(".validatextonumero").each(function(){
			exitoAlfanum=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars_Nums);
		});
		
		if(parseInt($("#idunidadoperativa").val()) <= 0){
			$("#idunidadoperativa").css({ color: "#610B0B", background: "#ffff99" });
			$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
			$("#resultado").html('Seleccione la unidad operativa');
			return false;
		}
		if(parseInt($("#idresponsableunidado").val()) <= 0){
			$("#idresponsableunidado").css({ color: "#610B0B", background: "#ffff99" });
			$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
			$("#resultado").html('Seleccione el responsable');
			return false;
		}
		
		if ( (exitoLetras) && (exitoNumeros) && (exitoAlfanum) && parseInt(ponderacion) > 0 && parseInt(valor) > 0)
		{
			encoordinacion="";
			$(".coordinacion").each(function(){
				if($(this).is(':checked')){
					encoordinacion+=$(this).val()+"|";
				}
			});
			bootbox.confirm("<br><br><b>&iquest;Desea actualizar los datos del proyecto?</b>", function(result) {
				if(result)
				{
					$("#procesando").show();
					$("#t_procesando").show();
					random= Math.round(Math.random() * 1000);
					$.post("ajax/salvarProyecto.php",{random:random,valor:valor,idarea:idarea,idprograma:idprograma,
						inputNombre:$("#inputNombre").val(),
						idproyecto:$("#idproyecto").val(),
						idunidadoperativa:$("#idunidadoperativa").val(),
						idresponsableunidado:$("#idresponsableunidado").val(),ponderacion:ponderacion,
						descripcion:$("#descripcion").val(),
						resultados:$("#resultados").val(),
						en_coordinacion:encoordinacion,
						especifique:$("#especifique").val(),
						participacion:$("#idopcion").val(),
						presupuesto:$("#presupuesto_1").val(),
						estimado:$("#estimado_1").val(),
						opc:3},function(bufferAct){
						if(parseInt(bufferAct) > 0){                            
							$("#resultado").css({ color: "#006600", background: "#ffffff" });
							$("#resultado").html("Se ha actualizado el registro.");
							setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},500);   
						}
						else
						{
							if(parseInt(bufferAct)== -1){
								$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
								$("#resultado").html('El proyecto ya fue registrado');
							}else{
								$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
								$("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							}
							return false;
						}
					});
				}
			}); 
		}		
	});
	
	/***** ACTUALIZA ACTIVIDAD ****/
	$("#actualizaActividad").click(function(){
		ponderacion=0;
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		folio     = $("#folio").val();
		random    = $("#random").val();
		valor      =$("#valueId").val();
		exitoAlfanum = true;
		$("#idMedida").css({ color: "#000000", background: "#FFFFFF" });
		$("#idTipoActividad").css({ color: "#000000", background: "#FFFFFF" });

		if(parseInt($("#idMedida").val()) <= 0){
			$("#idMedida").css({ color: "#610B0B", background: "#ffff99" });
			return false;
		}
		
		if($("#Aponderacion5").is(':checked')){
			ponderacion=5;
		}
		if($("#Aponderacion4").is(':checked')){
			ponderacion=4;
		}
		if($("#Aponderacion3").is(':checked')){
			ponderacion=3;
		}
		if($("#Aponderacion2").is(':checked')){
			ponderacion=2;
		}
		if($("#Aponderacion1").is(':checked')){
			ponderacion=1;
		}		
		if(parseInt(ponderacion)<=0){
			$("#Aponderacion1").css({ color: "#610B0B", background: "#ffff99" });
			return false;
		}
		
		if(parseInt($("#idTipoActividad").val()) <= 0){
			$("#idTipoActividad").css({ color: "#610B0B", background: "#ffff99" });
			return false;
		}		
		//validamos alfanumericos
		$(".validatextonumero").each(function(){
			exitoAlfanum=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars_Nums);
		});
		
		if ( (exitoAlfanum) && (parseInt(ponderacion) > 0) && (parseInt(folio)> 0) && (parseInt(random)>0) && (parseInt(valor) > 0) )
		{
			bootbox.confirm("<br><br><b>&iquest;Desea actualizar la Actividad?</b>", function(result) {
				if(result)
				{
					$("#procesando").show();
					$("#t_procesando").show();
					random= Math.round(Math.random() * 1000);
					$.post("ajax/salvarProyecto.php",{random:random,valor:valor,idProyecto:folio,userId:userId,
						idMedida:$("#idMedida").val(),
						idTipoActividad:$("#idTipoActividad").val(),
						random:random,
						ponderacion:ponderacion,
						actividad:$("#actividad").val(),
						opc:4},function(bufferAct){
						if(parseInt(bufferAct) > 0){
							$("#resultado").css({ color: "#006600", background: "#ffffff" });
							$("#resultado").html("Se ha actualizado la actividad.");
							setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=3&folio="+folio},1000);   
						}
						else
						{
							if(parseInt(bufferAct)== -1){
								$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
								$("#resultado").html('La actividad ya fue registrada.');
							}else{
								$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
								$("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							}
							return false;
						}
					});
				}
			}); 
		}
		
		
	});

	
	/***** INSERTAR ACTIVIDAD *****/
	$("#guardaActividad").click(function(){
		ponderacion=0;
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		folio     = $("#folio").val();
		random    = $("#random").val();
		url="ajax/salvarProyecto.php";
		exitoAlfanum = true;
		$("#idMedida").css({ color: "#000000", background: "#FFFFFF" });
		$("#idTipoActividad").css({ color: "#000000", background: "#FFFFFF" });

		if(parseInt($("#idMedida").val()) <= 0){
			$("#idMedida").css({ color: "#610B0B", background: "#ffff99" });
			return false;
		}
		
		if($("#Aponderacion5").is(':checked')){
			ponderacion=5;
		}
		if($("#Aponderacion4").is(':checked')){
			ponderacion=4;
		}
		if($("#Aponderacion3").is(':checked')){
			ponderacion=3;
		}
		if($("#Aponderacion2").is(':checked')){
			ponderacion=2;
		}
		if($("#Aponderacion1").is(':checked')){
			ponderacion=1;
		}		
		if(parseInt(ponderacion)<=0){
			$("#Aponderacion1").css({ color: "#610B0B", background: "#ffff99" });
			return false;
		}
		
		if(parseInt($("#idTipoActividad").val()) <= 0){
			$("#idTipoActividad").css({ color: "#610B0B", background: "#ffff99" });
			return false;
		}		
		//validamos alfanumericos
		$(".validatextonumero").each(function(){
			exitoAlfanum=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars_Nums);
		});
		
		if ( (exitoAlfanum) && (parseInt(ponderacion) > 0) && (parseInt(folio)> 0) && (parseInt(random)>0) )
		{
			bootbox.confirm("<br><br><b>&iquest;Desea grabar los datos?</b>", function(result) {
				if(result)
				{
					$("#procesando").show();
					$("#t_procesando").show();
					random= Math.round(Math.random() * 1000);
					$.post(url,{random:random,idProyecto:folio,userId:userId,
						idMedida:$("#idMedida").val(),
						idTipoActividad:$("#idTipoActividad").val(),
						random:random,
						ponderacion:ponderacion,
						actividad:$("#actividad").val(),
						opc:2},function(bufferAct){
						if(parseInt(bufferAct) > 0){
							$("#resultado").css({ color: "#006600", background: "#ffffff" });
							$("#resultado").html("Se ha almacenado el registro.");
							setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=3&folio="+folio},1000);   
						}
						else
						{
							if(parseInt(bufferAct)== -1){
								$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
								$("#resultado").html('La actividad ya fue registrada.');
							}else{
								$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
								$("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							}
							return false;
						}
					});
				}
			}); 
		}
	});

	/*$(".coordinacion").click(function(){
		encoordinacion="";
		$(".coordinacion").each(function(){
			if($(this).is(':checked')){
				encoordinacion+=$(this).val()+"|";
			}
		});
	});*/
	
	$("#coordinacion8").click(function(){
			if($("#coordinacion8").is(':checked')){
				$("#especifique").val("");	
				$("#trespecifique").show();
			}
			else{
				$("#trespecifique").hide();
				$("#especifique").val('NOAPLICA');
			}
	});
	
	$("#btnBuscarProyecto1").click(function(){
		$("#resProyecto").css({ color: "#ffffffffff99", background: "#FFFFFF" });
		$("#resProyecto").html("");
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		idarea    = $("#idarea").val();
		idprograma= $("#idprograma").val();
		idano     = $("#idano").val();
		if( (parseInt(idarea)>0) && (parseInt(idprograma)>0) ){
			random= Math.round(Math.random() * 1000);
			$("#resProyecto").css({ color: "#000000", background: "#fffff" });
			$.post(url,{random:random,areaId:idarea,programaId:idprograma,idano:idano,opcion:8},function(bufferAct){
				$("#resProyecto").html(bufferAct);
			});
			
		}else{
			$("#resProyecto").css({ color: "#ff0000", background: "#ffffff" });
			$("#resProyecto").html("Favor de seleccionar el area y programa");
		}
	})
	
	 $(".todos").click(function(){
        $('input:checkbox').each(function(i, item){
            $(item).attr('checked', true);
        });
    });

    $(".ningunos").click(function(){
        $('input:checkbox').each(function(i, item){
            $(item).attr('checked', false);
        });
    });
    
    $("#cambiaFase2").click(function(){
    	alert("Cambia de fase los proyectos y se envia correo, " +
    		  "con los proyectos que cambiaron de fase. " +
    		  "En la base de datos se hace el cambio y se guarda la fecha del mismo");
    });

    $(".btneliminaAdjunto" ).on( "click", function(e) {
    	id=$(this).attr('id');
		array_tmp=id.split('-');
		if ( (parseInt(array_tmp[1])>0) && (parseInt(array_tmp[2])>0) && (parseInt(array_tmp[3])>0)) {
			random= Math.round(Math.random() * 1000);
			$.post("ajax/doajaxfileupload.php",{random:random,idAdjunto:array_tmp[1],proyectoId:array_tmp[2],actividadId:array_tmp[3],opc:3},function(buffer){
				$("#downloadFiles").html(buffer);
			});
		}
		return false;
    });
    
    $(".validanumsM").change(function(){
    	RevisaCaracteresNumeros($(this).attr('id'),$(this).val(),1,numsValor);
    		id = $(this).attr('id');
    		array_tmp=id.split('-');
    		contador=array_tmp[1];    
    		if(String($(this).val())==""){
    			$(this).val(parseInt('0'));
    			valor = 0;
    		}else{
    			valor = parseInt($(this).val());
    		}
    		arrayNoActividades[contador] = arrayNoActividades[contador] + valor;
    		$("#total"+contador).html(arrayNoActividades[contador]);
    		random = random + valor;
    		$("#totales").html(random);
    });
    
    $("#guardaMeta").click(function(){
    	valor="";
    	inputNombre = "";
    	proyectoId=$("#folio").val();
    	$('input:text').each(function(i, item){
    		inputNombre=$(item).attr('id');
    		if(inputNombre.substring(0, 1)== 'p'){
    			valor += inputNombre+"-"+$(item).val()+"|";
    		}
        });
    	if( (parseInt(proyectoId) > 0) && (valor!= "")){
        	bootbox.confirm("<br><br><b>&iquest;Desea grabar las metas?</b>", function(result) {
    			if(result)
    			{
    				$("#procesando").show();
    				$("#t_procesando").show();
    		    	random= Math.round(Math.random() * 1000);
    				$.post("ajax/salvarProyecto.php",{random:random,proyectoId:proyectoId,valor:valor,opc:7},function(buffer){
    					if(parseInt(buffer) > 0){
    						$("#resultado").css({ color: "#006600", background: "#ffffff" });
    						$("#resultado").html("Se han almacenado las metas.");
    						setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},1000);   
    					}
    					else
    					{
    						$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
    						$("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
    						return false;
    					}
    				});
    			}		
        	});
    	}
    });
    
    /********   Usuarios ******/
    $("#guardarUsuario").click(function(){
    	exito=0;
    	cadenaCoordinacion="";
    	contador = 0;
    	$("#resultado").css({ color: "#ffffff", background: "#FFFFFF" });
		$("#resultado").html("");
		userId    = $("#userId").val();
		aplicacion= $("#aplicacion").val();
		apli_com  = $("#apli_com").val();
		valueId   = $("#valueId").val();
		idarea    = $("#idarea").val();
		idprograma= $("#idprograma").val();
    	$(".validatexto").each(function(){
			exitoLetras=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars);
		});

		//validamos numeros
		$(".validanums").each(function(){
			exitoNumeros=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,charsNum);
		});
		//validamos alfanumericos
		$(".validatextonumero").each(function(){
			exitoAlfanum=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars_Nums);
		});    	
		$("#rol").css({ color: "#000000", background: "#FFFFFF" });
		if(parseInt($("#rol").val())<=0){
			$("#rol").css({ color: "#610B0B", background: "#ffff99" });
			$("#resultado").css({ color: "#ff0000", background: "#FFFFFF" });
			$("#resultado").html("Por favor seleccione un rol");
			return false;
		}
		contador=0;
	    $('input:checkbox').each(function(i, item){
	        if($(this).is(':checked')){
	        	cadenaCoordinacion+=$(this).val()+"*";
	        	contador++;
	        }  
	    });
		if( (parseInt(contador) == 0) && (parseInt($("#rol").val()) < 4)){
			$("#resultado").css({ color: "#ff0000", background: "#FFFFFF" });
			$("#resultado").html("Por favor seleccione algun menu");
			return false;
		}
		user_nombre = $("#user_nombre").val();
		user_login  = $("#user_login").val();
		user_pass   = $("#user_pass").val();
		user_email  = $("#user_email").val();
		rol 		= $("#rol").val();	
		opc         = $("#opcion").val();
		if( (parseInt($("#rol").val()) == 4) && (parseInt(idarea)) == 0){
			idprograma="";
			idarea=0;
			exito=1;
		}
		else if( (parseInt($("#rol").val()) != 4) && (parseInt(contador) > 0) && (parseInt(idarea)>0) && (String(idprograma)!= "") )
		{
			exito=1;
		}
		else{
			$("#resultado").css({ color: "#ff0000", background: "#FFFFFF" });
			$("#resultado").html("Por favor seleccione unidad responsable y programas");			
		}
		
		
		if(exito == 1){
			bootbox.confirm("<br><br><b>&iquest;Desea grabar los datos del Usuario?</b>", function(result) {
    			if(result)
    			{
    				$("#procesando").show();
    				$("#t_procesando").show();
    		    	random= Math.round(Math.random() * 1000);
    				$.post("ajax/usuarios.php",{random:random,user_nombre:user_nombre,
    					user_login:user_login,user_pass:user_pass,user_email:user_email,rol:rol,
    					idarea:idarea,idprograma:idprograma,valueId:valueId,
    					cadenaCoordinacion:cadenaCoordinacion,estilo:$("#estilo").val(),opc:opc},function(buffer){
    					if(parseInt(buffer) > 0){
    						$("#resultado").css({ color: "#006600", background: "#ffffff" });
    						$("#resultado").html("Se han almacenado las metas.");
    						setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},1000);   
    					}
    					else
    					{
    						if(parseInt(buffer)== -1){
								$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
								$("#resultado").html('El usuario ya existe.');
							}else if(parseInt(buffer)== -2){
								$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
								$("#resultado").html('El nombre ya se encuentra registrado.');
							}
							else{
								$("#resultado").css({ color: "#ff0000", background: "#ffffff" });
								$("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
							}
    						return false;
    					}
    				});
    			}		
        	});	
		}
    })
    // fin de jquery
});


function RevisaCaracteresNumeros(id,valor,min_size,chars){
	var exito=true;
	$("#"+id).css({ background: "#ffffff" });
	if(check_min_length(valor,min_size))
	{
		array_tmp=check_chars(valor,chars);
		if(array_tmp[0]>0)
		{
			$("#"+id).val(array_tmp[1]);
			$("#"+id).css({ background: "#ffff99" });
			$("#"+id).focus();
			exito=false;            
		}            
	}
	else
	{
		$("#"+id).focus();
		$("#"+id).css({ background: "#ffff99" });
		$("#"+id).html("El tama&ntilde;o m&iacute;nimo para este campo es de "+min_size+" caracteres.");
		$("#resultadoModal").html("El tama&ntilde;o m&iacute;nimo para este campo es de "+min_size+" caracteres.");
		exito=false;
	}
	return exito;
}

function RevisaCaracteres(id,valor,min_size,chars){
	if((id == "presupuesto_1") || (id == "estimado_1")){
		min_size=0;
    }
	var exito=true;
	$("#resultado").css({ background: "#ffffff" });
	$("#resultadoModal").css({ background: "#ffffff" });
	$("#"+id).css({ background: "#ffffff" });
	if(check_min_length(valor,min_size))
	{
		array_tmp=check_chars(valor,chars);
		if(array_tmp[0]>0)
		{
			$("#resultado").html("Se eliminar&aacute;n caracteres no v&aacute;lidos.");       
			$("#resultadoModal").html("Se eliminar&aacute;n caracteres no v&aacute;lidos.");       
			$("#"+id).val(array_tmp[1]);
			$("#"+id).css({ background: "#ffff99" });
			$("#"+id).focus();
			exito=false;            
		}            
	}
	else
	{
		if((id != "presupuesto_1") && (id != "estimado_1")){
			$("#"+id).focus();
			$("#"+id).css({ background: "#ffff99" });
			$("#"+id).html("El tama&ntilde;o m&iacute;nimo para este campo es de "+min_size+" caracteres.");
			$("#resultadoModal").html("El tama&ntilde;o m&iacute;nimo para este campo es de "+min_size+" caracteres.");
			exito=false;
		}
	}
	 if(exito){
	        if((id == "presupuesto_1") || (id == "estimado_1"))
	        {
	            $("#"+id).val(numberFormat(valor));
	        }
	}
	return exito;
}

function check_chars(cadena, chars)
{
	var array=new Array();
	var s = "";
	var j = 0;
	for (i = 0; i < cadena.length; i++)
	{
		if (chars.indexOf(cadena.charAt(i)) != -1)
		{
			s = s + cadena.charAt(i);
		}
		else j++;
	}
	cadena = s;  
	if (j > 0)
	{
		array[0]=j;
		array[1]=cadena;
	}
	else
	{
		array[0]=0;
		array[1]=cadena;            
	}
	return array;
}

var lock = "";
var correcto;

function check_min_length(cadena, min_size)
{
	correcto=true;
	if (cadena.length > 0)
	{
		if (cadena.length < min_size)
		{
			correcto=false;
		}
	}
	else
	{
		correcto=false;
	}
	return correcto;
}
function numberFormat(numero)
{
	var resultado = ""; 
    if(numero[0]=="-") {
        nuevoNumero=numero.replace(/\,/g,'').substring(1);
    }
    else{
        nuevoNumero=numero.replace(/\,/g,'');
    }
    if(numero.indexOf(".")>=0){
        nuevoNumero=nuevoNumero.substring(0,nuevoNumero.indexOf("."));
    }
    for (var j, i = nuevoNumero.length - 1, j = 0; i >= 0; i--, j++){
        resultado = nuevoNumero.charAt(i) + ((j > 0) && (j % 3 == 0)? ",": "") + resultado;
    }
    if(numero.indexOf(".")>=0){
    	resultado+=numero.substring(numero.indexOf("."));
    }
    if(numero[0]=="-") {
    	return "-"+resultado;
    }
    else{
            return resultado;
    }
}