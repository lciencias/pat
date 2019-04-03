var url = "ajax/catalogos.php";
var idarea;
var idprograma;
var idproyecto;
var valor;
var userId;
var aplicacion;
var apli_com;

$(document).ready(function(){
    $("#MyTableActividades").tablesorter();
     $('#tabs').tab();
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
            $.get(url,{areaId:idarea,programaId:idprograma,opcion:2},function(data){$("#idproyecto").html(data);})
        }
        else{
            $("#idproyecto").html('<option value="" selected="selected">Proyecto</option>');    
        }
    });
    
});