var id;
var array_tmp;
var idProyecto;
var idActividad;
var idTrimestre;
$(document).ready(function(){	
    $(".btneliminaAdjunto" ).on( "click", function(e) {
    	array_tmp=$(this).attr('id').split("-");
    	id=array_tmp[0];
    	idProyecto  = array_tmp[1]; 
    	idActividad = array_tmp[2];
    	idTrimestre = array_tmp[3];
		if (parseInt(id) > 0) {
			random= Math.round(Math.random() * 1000);
			$.post("ajax/doajaxfileupload.php",{random:random,id:id,idProyecto:idProyecto,idActividad:idActividad,idTrimestre:idTrimestre,opc:3},function(buffer){
				$("#downloadFiles").html(buffer);
			});
		}
		return false;
    });
});