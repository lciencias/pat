var url = "ajax/catalogos.php";
var urlFormatos = "ajax/tiposFormatos.php";
var urlAyudas   = "ajax/tiposAyudas.php";
var urlCatalogo = "ajax/insertaCatalogos.php";
var urlEstilo   = "ajax/actualizaEstilo.php";
var urlAsignacion = "ajax/asignacion.php";
var title="Secretaria de Cultura del D.F.";
var idestatus;
var fechaLimite;
var fechaLimiteIni;
var fechaLimiteFin;
var idTmp;
var ideje;
var idPolitica;
var idarea;
var idtrimestre;
var proyectoId;
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
var chars     ="ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyzÁÉÍÓÚáéíóú0123456789,.&; ._-";
var chars_Nums=" ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyzÁÉÍÓÚáéíóú0123456789+_-*/'@!\"#$%&/()=??'?. ";
var charsNum  ="0123456789,.";
var numsValor ="0123456789";
var charTels  ="0123456789 ()-_";
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
var valor1;
var valor2;
var valor3;
var valor4;
var descripcion;
var descripcion2;
var resultados;
var paginate;
var ipp;
var inicio;
var content;
var fin;
var ano_id;
var tableId;
var idProyectoTmp; 
$(document).ready(function(){
     
    $(document).keypress(function(e) {
        if(e.which == 13) {
            return false;
        }
    });
     $(".alert").hide();
     $('#tabs').tab();
	 $('.summernote').summernote({height: 180});
     $('#summernote').summernote({height: 180});
     $('#summernoteV').summernote({height: 180});
     $('#summernoteMas').summernote({height: 180});
     $("#summernoteVis").summernote({height: 180});
     $("#descripcion").summernote({height: 80});
     $("#resultados").summernote({height: 80});
    $("#actSummernoteMas").summernote({height: 180});
      
    if($("#idarea").val()> 0)
        $("#btn-5").show();
    else
        $("#btn-5").hide();
    $('[data-toggle="tooltip"]').tooltip()
    $('#fechaLimite').datepicker({format: "dd/mm/yyyy"});
    $('#fechaLimiteIni').datepicker({format: "dd/mm/yyyy"});
    $('#fechaLimiteFin').datepicker({format: "dd/mm/yyyy"});
     
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
    $(".tablesorter").tablesorter();
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
 
 
    $("#ano_id").change(function(){
        ano_id = $("#ano_id").val();
        url = "ajax/actualizaAno.php";
        if(parseInt(ano_id)>0){
            $.post(url,{ano_id:ano_id},function(data){
                $("#ano_id").val(data);
                location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val();
            });
 
        }
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
        if(parseInt(idarea) != 0){
            $("#btn-5").show();
            $.get(url,{areaId:idarea,opcion:1},function(data){
                $("#idprograma").html(data);
            })
            $.get(url,{areaId:idarea,opcion:10},function(databuffer){
                valor=databuffer.split("|");
                $("#nmejes").html(valor[0]);
                $("#nmpoliticas").html(valor[1]);
            });
            $.get(url,{areaId:idarea,opcion:6},function(data){$("#idunidadoperativa").html(data);})
        }
        else
        {
            $("#btn-5").hide();
            $("#idprograma").html('<option value="" selected="selected">Programa</option>');
        }
    });
 
    $("#idprograma").change(function(){
        idarea     = $("#idarea").val();
        idprograma = $("#idprograma").val();
        if( (parseInt(idarea) != 0) && (parseInt(idprograma) != 0))
        {
            $("#mas").show();           
//          $.get(url,{areaId:idarea,programaId:idprograma,opcion:2},function(data){$("#idobjetivog").html(data);})
        }
        else{
            $("#mas").hide();
//          $("#idobjetivog").html('<option value="" selected="selected">Objetivo General</option>');    
        }
    });
    $("#idprograma").blur(function(){
        idarea     = $("#idarea").val();
        idprograma = $("#idprograma").val();
        if( (parseInt(idarea) != 0) && (parseInt(idprograma) != 0))
        {
        $("#mas").show();
        }    
    });
    $("#mas").click(function(){
        idarea     = $("#idarea").val();
        idprograma = $("#idprograma").val();
        if( (parseInt(idarea) != 0) && (parseInt(idprograma) != 0))
        {
            $.get(url,{areaId:idarea,programaId:idprograma,opcion:11},function(databuffer){
                valor=databuffer.split("|");
                $("#well").html("<span class='negritas'><b>Eje:</b></span><br> "+valor[0]+"<br><span class='negritas'><b>Pol&iacute;ticas:</b></span><br> "+valor[1]);
         
            });
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
            $("#resultado").css({ color: "#ffffff", background: "#ffffFF" });
            $("#idunidadoperativa").css({ color: "#000000", background: "#ffffFF" });
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
        return RevisaCaracteres($(this).attr('id'),$(this).val(),1,charsNum);
    }); 
 
    $(".validatextonumero").change(function(){
        return RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars_Nums);
    }); 
     
    //guardar catalogo
    $(".savecatalogo").click(function(){
        valor=$(this).attr('id');
        idCatalogo=0;
        idTmp=0;        
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
            idTmp=$("#idpoliticap").val();
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
            idCatalogo=$("#idarea").val();
            url="ajax/unidadoperativa.php";
            break;
        case "guardarAno":
            url="ajax/anos.php";
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
                    $.post(url,{random:random,idSec:idValue,nombre:$("#nomCatalogo").val(),eje_id:idCatalogo,idTmp:idTmp,active:$("#edoId").val(),opc:opc},function(bufferAct){
                        if(parseInt(bufferAct) > 0){                            
                            $("#resultado").css({ color: "#006600", background: "#ffffff" });
                            $("#resultado").html("Se ha almacenado el folio: "+bufferAct+".");
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},300);   
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
 
    $(".savecatalogotipo2").click(function(){
        valor=$(this).attr('id');
        idCatalogo=0;
        switch (String(valor)) {
            case "guardarTipoActividad":
                url="ajax/tipoActividad.php";
                break;
            case "guardarPonderacion":
                url="ajax/ponderacion.php";
                break;
        }
         
        exitoLetras  = true;
        exitoNumeros = true;
        exitoAlfanum = true;
        $(".validatexto").each(function(){
            exitoLetras=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars);
        });
 
        $(".validatextonumero").each(function(){
            exitoLetrasNum=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars_Nums);          
        });
        //validamos numeros
        $(".validanums").each(function(){
            exitoNumeros=RevisaCaracteres($(this).attr('id'),$(this).val(),1,numsValor);
        });
        if ( (exitoLetras) && (exitoNumeros) && (exitoAlfanum))
        {
            valor2 = $("#nomCatalogo").val();
            valor3 = $("#descripcion").code();          
            valor4 = $("#edoId").val();
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
                    $.post(url,{random:random,idValue:idValue,nombre:valor2,descripcion:valor3,active:valor4,opc:opc},function(bufferAct){
                        if(parseInt(bufferAct) > 0){                            
                            $("#resultado").css({ color: "#006600", background: "#ffffff" });
                            $("#resultado").html("Se ha almacenado el folio: "+bufferAct+".");
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},300);   
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
        paginate=$("#paginate").val();
        ipp =$("#ipp").val();
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
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0&page="+paginate+"&ipp="+ipp},300);   
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
                            //setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},300);   
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
        paginate=$("#paginate").val();
        ipp =$("#ipp").val();
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
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0&page="+paginate+"&ipp="+ipp},300);   
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
                else if(parseInt(bufferAct) < 0){
                    $("#resultadoModal2").css({ color: "#ff0000", background: "#ffffff" });
                    $("#resultadoModal2").html('La unidad Operativa ya fue registrada.');
     
                }
                else
                {
                    $("#resultadoModal2").css({ color: "#ff0000", background: "#ffff99" });
                    $("#resultadoModal2").html('Ha surgido un error, por favor comunicate con el administrador del sistema');
                    return false;
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
        idano     = $("#idano").val();
        valor=$(this).attr('id');
        exitoLetras  = true;
        exitoNumeros = true;
        exitoAlfanum = true;
        $("#resultado").css({ color: "#ffffff", background: "#ffffFF" });
        $("#idarea").css({ color: "#000000", background: "#ffffFF" });
        $("#idprograma").css({ color: "#000000", background: "#ffffFF" });
        $("#idunidadoperativa").css({ color: "#000000", background: "#FFFFFF" });
        $("#idresponsableunidado").css({ color: "#000000", background: "#FFFFFF" });
        if(parseInt($("#idarea").val()) <= 0){
            $("#idarea").css({ color: "#610B0B", background: "#ffff99" });
            $("#resultado").css({ color: "#ff0000", background: "#ffffff" });
            $("#resultado").html('Seleccione el area');
            bootbox.alert('Por favor seleccione el area').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
            return false;
        }
        if(parseInt($("#idprograma").val()) <= 0){
            $("#idprograma").css({ color: "#610B0B", background: "#ffff99" });
            $("#resultado").css({ color: "#ff0000", background: "#ffffff" });
            $("#resultado").html('Seleccione el programa');
            bootbox.alert('Por favor seleccione el programa').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
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
            bootbox.alert('Seleccione la ponderacion').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
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
            bootbox.alert('Por favor seleccione la unidad operativa').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
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
                        idproyecto:$("#valueId").val(),idano:idano,
                        idunidadoperativa:$("#idunidadoperativa").val(),
                        idresponsableunidado:$("#idresponsableunidado").val(),ponderacion:ponderacion,
                        descripcion:$("#descripcion").code(),
                        resultados:$("#resultados").code(),
                        en_coordinacion:encoordinacion,
                        especifique:$("#especifique").val(),
                        participacion:$("#idopcion").val(),
                        presupuesto:$("#presupuesto_1").val(),
                        estimado:$("#estimado_1").val(),
                        opc:1},function(bufferAct){
                        if(parseInt(bufferAct) > 0){                            
                            $("#resultado").css({ color: "#006600", background: "#ffffff" });
                            $("#resultado").html("Se ha almacenado el registro.");
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},300);   
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
        paginate=$("#paginate").val();
        ipp =$("#ipp").val();
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
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=3&page="+paginate+"&ipp="+ipp+"&folio="+array_tmp[1]+"-"+array_tmp[2]},500);   
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
        paginate=$("#paginate").val();
        ipp =$("#ipp").val();
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
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0&page="+paginate+"&ipp="+ipp},500);   
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
        idano     = $("#idano").val();
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
            bootbox.alert('Por favor seleccione el area').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
            $("#resultado").html('Seleccione el area');
            return false;
        }
        if(parseInt($("#idprograma").val()) <= 0){
            $("#idprograma").css({ color: "#610B0B", background: "#ffff99" });
            $("#resultado").css({ color: "#ff0000", background: "#ffffff" });
            $("#resultado").html('Seleccione el programa');
            bootbox.alert('Por favor seleccione el programa').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );         
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
            bootbox.alert('Por favor seleccione la ponderacion').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
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
            bootbox.alert('Por favor seleccione la unidad operativa').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
            return false;
        }
        if(parseInt($("#idresponsableunidado").val()) <= 0){
            $("#idresponsableunidado").css({ color: "#610B0B", background: "#ffff99" });
            $("#resultado").css({ color: "#ff0000", background: "#ffffff" });
            $("#resultado").html('Seleccione el responsable');
            bootbox.alert('Por favor seleccione el nombre del responsable').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
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
                        idproyecto:$("#idproyecto").val(),idano:idano,
                        idunidadoperativa:$("#idunidadoperativa").val(),
                        idresponsableunidado:$("#idresponsableunidado").val(),ponderacion:ponderacion,
                        descripcion:$("#descripcion").code(),
                        resultados:$("#resultados").code(),
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
        valor1 = $("#p-0-1").val();
        valor2 = $("#p-0-2").val();
        valor3 = $("#p-0-3").val();
        valor4 = $("#p-0-4").val();
        exitoAlfanum = true;
        $("#idMedida").css({ color: "#000000", background: "#FFFFFF" });
        $("#idTipoActividad").css({ color: "#000000", background: "#FFFFFF" });
 
        if(parseInt($("#idMedida").val()) <= 0){
            $("#idMedida").css({ color: "#610B0B", background: "#ffff99" });
            bootbox.alert('Por favor seleccione la unidad de medida').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
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
            bootbox.alert('Por favor seleccione la ponderaciï¿½n').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
            return false;
        }
         
        if(parseInt($("#idTipoActividad").val()) <= 0){
            $("#idTipoActividad").css({ color: "#610B0B", background: "#ffff99" });
            bootbox.alert('Por favor seleccione el tipo de actividad').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
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
                        valor1:valor1,valor2:valor2,valor3:valor3,valor4:valor4,
                        opc:4},function(bufferAct){
                        if(parseInt(bufferAct) > 0){
                            $("#resultado").css({ color: "#006600", background: "#ffffff" });
                            $("#resultado").html("Se ha actualizado la actividad.");
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=3&folio="+folio},800);   
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
        valor1 = valor2 = valor3 = valor4 = 0;
        userId    = $("#userId").val();
        aplicacion= $("#aplicacion").val();
        apli_com  = $("#apli_com").val();
        folio     = $("#folio").val();
        random    = $("#random").val();
        idtrimestre= $("#idtrimestre").val();
        valor1 = $("#p-0-1").val();
        valor2 = $("#p-0-2").val();
        valor3 = $("#p-0-3").val();
        valor4 = $("#p-0-4").val();
        url="ajax/salvarProyecto.php";
        exitoAlfanum = true;
        $("#idMedida").css({ color: "#000000", background: "#FFFFFF" });
        $("#idTipoActividad").css({ color: "#000000", background: "#FFFFFF" });
 
        if(parseInt($("#idMedida").val()) <= 0){
            $("#idMedida").css({ color: "#610B0B", background: "#ffff99" });
            bootbox.alert('Por favor seleccione la unidad de medida').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
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
            bootbox.alert('Por favor seleccione la ponderacion').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
            return false;
        }
         
        if(parseInt($("#idTipoActividad").val()) <= 0){
            $("#idTipoActividad").css({ color: "#610B0B", background: "#ffff99" });
            bootbox.alert('Por favor seleccione el tipo de actividad').find('.modal-content').css({'background-color': '#610B0B', color: '#FFFFFF', } );
            return false;
        }       
        //validamos alfanumericos
        /*$(".validatextonumero").each(function(){
            exitoAlfanum=RevisaCaracteres($(this).attr('id'),$(this).val(),min_size,chars_Nums);
        });*/
         
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
                        random:random,idtrimestre:idtrimestre,
                        ponderacion:ponderacion,
                        actividad:$("#actividad").val(),
                        valor1:valor1,valor2:valor2,valor3:valor3,valor4:valor4,
                        opc:2},function(bufferAct){
                        if(parseInt(bufferAct) > 0){
                            $("#resultado").css({ color: "#006600", background: "#ffffff" });
                            $("#resultado").html("Se ha almacenado el registro.");
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=3&folio="+folio},300);   
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
            $(item).prop('checked', true);
        });
    });
 
    $(".ningunos").click(function(){
        $('input:checkbox').each(function(i, item){
            $(item).prop('checked', false);
        });
    });
     
    $("#cambiaFaseAvance").click(function(){
        $("#res").css({ color: "#ffffff", background: "#ffffff" });
        idtrimestre = $("#trimestreId").val();
        userId      = $("#userId").val();
        aplicacion  = $("#aplicacion").val();
        apli_com    = $("#apli_com").val();
        contador    = 0;
        encoordinacion="";
        $(".enviaIdAvance").each(function(){
            if($(this).is(':checked')){
                encoordinacion+=$(this).val()+"|";
                contador++;
            }
        });
        if(parseInt(contador) > 0 && parseInt(idtrimestre) > 0){
            bootbox.confirm("<br><br><b>&iquest;Desea enviar "+contador+" avances para su validacion?</b>", function(result) {
                if(result){
                    random= Math.round(Math.random() * 1000);
                    $.post("ajax/notificacionesAvances.php",{random:random,idtrimestre:idtrimestre,proyectosIds:encoordinacion,opcion:1},function(bufferAct){
                        $("#res").html("Se han cambiado de estatus los proyectos");
                        setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()},300);
                    }); 
                }
            });
        }
        else{           
            $("#res").css({ color: "#ff0000", background: "#ffffff" });
            $("#res").html("Por favor seleccione al menos un proyecto");
        }
    });
     
     
    $("#cambiaFase2").click(function(){
        $("#res").css({ color: "#ffffff", background: "#ffffff" });
        userId    = $("#userId").val();
        aplicacion= $("#aplicacion").val();
        apli_com  = $("#apli_com").val();
        contador=0;
        encoordinacion="";
        $(".enviaId").each(function(){
            if($(this).is(':checked')){
                encoordinacion+=$(this).val()+"|";
                contador++;
            }
        });
        if(parseInt(contador) > 0){
            bootbox.confirm("<br><br><b>&iquest;Desea enviar "+contador+" proyectos para su validacion?</b>", function(result) {
                if(result){
                    random= Math.round(Math.random() * 1000);
                    $.post("ajax/notificaciones.php",{random:random,proyectosIds:encoordinacion,opcion:1},function(bufferAct){
                        $("#res").html("Se han cambiado de estatus los proyectos");
                        setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()},300);
                    }); 
                }
            });
        }
        else{           
            $("#res").css({ color: "#ff0000", background: "#ffffff" });
            $("#res").html("Por favor seleccione al menos un proyecto");
        }
    });
     
    //sumador de metras al agregar metas
    $(".validanumsM").blur(function(){
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
 
    //sumador para avance de metas
    $(".validanumsMA").blur(function(){
        var idDiv; 
        var contador;
        var array_tmp;
        idDiv = "";
        contador = 0;
        //limpio array
        $('.validanumsMA').each(function(){
            idDiv = $(this).attr('id');
            array_tmp=idDiv.split('-');
            contador=array_tmp[1];               
            arrayNoActividades[contador] = 0;
        });
        // Genero sumas
        $('.validanumsMA').each(function(){
            idDiv = $(this).attr('id');
            array_tmp=idDiv.split('-');
            contador=array_tmp[1];               
            if(String($(this).val()) === ""){
                $(this).val(parseInt('0'));
                valor = 0;
            }else{
                valor = parseInt($(this).val());
            }                   
            if( (parseInt(array_tmp[5]) === 1 ) || (parseInt(array_tmp[5]) === 5 )){
                arrayNoActividades[contador] = arrayNoActividades[contador] + valor;                            
                $("#rtotal"+contador).html(arrayNoActividades[contador]);
            random = random + valor;
            $("#rtotales").html(random);
            }
        });
    });
             
     
    //sumador de metras al agregar actividades
    $(".validanumsMR").change(function(){
        contador=0;
        RevisaCaracteresNumeros($(this).attr('id'),$(this).val(),1,numsValor);
        if(String($(this).val()) === ""){
            $(this).val(parseInt('0'));
            valor = 0;
        }
        random = 0;
        if( (parseInt($("#idTipoActividad").val()) === 1 ) || (parseInt($("#idTipoActividad").val()) === 5 )){
            random = random  + parseInt($("#p-0-1").val());
            random = random  + parseInt($("#p-0-2").val());
            random = random  + parseInt($("#p-0-3").val());
            random = random  + parseInt($("#p-0-4").val());
            $("#total"+contador).html(random);
        }
        if(parseInt($("#idTipoActividad").val()) === 3 ){
            if(parseInt($("#p-0-1").val()) < parseInt($("#p-0-2").val()))
                random= parseInt($("#p-0-1").val());
            else
                random= parseInt($("#p-0-2").val());
             
            if(random > parseInt($("#p-0-3").val()))
                random = parseInt($("#p-0-3").val());
 
            if(random > parseInt($("#p-0-4").val()))
                random = parseInt($("#p-0-4").val());
            $("#total"+contador).html(random);
        }
        if(parseInt($("#idTipoActividad").val()) === 4 ){
            random= parseInt($("#p-0-1").val());
            $("#total"+contador).html(random);
        }
    });
     
    /***** Guarda metas  *****/
     
    $("#guardaAvance").click(function(){
        var valor="";
        var content="";
        proyectoId  = $("#folio").val();
        idtrimestre = $("#trimestreId").val();
         
        $('.validanumsMA').each(function(i, item){
            content = $(item).attr('id')+"-"+$(item).val();
            valor= valor+"|"+content;
        });
        if( (parseInt(proyectoId) > 0) && (String(valor) !== "")){
            bootbox.confirm("<br><br><b>&iquest;Desea grabar los avances?</b>", function(result) {
                if(result)
                {
                    $("#procesando").show();
                    $("#t_procesando").show();
                    random= Math.round(Math.random() * 1000);
                    $.post("ajax/salvarProyecto.php",{random:random,proyectoId:proyectoId,valor:valor,idtrimestre:idtrimestre,opc:8},function(buffer){
                        if(parseInt(buffer) > 0){
                            $("#resultado").css({ color: "#006600", background: "#ffffff" });
                            $("#resultado").html("Se han almacenado los avances.");
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
     
     
    /***** Guarda metas  *****/
    $("#guardaMeta").click(function(){
        valor="";
        inputNombre = "";
        proyectoId=$("#folio").val();
        $('input:text').each(function(i, item){
            inputNombre=$(item).attr('id');
            if(inputNombre.substring(0, 1) === 'p'){
                valor += inputNombre+"-"+$(item).val()+"|";
            }
        });
        if( (parseInt(proyectoId) > 0) && (valor!== "")){
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
        idunidadoperativa = $("#idunidadoperativa").val();
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
        descripcion  =$("#user_datos").val();
        descripcion2  =$("#user_datos2").val();
        rol         = $("#rol").val();  
        opc         = $("#opcion").val();
        if( (parseInt($("#rol").val()) >= 4) && (parseInt(idarea)) == 0){
            idprograma="";
            idarea=0;
            exito=1;
        }
        else if( (parseInt($("#rol").val()) < 4) && (parseInt(contador) > 0) && (parseInt(idarea)>0) && (String(idprograma)!= "") )
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
                        idarea:idarea,idprograma:idprograma,valueId:valueId,user_datos:descripcion,
                        user_datos2:descripcion2,idunidadoperativa:idunidadoperativa,
                        cadenaCoordinacion:cadenaCoordinacion,estilo:$("#estilo").val(),opc:opc},function(buffer){
                        if(parseInt(buffer) > 0){
                            $("#resultado").css({ color: "#006600", background: "#ffffff" });
                            $("#resultado").html("Se han guardado los datos del usuario.");
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},800);   
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
    });
 
 
    //fecha de captura
    $(".FechaArea").click(function(){
        contador=0;
        idano = $("#idano").val();
        $("#fechaLimite").css({ color: "#000000", background: "#ffffff" });
        id = $(this).attr('id');
        tmp=id.split("-");
        fechaLimite = $("#fechaLimite").val();
        $("#resultado"+tmp[1]).css({ color: "#ffffff", background: "#ffffff" });
        if(String(fechaLimite)!= ""){
            $("#procesando").show();
            $("#t_procesando").show();
            random= Math.round(Math.random() * 1000);
            if($("#"+id).is(':checked')) {
                contador=1;
                $(".FechaPrograma"+tmp[1]).prop('checked', true);
            }
            else{
                contador=2;
                $(".FechaPrograma"+tmp[1]).prop('checked', false);
            }
            $.post("ajax/permisos.php",{random:random,idano:idano,fechaLimite:fechaLimite,status:contador,areaId:$(this).val(),opc:1},function(buffer){
                $("#procesando").hide();
                $("#t_procesando").hide();
                $("#resultado"+tmp[1]).css({ color: "#006600", background: "#ffffff" });                    
                if(parseInt(contador)==1)
                    $("#resultado"+tmp[1]).html('Se han registrado los permisos.');
                else
                    $("#resultado"+tmp[1]).html('Se han cancelado los permisos.');
            });             
        }else{
            id = $(this).attr('checked', false);
            $("#fechaLimite").css({ color: "#000", background: "#ffff99" });
            $("#resultado"+tmp[1]).css({ color: "#ff0000", background: "#ffffff" });
            $("#resultado"+tmp[1]).html('Selecciona la fecha.');
        }
    });
 
    $(".programasFecha").click(function(){
        contador=0;
        idano = $("#idano").val();
        $("#fechaLimite").css({ color: "#000000", background: "#ffffff" });
        id = $(this).attr('id');
        tmp=id.split("-");
        $("#resultado"+tmp[1]).css({ color: "#ffffff", background: "#ffffff" });
        fechaLimite = $("#fechaLimite").val();
        if(String(fechaLimite)!= ""){
            $("#procesando").show();
            $("#t_procesando").show();
            random= Math.round(Math.random() * 1000);
            if($("#"+id).is(':checked')) {
                contador=1;
            }
            else{
                contador=2;
            }
            $.post("ajax/permisos.php",{random:random,idano:idano,fechaLimite:fechaLimite,status:contador,id:id,opc:2},function(buffer){
                $("#procesando").hide();
                $("#t_procesando").hide();
                $("#resultado"+tmp[1]).css({ color: "#006600", background: "#ffffff" });                    
                if(parseInt(contador)==1)
                    $("#resultado"+tmp[1]).html('Se han registrado los permisos.');
                else
                    $("#resultado"+tmp[1]).html('Se han cancelado los permisos.');
            });             
        }else{
            id = $(this).attr('checked', false);
            $("#fechaLimite").css({ color: "#000", background: "#ffff99" });
            $("#resultado"+tmp[1]).css({ color: "#ff0000", background: "#ffffff" });
            $("#resultado"+tmp[1]).html('Selecciona la fecha.');
        }
    });
 
    // fecha de avances
    $(".FechaAreaA").click(function(){
        contador=0;
        idano = $("#idano").val();
        $("#fechaLimiteIni").css({ color: "#000000", background: "#ffffff" });
        $("#fechaLimiteFin").css({ color: "#000000", background: "#ffffff" });
        id = $(this).attr('id');
        tmp=id.split("-");
        fechaLimiteIni = $("#fechaLimiteIni").val();
        fechaLimiteFin = $("#fechaLimiteFin").val();
        idtrimestre    = $("#idtrimestre").val();
        $("#resultado"+tmp[1]).css({ color: "#ffffff", background: "#ffffff" });
        if(String(fechaLimite)!= ""){
            $("#procesando").show();
            $("#t_procesando").show();
            random= Math.round(Math.random() * 1000);
            if($("#"+id).is(':checked')) {
                contador=1;
                $(".FechaProgramaA"+tmp[1]).prop('checked', true);
            }
            else{
                contador=2;
                $(".FechaProgramaA"+tmp[1]).prop('checked', false);
            }
            $.getJSON("ajax/permisos.php",{random:random,idano:idano,fechaLimiteIni:fechaLimiteIni,
                fechaLimiteFin:fechaLimiteFin,idtrimestre:idtrimestre,status:contador,
                areaId:$(this).val(),opc:3},function(buffer){
                    $.each(buffer, function(k,v){
                        $("#"+k).html("");
                        $("#"+k).html(v);
                    });
                $("#procesando").hide();
                $("#t_procesando").hide();
                $("#resultado"+tmp[1]).css({ color: "#006600", background: "#ffffff" });                    
                if(parseInt(contador)==1){
                    $("#resultado"+tmp[1]).html('Se han registrado los permisos.');
                }
                else{
                    $("#resultado"+tmp[1]).html('Se han cancelado los permisos.');
                }
            });             
        }else{
            id = $(this).attr('checked', false);
            $("#fechaLimiteIni").css({ color: "#000", background: "#ffff99" });
            $("#fechaLimiteFin").css({ color: "#000", background: "#ffff99" });
            $("#resultado"+tmp[1]).css({ color: "#ff0000", background: "#ffffff" });
            $("#resultado"+tmp[1]).html('Selecciona la fecha.');
        }
    });
 
    $(".programasFechaA").click(function(){
        contador=0;
        idano = $("#idano").val();
        $("#fechaLimiteIni").css({ color: "#000000", background: "#ffffff" });
        $("#fechaLimiteFin").css({ color: "#000000", background: "#ffffff" });
        id = $(this).attr('id');
        tmp=id.split("-");
        fechaLimiteIni = $("#fechaLimiteIni").val();
        fechaLimiteFin = $("#fechaLimiteFin").val();
        idtrimestre    = $("#idtrimestre").val();
        if( (String(fechaLimiteIni)!= "") && (String(fechaLimiteFin)!= "") ){
            $("#procesando").show();
            $("#t_procesando").show();
            random= Math.round(Math.random() * 1000);
            if($("#"+id).is(':checked')) {
                contador=1;
            }
            else{
                contador=2;
            }
            $.getJSON("ajax/permisos.php",{random:random,idano:idano,fechaLimiteIni:fechaLimiteIni,
                fechaLimiteFin:fechaLimiteFin,idtrimestre:idtrimestre,status:contador,id:id,opc:4},function(buffer){
                $.each(buffer, function(k,v){
                    $("#"+k).html("");
                    $("#"+k).html(v);
                });
                $("#procesando").hide();
                $("#t_procesando").hide();
                $("#resultado"+tmp[1]).css({ color: "#006600", background: "#ffffff" });                    
                if(parseInt(contador)==1)
                    $("#resultado"+tmp[1]).html('Se han registrado los permisos de avances.');
                else
                    $("#resultado"+tmp[1]).html('Se han cancelado los permisos de avances.');
            });             
        }else{
            id = $(this).attr('checked', false);
            $("#fechaLimiteIni").css({ color: "#000", background: "#ffff99" });
            $("#fechaLimiteFin").css({ color: "#000", background: "#ffff99" });
            $("#resultado"+tmp[1]).css({ color: "#ff0000", background: "#ffffff" });
            $("#resultado"+tmp[1]).html('Seleccione fecha inicial y final.');
        }
    });
     
    $("#idTipoActividad").change(function(){
        $("#comentarioTipoActividad").html("");
        $("#p-0-1").val("0");
        $("#p-0-2").val("0");
        $("#p-0-3").val("0");
        $("#p-0-4").val("0");
        $("#total"+contador).html("");
        $("#comentarioTipoActividad").css({ color: "#000", background: "#fff" });
        if(parseInt($("#idTipoActividad").val())>0){
            if($("#idTipoActividad").val() == 2)
                $("#regmetas").hide();
            else
                $("#regmetas").show();
            $.post("ajax/tipoActividad.php",{random:random,idTipoActividad:$("#idTipoActividad").val(),opc:8},function(buffer){         
                $("#comentarioTipoActividad").css({ color: "#800000", background: "#ffffff" });
                $("#comentarioTipoActividad").html(buffer);
            });
        }
    });
    // fin de jquery
    $(".pondera").click(function(){
        $("#comentariopodenracion").html("");
        $("#comentariopodenracion").css({ color: "#000", background: "#e5e5e5" });
        if(parseInt($(this).val())>0){
            $.post("ajax/ponderacion.php",{random:random,idPonderacion:$(this).val(),opc:8},function(buffer){           
                $("#comentariopodenracion").css({ color: "#800000", background: "#f5f5f5" });
                $("#comentariopodenracion").html(buffer);
            });
        }
    });
     
    //para consultar informacion
    $(".mComentariosConsulta").click(function(){
            rol = $("#apli_rol").val();
            if(parseInt(rol)>2)
                $("#btncomentario").hide();
             
        array_tmp=$(this).attr('id').split('-');        
        idproyecto  = array_tmp[0]; 
        idactividad = array_tmp[1];
        idtrimestre = array_tmp[2];
        $("#comentarios").html("");
        $("#resultado").html("");
        $("#fileToUpload").html("");
        $("#downloadFiles").html("");
        if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0) && (parseInt(idtrimestre) > 0)){
            random= Math.round(Math.random() * 1000);
            $("#idProyecto").val(idproyecto);
            $("#idActividad").val(idactividad);
            $("#idTrimestre").val(idtrimestre);
            $("#random").val(random);
            if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0) ){
                $.post("ajax/comentariosM.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,idtrimestre:idtrimestre,opc:1},function(buffer){
                     $("#summernote").code(buffer);
                     $.post("ajax/doajaxfileupload.php",{random:random,idProyecto:idproyecto,idActividad:idactividad,idTrimestre:idtrimestre,opc:2},function(bufferFiles){
                         $("#downloadFiles").html(bufferFiles);
                         $('#myModal').modal('show');
                     });
                })
            }
        }
    });
    // para actualizar informacion
    $(".mComentarios").click(function(){
        $("#btncomentario").show();
        array_tmp=$(this).attr('id').split('-');        
        idproyecto  = array_tmp[0]; 
        idactividad = array_tmp[1];
        idtrimestre = array_tmp[2];
        $("#comentarios").html("");
        $("#resultado").html("");
        $("#fileToUpload").html("");
        $("#downloadFiles").html("");
        $("#btncomentario").val(idactividad);
        if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0) && (parseInt(idtrimestre) > 0)){
            random= Math.round(Math.random() * 1000);
            $("#idProyecto").val(idproyecto);
            $("#idActividad").val(idactividad);
            $("#idTrimestre").val(idtrimestre);
            $("#random").val(random);
            if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0) ){
                $.post("ajax/comentariosM.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,idtrimestre:idtrimestre,opc:1},function(buffer){
                     $("#summernote").code(buffer);
                     $.post("ajax/doajaxfileupload.php",{random:random,idProyecto:idproyecto,idActividad:idactividad,idTrimestre:idtrimestre,opc:2},function(bufferFiles){
                         $("#downloadFiles").html(bufferFiles);
                         $('#myModal').modal('show');
                     });
                })
            }
        }
    });
         
    $(".mas").click(function(){
        array_tmp=$(this).attr('id').split('-');        
        idproyecto  = array_tmp[1]; 
        idactividad = array_tmp[2];
        if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0)){
            random= Math.round(Math.random() * 1000);            
            $.post("ajax/comentariosM.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,opc:3},function(buffer){
                $("#summernoteMas").code(buffer);
                $('#myModalTrimestres').modal('show');
            });
        }
        return false;
    });
     
    $(".masFile").click(function(){
        array_tmp=$(this).attr('id').split('-');        
        idproyecto  = array_tmp[1]; 
        idactividad = array_tmp[2];
        if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0)){
            random= Math.round(Math.random() * 1000);            
            $.post("ajax/comentariosM.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,opc:3},function(buffer){
                $.post("ajax/regresaArchivos.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,opc:1},function(files){
                    $("#summernoteMas").code(buffer);
                    $("#files").html(files);
                    $('#myModalTrimestres').modal('show');                  
                });
            });
        }
        return false;
    });
     
    $("#btncomentarioA").click(function(){
        $("#resultado").css({ color: "#ffffff", background: "#FFFFFF" });
        $("#resultado").html("");
        idproyecto  = $("#idProyectoV").val();
        idtrimestre = $("#idTrimestreV").val();
        content = $('#summernoteV').code();
        if( (parseInt(idproyecto) > 0) && (String(content)!= "")){
            random= Math.round(Math.random() * 1000);
            $.post("ajax/validaciones.php",{random:random,idproyecto:idproyecto,idtrimestre:idtrimestre,aprobado:1,content:content,opc:2},function(buffer){ 
                if(parseInt(buffer)>0){
                    $("#resultado").css({ color: "#006600", background: "#ffffff" });
                    $("#resultado").html("Se han guardado los comentarios.");
                    setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()},300);
 
                }else{
                    $("#resultado").css({ color: "#ff0000", background: "#ffffff" });
                    $("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');                 
                }
             
            });
        }
    });
     
    $("#btncomentarioNA").click(function(){
        $("#resultado").css({ color: "#ffffff", background: "#FFFFFF" });
        $("#resultado").html("");
        idproyecto  = $("#idProyectoV").val();
        idactividad = $("#idActividadV").val();
        idtrimestre = $("#idTrimestreV").val();
        content = $('#summernoteV').code();
        if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) >= 0) && (parseInt(idtrimestre) >= 0) && (String(content)!= "")){
            random= Math.round(Math.random() * 1000);
            $.post("ajax/validaciones.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,idtrimestre:idtrimestre,aprobado:2,content:content,opc:2},function(buffer){ 
                if(parseInt(buffer)>0){
                    $("#resultado").css({ color: "#006600", background: "#ffffff" });
                    $("#resultado").html("Se han guardado los comentarios.");
                    if(parseInt(idactividad) > 0){
                        if(parseInt($("#aplicacion").val()) != 4)
                        setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=3&folio="+idproyecto+"-2"},300);                        
                        else
                        setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=9&folio="+idproyecto},300);
                    }
                    else
                        setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()},300);
                 
                }else{
                    $("#resultado").css({ color: "#ff0000", background: "#ffffff" });
                    $("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');                 
                }
             
            });
        }
    });
     
    $("#btncomentario").click(function(){
        $("#resultado").css({ color: "#ffffff", background: "#FFFFFF" });
        $("#resultado").html("");
        $("#fileToUpload").html("");
        idproyecto  = $("#idProyecto").val();
        idactividad = $("#idActividad").val();
        idtrimestre = $("#idTrimestre").val();
        content = $('#summernote').code();
        valor   = $(this).attr('value'); 
        if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0) && (String(content)!= "")){
            random= Math.round(Math.random() * 1000);
            $.post("ajax/comentarios.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,idtrimestre:idtrimestre,content:content,opc:2},function(buffer){ 
                if(parseInt(buffer)>0){
                    $("#comment-"+valor).html("<b>Comentario</b>:<br>"+content.substring(0,35));
                    $("#resultado").css({ color: "#006600", background: "#ffffff" });
                    $("#resultado").html("Se han guardado los comentarios.");
                    $(".modal").modal('hide');
                     
                }else{
                    $("#resultado").css({ color: "#ff0000", background: "#ffffff" });
                    $("#resultado").html('Ha surgido un error, por favor comunicate con el administrador del sistema');                 
                }
             
            });
        }
    });
     
    $(".validarAvance").click(function(){
        array_tmp=$(this).attr('id').split('-');
        idproyecto  = array_tmp[0]; 
        idestatus   = array_tmp[1];
        idtrimestre = array_tmp[2];
        if(parseInt(idproyecto) > 0){    
            random= Math.round(Math.random() * 1000);
            $.post("ajax/validacionesAvances.php",{random:random,idproyecto:idproyecto,idtrimestre:idtrimestre,idestatus:idestatus,opc:1},function(buffer){
                 $("#idProyectoV").val(idproyecto);
                 $("#idTrimestreV").val(idtrimestre);
                 $("#summernoteV").code(buffer);
                 $('#myModalValida').modal('show');
            })          
            return false;
        }
    });
     
    $(".validar").click(function(){
        array_tmp=$(this).attr('id').split('-');
        idproyecto  = array_tmp[0]; 
        idestatus = array_tmp[1];
        if(parseInt(idproyecto) > 0){    
            random= Math.round(Math.random() * 1000);
            $.post("ajax/validaciones.php",{random:random,idproyecto:idproyecto,idestatus:idestatus,opc:1},function(buffer){
                 $("#idProyectoV").val(idproyecto);
                 $("#summernoteV").code(buffer);
                 $('#myModalValida').modal('show');
            })          
            return false;
        }
    });
     
    $(".visualizaComentarios").click(function(){
        array_tmp=$(this).attr('id').split('-');
        idproyecto  = array_tmp[0]; 
        idestatus = array_tmp[1];
        if(parseInt(idproyecto) > 0 && parseInt(idestatus)>2){    
            random= Math.round(Math.random() * 1000);
            $.post("ajax/visualizar.php",{random:random,idproyecto:idproyecto,idestatus:idestatus,opc:1},function(buffer){
                $("#summernoteVis").code(buffer);
                $('#myModalVisualiza').modal('show');
            });         
            return false;
        }
    });
 
    $("#pvalidados").click(function(){
        location.href="aplicacion.php?aplicacion=2&apli_com=1&tipo=3";
    })
    $("#pnovalidados").click(function(){
        location.href="aplicacion.php?aplicacion=2&apli_com=1&tipo=1";
    })
    $("#prechazados").click(function(){
        location.href="aplicacion.php?aplicacion=2&apli_com=1&tipo=2";
    })
 
    $("#avalidados").click(function(){
        location.href="aplicacion.php?aplicacion=4&apli_com=3&tipo=3";
    })
    $("#anovalidados").click(function(){
        location.href="aplicacion.php?aplicacion=4&apli_com=3&tipo=1";
    })
    $("#arechazados").click(function(){
        location.href="aplicacion.php?aplicacion=4&apli_com=3&tipo=2";
    })
         
    $("#pProyectoAdmin").click(function(){
        random     = $("#randomAP").val();
        idarea     = $("#idarea").val();
        idprograma = $("#idprograma").val();
        if( (parseInt(idarea) != 0) && (String(idprograma) != "") )
        {
            $.post("ajax/temporal.php",{areaId:idarea,programaId:idprograma,random:random,opcion:1},function(data){
                $("#resultadoModalAdmin").html(data);
                $("#NmAreas").html(data);
            });
        }
        return false;
    });
     
    //aprobacion de avances
    $(".aprobadosavance").click(function(){
        id    = $(this).attr('id');
        array_tmp=id.split('-');
        idtrimestre  = array_tmp[3];
        idproyecto  = array_tmp[2];
        idactividad = array_tmp[1];
        if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0) ){          
            random= Math.round(Math.random() * 1000);
            /*bootbox.confirm("<br><br><b>&iquest;Desea aprobar la actividad del avance?</b>", function(result) {
                if(result)
                {*/        
                    $.post("ajax/validaciones.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,idtrimestre:idtrimestre,aprobado:1,opc:3},function(buffer){
                        if(parseInt(buffer)>0){
                            $(".alert").html("Se han aprobado las actividades");
                            $(".alert").show();
                            setTimeout(function(){$(".alert").hide();},2000);
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=3&folio="+idproyecto+"-2"},300);
                        }
                    });
                /*}
            });*/
        }
    });
    //aprobacion de avances
    $(".aprobadosProyAvan").click(function(){
        id    = $(this).attr('id');
        array_tmp=id.split('-');
        idproyecto  = array_tmp[1];
        idestatus   = array_tmp[2];
        idtrimestre = array_tmp[3];
        if( (parseInt(idproyecto) > 0) && (parseInt(idestatus) > 0) && (parseInt(idtrimestre)>0) ){            
            random= Math.round(Math.random() * 1000);
            /*bootbox.confirm("<br><br><b>&iquest;Desea aprobar el avance del proyecto?</b>", function(result) {
                if(result)
                {*/        
                    $.post("ajax/validaciones.php",{random:random,idproyecto:idproyecto,idactividad:0,idestatus:idestatus,idtrimestre:idtrimestre,aprobado:1,opc:6},function(buffer){
                        if(parseInt(buffer)>0){
                            $(".alert").html("Se ha aprobado el proyecto");
                            $(".alert").show();
                            setTimeout(function(){$(".alert").hide();},2000);
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()},300);
                        }
                    });
                    return false;
                /*}
            });*/
        }
        return false;
    });
     
    //aprobacion de proyectos
    $(".aprobadosProy").click(function(){
        id    = $(this).attr('id');
        array_tmp=id.split('-');
        idproyecto  = array_tmp[1];
        idestatus = array_tmp[2];
        if( (parseInt(idproyecto) > 0) && (parseInt(idestatus) > 0) ){            
            random= Math.round(Math.random() * 1000);
            /*bootbox.confirm("<br><br><b>&iquest;Desea aprobar el proyecto?</b>", function(result) {
                if(result)
                {*/        
                    $.post("ajax/validaciones.php",{random:random,idproyecto:idproyecto,idactividad:0,idestatus:idestatus,idtrimestre:0,aprobado:1,opc:4},function(buffer){
                        if(parseInt(buffer)>0){
                            $(".alert").html("Se ha aprobado el proyecto");
                            $(".alert").show();
                            setTimeout(function(){$(".alert").hide();},2000);
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()},300);
                        }
                    });
                /*}
            });*/
        }
        return false;
    });
     
    //No aprobacion de proyectos
    $(".noaprobadosProyAvan").click(function(){ 
        $("#resultado").css({ color: "#ffffff", background: "#FFFFFF" });
        $("#resultado").html("");
        id    = $(this).attr('id');
        array_tmp=id.split('-');
        idproyecto = array_tmp[1];
        idestatus  = array_tmp[2];
        idtrimestre = array_tmp[3];
        $("#comentarios").html("");
        if( (parseInt(idproyecto) > 0) && (parseInt(idestatus) > 0)   ){
            random= Math.round(Math.random() * 1000);
            $("#idProyectoV").val(idproyecto);
            $("#idActividadV").val(0);
            $("#idTrimestreV").val(idtrimestre);
            $("#randomV").val(random);
            $.post("ajax/comentariosM.php",{random:random,idproyecto:idproyecto,idactividad:0,idestatus:idestatus,idtrimestre:idtrimestre,opc:4},function(buffer){
                $("#summernote").code(buffer);
                $('#myModalValida').modal('show');
            })
        }
        return false;
    }); 
         
     
    //No aprobacion de proyectos
    $(".noaprobadosProy").click(function(){ 
        $("#resultado").css({ color: "#ffffff", background: "#FFFFFF" });
        $("#resultado").html("");
        id    = $(this).attr('id');
        array_tmp=id.split('-');
        idproyecto = array_tmp[1];
        idestatus  = array_tmp[2];
        $("#comentarios").html("");
        if( (parseInt(idproyecto) > 0) && (parseInt(idestatus) > 0)   ){
            random= Math.round(Math.random() * 1000);
            $("#idProyectoV").val(idproyecto);
            $("#idActividadV").val(0);
            $("#idTrimestreV").val(0);
            $("#randomV").val(random);
            $.post("ajax/comentariosM.php",{random:random,idproyecto:idproyecto,idactividad:0,idestatus:idestatus,idtrimestre:idtrimestre,opc:4},function(buffer){
                $("#summernote").code(buffer);
                $('#myModalValida').modal('show');
            })
        }
        return false;
    }); 
     
     
    //proyectos y actividades aprobadas
    $(".aprobados").click(function(){
        id    = $(this).attr('id');
        array_tmp=id.split('-');
        idproyecto  = array_tmp[2];
        idactividad = array_tmp[1];
        if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0) ){          
            random= Math.round(Math.random() * 1000);
            /*bootbox.confirm("<br><br><b>&iquest;Desea aprobar la actividad?</b>", function(result) {
                if(result)
                {*/        
                    $.post("ajax/validaciones.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,idtrimestre:0,aprobado:1,opc:3},function(buffer){
                        if(parseInt(buffer)>0){
                            $(".alert").html("Se han aprobado las actividades");
                            $(".alert").show();
                            setTimeout(function(){$(".alert").hide();},2000);
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=3&folio="+idproyecto+"-2"},300);
                        }
                    });
                /*}
            });*/
        }
    });
     
    //boton para aprobar avances por actividad y trimestre
    $(".aprobadosavances").click(function(){            
        id    = $(this).attr('id');
        array_tmp=id.split('-');
        idproyecto  = array_tmp[2];
        idactividad = array_tmp[1];
        idtrimestre = array_tmp[3];
         
        if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0) && (parseInt(idtrimestre)>0) ){          
            random= Math.round(Math.random() * 1000);
            /*bootbox.confirm("<br><br><b>&iquest;Desea aprobar el avance de la actividad?</b>", function(result) {
                if(result)
                {*/        
                    $.post("ajax/validaciones.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,idtrimestre:idtrimestre,aprobado:1,opc:3},function(buffer){
                        if(parseInt(buffer)>0){
                            $(".alert").html("Se han aprobado el avance de la actividad.");
                            $(".alert").show();
                            setTimeout(function(){$(".alert").hide();},2000);
                            if(parseInt($("#aplicacion").val()!= 4))
                                setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=3&folio="+idproyecto+"-2"},300);
                            else
                                setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=9&folio="+idproyecto},300);
                        }
                    });
                /*}
            });*/
        }       
    });
 
     
    //boton para no aprobar avances por trimestre
    $(".noaprobadosavances").click(function(){          
        $("#resultado").css({ color: "#ffffff", background: "#FFFFFF" });
        $("#resultado").html("");
        id    = $(this).attr('id');
        array_tmp=id.split('-');
        idproyecto  = array_tmp[2];
        idactividad = array_tmp[1];
        idtrimestre = array_tmp[3];
        $("#comentarios").html("");
        if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0)     ){
            random= Math.round(Math.random() * 1000);
            $("#idProyectoV").val(idproyecto);
            $("#idActividadV").val(idactividad);
            $("#idTrimestreV").val(idtrimestre);
            $("#randomV").val(random);
            if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0) ){
                $.post("ajax/comentariosavancesaprobacion.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,idtrimestre:idtrimestre,opc:1},function(buffer){
                    $("#summernoteV").code(buffer);
                    $('#myModalValida').modal('show');
                })
            }
        }
    });
 
     
    //boton para no aprobar proyectos   
    $(".noaprobados").click(function(){         
        $("#resultado").css({ color: "#ffffff", background: "#FFFFFF" });
        $("#resultado").html("");
        id    = $(this).attr('id');
        array_tmp=id.split('-');
        idproyecto  = array_tmp[2];
        idactividad = array_tmp[1];
        idtrimestre = array_tmp[3];
        $("#comentarios").html("");
        if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0)     ){
            random= Math.round(Math.random() * 1000);
            $("#idProyectoV").val(idproyecto);
            $("#idActividadV").val(idactividad);
            $("#idTrimestreV").val(0);
            $("#randomV").val(random);
            if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) > 0) ){
                $.post("ajax/comentarios.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,idtrimestre:idtrimestre,opc:1},function(buffer){
                    $("#summernote").code(buffer);
                    $('#myModalValida').modal('show');
                })
            }
        }
    });
 
    $("#validaAdmin").click(function(){
        $("#res").css({ color: "#ffffff", background: "#ffffff" });
        userId    = $("#userId").val();
        aplicacion= $("#aplicacion").val();
        apli_com  = $("#apli_com").val();
        contador=0;
        encoordinacion="";
        $(".validaPorAdmin").each(function(){
            if($(this).is(':checked')){
                encoordinacion+=$(this).attr('id')+"|";
                contador++;
            }
        });
        if(parseInt(contador) > 0 ){
            bootbox.confirm("<br><br><b>&iquest;Desea validar "+contador+" actividades?</b>", function(result) {
                if(result){
                    random= Math.round(Math.random() * 1000);
                    $.post("ajax/validaciones.php",{random:random,idtrimestre:0,proyectosIds:encoordinacion,opc:5},function(bufferAct){
                        $("#res").html("Se han cambiado de estatus los proyectos");
                        setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()},300);
                    }); 
                }
            });
        }
        else{           
            $("#res").css({ color: "#ff0000", background: "#ffffff" });
            $("#res").html("Por favor seleccione al menos un proyecto");
        }
    });
     
    $(".enviaCoordinador").click(function(){
        $("#resultado").css({ color: "#ffffff", background: "#FFFFFF" });
        $("#resultado").html("");
        id    = $(this).attr('id');
        array_tmp=id.split('-');
        idproyecto  = array_tmp[1];
        idestatus   = array_tmp[2];
        idtrimestre = array_tmp[3];
        if( (parseInt(idproyecto) > 0) && (parseInt(idestatus) > 0)){
            bootbox.confirm("<br><br><b>&iquest;Desea enviar el proyecto al Coordinador?</b>", function(result) {
                if(result){
                    random= Math.round(Math.random() * 1000);
                    $.post("ajax/regresaproyecto.php",{random:random,idproyecto:idproyecto,idestatus:idestatus,idtrimestre:idtrimestre,opc:1},function(bufferAct){
                        $("#res").html("Se han cambiado de estatus los proyectos");
                        //setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()},300);
                    }); 
                }
            });         
        }
    });
     
    $(".enviaEnlacePlaneacion").click(function(){
        $("#resultado").css({ color: "#ffffff", background: "#FFFFFF" });
        $("#resultado").html("");
        id    = $(this).attr('id');
        array_tmp=id.split('-');
        idproyecto  = array_tmp[1];
        idestatus = array_tmp[2];
        idtrimestre = array_tmp[3];
        if( (parseInt(idproyecto) > 0) && (parseInt(idestatus) > 0)){
            bootbox.confirm("<br><br><b>&iquest;Desea enviar el proyecto al Enlace de Planeaci&oacute;n?</b>", function(result) {
                if(result){
                    random= Math.round(Math.random() * 1000);
                    $.post("ajax/regresaproyecto.php",{random:random,idproyecto:idproyecto,idestatus:idestatus,idtrimestre:idtrimestre,opc:2},function(bufferAct){
                        $("#res").html("Se han cambiado de estatus los proyectos");
                        //setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()},300);
                    }); 
                }
            });         
        }       
    });
 
    $("#rol").change(function(){
        if(parseInt($("#rol").val())!=1)
            $("#idunidadoperativa").val(0);
    });
     
    $(".verComentariosNoAprobados").click(function(){
        $("#btncomentario").hide();
        array_tmp=$(this).attr('id').split('-');        
        idproyecto  = array_tmp[2]; 
        idactividad = array_tmp[1];
        idtrimestre = array_tmp[3];
        $("#comentarios").html("");
        $("#resultado").html("");
        $("#fileToUpload").html("");
        $("#downloadFiles").html("");
        if( (parseInt(idproyecto) > 0) ){
            random= Math.round(Math.random() * 1000);
            $("#idProyecto").val(idproyecto);
            $("#idActividad").val(idactividad);
            $("#idTrimestre").val(idtrimestre);
            $("#random").val(random);
            if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) >= 0) ){
                $.post("ajax/comentariosavancesaprobacion.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,idtrimestre:idtrimestre,opc:1},function(buffer){
                     $("#summernoteVis").code(buffer);
                     $('#myModalVisualiza').modal('show');
                })
            }
        }
    });
     
    $(".verComentarios").click(function(){
        $("#btncomentario").hide();
        array_tmp=$(this).attr('id').split('-');        
        idproyecto  = array_tmp[2]; 
        idactividad = array_tmp[1];
        idtrimestre = array_tmp[4];
        $("#comentarios").html("");
        $("#resultado").html("");
        $("#fileToUpload").html("");
        $("#downloadFiles").html("");
        if( (parseInt(idproyecto) > 0) ){
            random= Math.round(Math.random() * 1000);
            $("#idProyecto").val(idproyecto);
            $("#idActividad").val(idactividad);
            $("#idTrimestre").val(idtrimestre);
            $("#random").val(random);
            if( (parseInt(idproyecto) > 0) && (parseInt(idactividad) >= 0) ){
                $.post("ajax/comentarios.php",{random:random,idproyecto:idproyecto,idactividad:idactividad,idtrimestre:0,opc:1},function(buffer){
                     $("#summernoteVis").code(buffer);
                     $('#myModalVisualiza').modal('show');
                     /*$.post("ajax/doajaxfileupload.php",{random:random,idProyecto:idproyecto,idActividad:idactividad,idTrimestre:idtrimestre,opc:2},function(bufferFiles){
                         $("#downloadFiles").html(bufferFiles);
                         $('#myModalVisualiza').modal('show');
                     });*/
                })
            }
        }
    });
     
    /**** Empieza metodos para bitacoras ******/
    $("#tipo_log").change(function(){
       if(parseInt($("#tipo_log").val()) > 0){
            $.get(url,{tipoLogId:$("#tipo_log").val(),opcion:12},function(data){
                $("#tipo_status").html(data);
            })         
       } 
    });
     
    $("#consultarBitacora").click(function(){
        if(parseInt($("#tipo_log").val()) == 0){
        $("#error").html("Favor de seleccionar un tipo de bitacora");
        return false;
        }
    });
     
    /****** Empieza metodos para ayudas del sistema ******/
    $(".actualizaAyuda").click(function(){
        array_tmp=$(this).attr('id').split('-');
        opc = array_tmp[0];
        id  = array_tmp[1];
        if(parseInt(id) > 0 && parseInt(opc) > 0){
        $("#id").val(id);
        $("#opcion").val(opc);
        $("#titulo").val($("#"+opc+"t-"+id).html());
        $("#content").val($("#"+opc+"c-"+id).html());
        $('#myModalAyudas').modal('show');
        return false;
        }
    });
     
    $("#btnayuda").click(function(){        
        opc = $("#opcion").val();       
       if((parseInt($("#opcion").val()) > 0) && (parseInt($("#id").val()) > 0) && (String($("#titulo").val())!= "") && (String($("#content").val())!= "")  ){
           $("#"+opc+"r-"+id).css({ color: "#ffffff", background: "#ffffff" });
           $.post(urlCatalogo,{id:$("#id").val(),tipo: $("#opcion").val(),titulo:$("#titulo").val(),  content:$("#content").val(),  opcion:5},function(data){
           $('#myModalAyudas').modal('hide');
           $("#"+opc+"t-"+id).html($("#titulo").val());
           $("#"+opc+"c-"+id).html($("#content").val());
           $("#"+opc+"r-"+id).html("Se han guardado los cambios");  
           $("#"+opc+"r-"+id).css({ color: "#006600", background: "#ffffff" });
           setTimeout(function(){$("#"+opc+"r-"+id).html("");},2000);
            })
       }        
    })
     
    /******* Empieza los metodos para los estilos   *****/
    $(".seleccionaEstilos").click(function(){
        array_tmp=$(this).attr('id').split('-');
        opc = array_tmp[1];
        id  = array_tmp[0];
        if(parseInt(id) > 0 ){
        random= Math.round(Math.random() * 1000);
        $.post(urlEstilo,{id:id,style:opc,random:random,opcion:1},function(data){
            $("#res"+id).html("Se han guardado los cambios");
            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()},200);
        })
        }
    });
     
    /*****Aignacion de proyectos ****/
    $("#asignaProyecto").click(function(){
         $("#resAsignacion").html("");
        encoordinacion="";
        $(".asignacion").each(function(){
            if($(this).is(':checked')){
                encoordinacion+=$(this).val()+"|";
            }
        });
        if(String(encoordinacion) == ""){
            $("#resAsignacion").html("Por favor seleccione al menos un proyecto");
            return false;
        }
        if(parseInt( $("#idUsuario").val())  <= 0 ){
            $("#resAsignacion").html("Por favor seleccione un usuario");
            return false;       
        }
        $.post(urlAsignacion,{idUsuario:$("#idUsuario").val(),proyectos:encoordinacion,random:random,opc:1},function(data){
            $("#resAsignacion").html("Se han guardado los cambios");
            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()},200);            
        })
         
    });
     
     
     
    /************************** eventos del modulo de estadisticas ****************************/
     
    $("#crearTablaTotal").click(function(){
        var f   = new Date();
        var fin = strPad(String(f.getDate()),2,0)+strPad(String((f.getMonth() + 1)),2,0)+String(f.getFullYear());
        $("#resultado").css({ color: "#ffffff", background: "#ffffff" });
        $("#resultado").html("");
        //var escapar = "\'\'";
        url ="ajax/adminTablas.php";
        inputNombre = "Vista01012015_"+fin;
        $("#inputNombre").val(inputNombre);
        if( (String($("#fechaLimiteIni").val()) !== "") && (String($("#fechaLimiteFin").val()) !== "") && (String($("#inputNombre").val()) !== "")){
            bootbox.confirm("<br><br><b>&iquest;Desea generar la tabla temporal: "+inputNombre+"?</b>", function(result) {
                if(result){
                    random= Math.round(Math.random() * 1000);
                    $.post(url,{random:random,tabla:inputNombre,opc:2},function(buffer){
                        if(parseInt(buffer) > 0){                            
                            $("#resultado").css({ color: "#006600", background: "#ffffff" });
                            $("#resultado").html("Se ha almacenado el folio: "+buffer+".");
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},300);   
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
        }else{
            $("#resultado").html("Los campos son obligatorios");
        }
    }); 
     
    $("#crearTabla").click(function(){
        $("#resultado").css({ color: "#ffffff", background: "#ffffff" });
        $("#resultado").html("");
        $("#procesando").hide();
        $("#t_procesando").hide();
        url ="ajax/adminTablas.php";
        inputNombre = "View_"+check_date($("#fechaLimiteIni").val(),numsValor)+"_"+check_date($("#fechaLimiteFin").val(),numsValor);
        $("#inputNombre").val(inputNombre);
        if( (String($("#fechaLimiteIni").val()) !== "") && (String($("#fechaLimiteFin").val()) !== "") && (String($("#inputNombre").val()) !== "")){
            bootbox.confirm("<br><br><b>&iquest;Desea generar la tabla temporal: "+inputNombre+"?</b>", function(result) {
                if(result){
                    $("#procesando").show();
                    $("#t_procesando").show();
                    random= Math.round(Math.random() * 1000);
                    $.post(url,{random:random,tabla:inputNombre,opc:2},function(buffer){
                        $("#procesando").hide();
                        $("#t_procesando").hide();

                        if(parseInt(buffer) > 0){                            
                            $("#resultado").css({ color: "#006600", background: "#ffffff" });
                            $("#resultado").html("Se ha almacenado el folio: "+buffer+".");
                            setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},300);   
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
        }else{
            $("#resultado").html("Los campos son obligatorios");
        }
    }); 
    /*******tab para actualizar  *******/
    $('#myTabs a').click(function (e) {
          e.preventDefault()
          $(this).tab('show')
    });
     
    $(document).on('click','.actualizaProyectos', function (){    	
        tableId = $("#tableId").val();
        array_tmp=$(this).attr('id').split('-');
        url ="ajax/regresaDatosTmp.php";
        opc = array_tmp[0];
        id  = array_tmp[1];
        if(parseInt(id) > 0 && parseInt(tableId) > 0){
            random= Math.round(Math.random() * 1000);
            window.open("registro.php?idTable="+tableId+"&id="+id,"registros","toolbar=0,location=0,menubar=0,resizable=0,scrollbars=1,status=0,titlebar=0");
            return false;
        }
    });
          
    $(document).on('click','.day', function (){
        $(".datepicker").hide();
    });
     
 
     
    var fecha_ini;
    var fecha_fin;
    var f1;
    var f2;
    $(document).on('change','#idTrimestre', function (){
        if( (parseInt($("#idTrimestre").val()) > 0) && (parseInt($("#idano").val()) > 0)){
            switch(parseInt($("#idTrimestre").val())){
                case 1:
                    $("#fechaLimiteIni").val("01/01/"+$("#idano").val());
                    $("#fechaLimiteFin").val("31/03/"+$("#idano").val());                   
                    break;
                case 2:
                    $("#fechaLimiteIni").val("01/04/"+$("#idano").val());
                    $("#fechaLimiteFin").val("30/06/"+$("#idano").val());                                       
                    break;
                case 3:
                    $("#fechaLimiteIni").val("01/07/"+$("#idano").val());
                    $("#fechaLimiteFin").val("30/09/"+$("#idano").val());                                       
                    break;
                case 4:
                    $("#fechaLimiteIni").val("01/10/"+$("#idano").val());
                    $("#fechaLimiteFin").val("31/12/"+$("#idano").val());                                       
                    break;
                default:
                    $("#fechaLimiteIni").val("");
                    $("#fechaLimiteFin").val("");
                    break;                  
            }
        }else{
            $("#fechaLimiteIni").val("");
            $("#fechaLimiteFin").val("");           
        }
    });
     
     
    $(document).on('change','#fechaLimiteIni', function (){   
        fecha_ini = $("#fechaLimiteIni").val();
        fecha_fin = $("#fechaLimiteFin").val();
        if (String(fecha_fin) !== "") {
            f1 = new Date(fecha_ini.substring(6, 10), fecha_ini.substring(3, 5), fecha_ini.substring(0, 2));
            f2 = new Date(fecha_fin.substring(6, 10), fecha_fin.substring(3, 5), fecha_fin.substring(0, 2));
            if (f1 > f2) {
                $("#fechaLimiteFin").val(fecha_ini);
            }
        }
    });
 
     
    $(document).on('change','#fechaLimiteFin', function (){
        fecha_ini = $("#fechaLimiteIni").val();
        fecha_fin = $("#fechaLimiteFin").val();
        if (String(fecha_ini) !== "") {
            f1 = new Date(fecha_ini.substring(6, 10), fecha_ini.substring(3, 5), fecha_ini.substring(0, 2));
            f2 = new Date(fecha_fin.substring(6, 10), fecha_fin.substring(3, 5), fecha_fin.substring(0, 2));
            if (f2 < f1) {
                $("#fechaLimiteFin").val(fecha_ini);
            }
        }
    });
 
    
    /***boton para validar el el trimestre que se va a validar  ****/
    $(document).on('click','#trimestreValidar', function (){
    	idtrimestre = $("#idtrimestre").val();
    	ano_id      = $("#idano").val();    	
    	if( parseInt(ano_id) > 0  && parseInt(idtrimestre) > 0) {
    		 bootbox.confirm("<br><br><b>&iquest;Desea validar el "+idtrimestre+" trimestre del a&ntilde;o  "+ano_id+"?</b>", function(result) {
                 if(result){          
                	 random= Math.round(Math.random() * 1000);
                     $.post("ajax/permisos.php",{random:random,idano:ano_id,idtrimestre:idtrimestre,opc:6},function(buffer){
                         if(parseInt(buffer) > 0){  
                        	 $("#resultTrimestre").html("Se ha guardado el trimestre a validar");
                        	 setTimeout(function(){$("#resultTrimestre").html("")},1600);
                         }
                     });
                 }
              });
    	}
    	return false;
    });
    
    
    /****metodo para cambiar la vista defautl   *****/
    $(document).on('click','.default', function (){
    	$("#respVista").css({ color: "#ffffff", background: "#ffffff" });
    	array_tmp=$(this).attr('id').split('-');
        id  = array_tmp[2];
    	if(parseInt(id)> 0){
    		 bootbox.confirm("<br><br><b>&iquest;Desear asignar la vista como tabla predeterminada?</b>", function(result) {
                 if(result){      
                	 random= Math.round(Math.random() * 1000);
                     $.post("ajax/vista.php",{random:random,id:id,opc:1},function(buffer){
                    	 $("#respVista").css({ color: "#006600", background: "#ffffff" });
                    	 $("#respVista").html("Se ha cambiado la vista predeterminada");                    	 
                    	 setTimeout(function(){location.href="aplicacion.php?aplicacion="+$("#aplicacion").val()+"&apli_com="+$("#apli_com").val()+"&opc=0"},500);
                     });
                 }
    		 });
    	}
    	return false;
    });

    //actualizacion de datos
    $("#aidarea").change(function(){
        userId    = $("#userId").val();
        aplicacion= $("#aplicacion").val();
        apli_com  = $("#apli_com").val();
        idarea    = $("#aidarea").val();
        if(parseInt(idarea) != 0){
            $("#btn-5").show();
            $.get(url,{areaId:idarea,opcion:1},function(data){
                $("#idprograma").html(data);
            })
            $.get(url,{areaId:idarea,opcion:6},function(data){$("#idunidadoperativa").html(data);})
        }
        else
        {
            $("#btn-5").hide();
            $("#idprograma").html('<option value="" selected="selected">Programa</option>');
        }
    });
 
    
    /****guardar datos de vista proyecto***/
    $(document).on('click','#actualizadataTmp', function (){
        $("#procesando").hide();
        $("#t_procesando").hide();
        $("#exito").hide();
        $("#fallo").hide();
    	var inputNombreAct;
    	var ponderacion;
    	var presupuestoc_1;
    	var estimadoc_1;
    	var idTable;
    	url 		  = "ajax/registraActualizacion.php";
    	idTable   	  = $("#idTable").val();
    	idProyectoTmp = $("#idProyectoTmp").val();
    	if(parseInt(idProyectoTmp) > 0 && parseInt(idTable) > 0){
    		$("#div_procesando").show();
    		bootbox.confirm("<br><br><b>&iquest;Desear actualizar la informaci&oacute;n del proyecto?</b>", function(result) {
                if(result){
                    $("#procesando").show();
                    $("#t_procesando").show();                	
                	inputNombreAct = $("#inputNombreAct").val();
                	ponderacion    = 0;
                    if($("#ponderacionc5").is(':checked')){
                        ponderacion=5;
                    }
                    if($("#ponderacionc4").is(':checked')){
                        ponderacion=4;
                    }
                    if($("#ponderacionc3").is(':checked')){
                        ponderacion=3;
                    }
                    if($("#ponderacionc2").is(':checked')){
                        ponderacion=2;
                    }
                    if($("#ponderacionc1").is(':checked')){
                        ponderacion=1;
                    }     
                    random= Math.round(Math.random() * 1000);
                	presupuestoc_1 = $("#presupuestoc_1").val();
                	estimadoc_1 = $("#estimadoc_1").val();
                	$.post(url,{random:random,idTable:idTable,id:idProyectoTmp,proyecto:inputNombreAct,ponderacion:ponderacion,presupuesto:presupuestoc_1,estimado:estimadoc_1,opc:2},function(buffer){                		
                		$("#nom").html(inputNombreAct);
                        $("#procesando").hide();
                        $("#t_procesando").hide();
                		if(parseInt(buffer) == 1){
                			$("#exito").html("Se ha actualizado el proyecto");
                	        $("#exito").show();
                		}else{
                			$("#exito").html("Error al actualizar el proyecto");
                	        $("#fallo").show();
                		}
                		setTimeout(function(){$("#exito").hide();$("#fallo").hide();},1500);
                	});        	
                }
    		});
    	}
    	return false;
    });

    /** elimina proyecto de vista  **/
    $(document).on('click','.eliminarProyectos', function (){
        array_tmp=$(this).attr('id').split('-');
        opc = array_tmp[0];
        id  = array_tmp[1];
        tableId = $("#idTabla").val();         
        if(parseInt(id) > 0  && parseInt(tableId) > 0){
            bootbox.confirm("<br><br><b>&iquest;Desea eliminar el registro de la vista?</b>", function(result) {
               if(result){
            	   url    = "ajax/registraActualizacion.php";
	               random  = Math.round(Math.random() * 1000);
	               	$.post(url,{random:random,idTable:tableId,id:id,opc:4},function(buffer){
	               		if(parseInt(buffer) === 1){
	               			$("#r-"+id).remove();
	               		}	               		
	               	});
               	}               
            });
        }
       return false;
   }); 

/****elimina una actividad de la vista  **/
    $(document).on('click','.deleteActAct', function (){
    	var idActividad 
        array_tmp=$(this).attr('id').split('-');
        id  		  = array_tmp[1];
        idActividad   = id;
        url 		  = "ajax/registraActualizacion.php";
    	idTable   	  = $("#idTable").val();       
    	idProyectoTmp = $("#idProyectoTmp").val();
    	if(parseInt(idProyectoTmp) > 0  && parseInt(idActividad) >0 && parseInt(idTable) > 0){
    		bootbox.confirm("<br><br><b>&iquest;Desear eliminar la informaci&oacute;n de la actividad?</b>", function(result) {
                if(result){
                    url    = "ajax/registraActualizacion.php";
  	                random  = Math.round(Math.random() * 1000);
  	               	$.post(url,{random:random,idTable:idTable,id:idProyectoTmp,idActividad:idActividad,opc:5},function(buffer){
  	               		if(parseInt(buffer) === 1){
  	               			setTimeout(function(){location.href="registro.php?idTable="+idTable+"&id="+idProyectoTmp},500);
  	               		}	               		
  	               	});
                }
    		});
    	}
    	return false;
    });
    
    /****guardar datos de vista actividad***/
    $(document).on('click','.guardaActAct', function (){
        $("#procesando").hide();
        $("#t_procesando").hide();
        $("#exito").hide();
        $("#fallo").hide();    	
        array_tmp=$(this).attr('id').split('-');
        id  = array_tmp[1];
        var idActividad = id;
        var idTable;
        var vTrimestre1;
        var vTrimestre2;
        var vTrimestre3;
        var vTrimestre4;
        var aTrimestre1;
        var aTrimestre2;
        var aTrimestre3;
        var aTrimestre4;        
        var actividad;
        var medida;
        var ponderacion;
        var tipoActividad;
        var comenT1;
        var comenT2;
        var comenT3;
        var comenT4;        
    	url 		  = "ajax/registraActualizacion.php";
    	idTable   	  = $("#idTable").val();       
    	idProyectoTmp = $("#idProyectoTmp").val();
    	if(parseInt(idProyectoTmp) > 0  && parseInt(idActividad) >0 && parseInt(idTable) > 0){
    		bootbox.confirm("<br><br><b>&iquest;Desear actualizar la informaci&oacute;n de la actividad?</b>", function(result) {
                if(result){
                	$("#procesando").show();
                    $("#t_procesando").show();
                	vTrimestre1  = $("#m1-"+idActividad).val();
                    vTrimestre2  = $("#m2-"+idActividad).val();
                    vTrimestre3  = $("#m3-"+idActividad).val();
                    vTrimestre4  = $("#m4-"+idActividad).val();
                	aTrimestre1  = $("#a1-"+idActividad).val();
                    aTrimestre2  = $("#a2-"+idActividad).val();
                    aTrimestre3  = $("#a3-"+idActividad).val();
                    aTrimestre4  = $("#a4-"+idActividad).val();
                    actividad    = $("#nm-"+idActividad).val();
                    medida  	 = $("#me-"+idActividad).val();
                    ponderacion  = $("#po-"+idActividad).val();
                    tipoActividad= $("#ti-"+idActividad).val();
                    comenT1  	 = $("#comen1-"+idActividad).val();
                    comenT2  	 = $("#comen2-"+idActividad).val();
                    comenT3  	 = $("#comen3-"+idActividad).val();
                    comenT4  	 = $("#comen4-"+idActividad).val();    
                    random= Math.round(Math.random() * 1000);
                    $.post(url,{random:random,idTable:idTable,id:idProyectoTmp,idActividad:idActividad,
                    	actividad:actividad,ponderacion:ponderacion,medida:medida,tipoActividad:tipoActividad,
                    	v1:vTrimestre1,v2:vTrimestre2,v3:vTrimestre3,v4:vTrimestre4,
                    	a1:aTrimestre1,a2:aTrimestre2,a3:aTrimestre3,a4:aTrimestre4,
                    	c1:comenT1,c2:comenT2,c3:comenT3,c4:comenT4,opc:3},function(buffer){                    		
                    	$("#nmAct").html(actividad);
                        $("#procesando").hide();
                        $("#t_procesando").hide();
                		if(parseInt(buffer) == 1){
                			$("#exito").html("Se ha actualizado la actividad");
                	        $("#exito").show();
                		}else{
                			$("#exito").html("Error al actualizar la actividad");
                	        $("#fallo").show();
                		}
                		setTimeout(function(){$("#exito").hide();$("#fallo").hide();},1500);
                	}); 
                }
    		});
    	}
    	return false;
    });

    //fin del jquery
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
            //$("#"+id).html("El tama&ntilde;o m&iacute;nimo para este campo es de "+min_size+" caracteres.");
            $("#resultado").html("El tama&ntilde;o m&iacute;nimo para este campo es de "+min_size+" caracteres.");
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
    }else{
        resultado+=".00"
    }
    if(numero[0]=="-") {
        return "-"+resultado;
    }
    else{
            return resultado;
    }
}
 
function check_date(cadena, chars){
    var array=new Array();
    var s = "";
    var j = 0;
    for (i = 0; i < cadena.length; i++){
        if (chars.indexOf(cadena.charAt(i)) != -1){
            s = s + cadena.charAt(i);
        }
        else j++;
    }
    cadena = s;  
    return cadena;
}
function strPad(input, length, string) {
    string = string || '0'; input = input + '';
    return input.length >= length ? input : new Array(length - input.length + 1).join(string) + input;
}

function utf8_encode(argString) {
	if (argString === null || typeof argString === 'undefined') {
		return ''
	}
	var string = (argString + '')
	var utftext = ''
	var start
	var end
	var stringl = 0

	start = end = 0
	stringl = string.length
	for (var n = 0; n < stringl; n++) {
		var c1 = string.charCodeAt(n)
		var enc = null

		if (c1 < 128) {
			end++
		} else if (c1 > 127 && c1 < 2048) {
			enc = String.fromCharCode((c1 >> 6) | 192, (c1 & 63) | 128)
		} else if ((c1 & 0xF800) !== 0xD800) {
			enc = String.fromCharCode((c1 >> 12) | 224, ((c1 >> 6) & 63) | 128,
					(c1 & 63) | 128)
		} else {
			// surrogate pairs
			if ((c1 & 0xFC00) !== 0xD800) {
				throw new RangeError('Unmatched trail surrogate at ' + n)
			}
			var c2 = string.charCodeAt(++n)
			if ((c2 & 0xFC00) !== 0xDC00) {
				throw new RangeError('Unmatched lead surrogate at ' + (n - 1))
			}
			c1 = ((c1 & 0x3FF) << 10) + (c2 & 0x3FF) + 0x10000
			enc = String.fromCharCode((c1 >> 18) | 240,
					((c1 >> 12) & 63) | 128, ((c1 >> 6) & 63) | 128,
					(c1 & 63) | 128)
		}
		if (enc !== null) {
			if (end > start) {
				utftext += string.slice(start, end)
			}
			utftext += enc
			start = end = n + 1
		}
	}

	if (end > start) {
		utftext += string.slice(start, stringl)
	}

	return utftext
}

function utf8_decode (strData) { 
	  var tmpArr = []
	  var i = 0
	  var c1 = 0
	  var seqlen = 0

	  strData += ''

	  while (i < strData.length) {
	    c1 = strData.charCodeAt(i) & 0xFF
	    seqlen = 0

	    if (c1 <= 0xBF) {
	      c1 = (c1 & 0x7F)
	      seqlen = 1
	    } else if (c1 <= 0xDF) {
	      c1 = (c1 & 0x1F)
	      seqlen = 2
	    } else if (c1 <= 0xEF) {
	      c1 = (c1 & 0x0F)
	      seqlen = 3
	    } else {
	      c1 = (c1 & 0x07)
	      seqlen = 4
	    }

	    for (var ai = 1; ai < seqlen; ++ai) {
	      c1 = ((c1 << 0x06) | (strData.charCodeAt(ai + i) & 0x3F))
	    }

	    if (seqlen === 4) {
	      c1 -= 0x10000
	      tmpArr.push(String.fromCharCode(0xD800 | ((c1 >> 10) & 0x3FF)))
	      tmpArr.push(String.fromCharCode(0xDC00 | (c1 & 0x3FF)))
	    } else {
	      tmpArr.push(String.fromCharCode(c1))
	    }

	    i += seqlen
	  }

	  return tmpArr.join('')
	}
