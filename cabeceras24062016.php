<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?=$title?></title>
<!-- Meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="<?=$path_web?>imagenes/favicon.ico" rel="shortcut icon"	type="image/x-icon" />
<!-- Global CSS -->
<link rel="stylesheet" href="<?=$path_css?>bootstrap.min.css">
<link rel="stylesheet" href="<?=$path_css?>font-awesome.css">
<link rel="stylesheet" href="<?=$path_css?>datepicker.css">


<link rel="stylesheet" href="<?=$path_css?>styles<?=$_SESSION['estilo']?>.css">
<link rel="stylesheet" href="<?=$path_css?>bootstrap-combined.min.css">
<link rel="stylesheet" href="<?=$path_css?>font-awesome.min.css" />

<!-- include summernote -->
<link rel="stylesheet" href="<?=$path_css?>summernote.css">


<!-- <link rel="stylesheet" href="<?=$path_css?>ajaxfileupload">-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?=$path_js?>html5shiv.js"></script>
      <script src="<?=$path_js?>respond.min.js"></script>
    <![endif]-->
<!-- Javascript -->
<script type="text/javascript" src="<?=$path_js?>jquery-2.0.0.min.js"></script>
<script type="text/javascript" src="<?=$path_js?>ajaxfileupload.js"></script>
<script type="text/javascript" src="<?=$path_js?>bootstrap.min.js"></script>
<script type="text/javascript" src="<?=$path_js?>bootbox.min.js"></script>
<script type="text/javascript" src="<?=$path_js?>bootstrap-hover-dropdown.min.js"></script>
<script type="text/javascript" src="<?=$path_js?>bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?=$path_js?>summernote.js"></script>
<script type="text/javascript" src="<?=$path_js?>jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="<?=$path_js?>sisec.js"></script>
<script type='text/javascript'>
    var files="";
    var idActividad;
    var idProyecto;
    var idTrimestre;
    var random;
    var urlweb;
    function ajaxFileUpload()
	{
    	idProyecto  = $("#idProyecto").val();
    	idActividad = $("#idActividad").val();
    	idTrimestre = $("#idTrimestre").val();
    	random      = $("#random").val();
    	if(parseInt(idProyecto) > 0){
        	urlweb="ajax/doajaxfileupload.php";
    	}
    	else{
    		urlweb="ajax/doajaxfileuploadM.php";
    	}
    	$('#loading')
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});
		$.ajaxFileUpload({			
					url:urlweb,
					secureuri:false,
					fileElementId:'fileToUpload',
					dataType: 'json',
					data:{name:'logan', id:'id',idProyecto:idProyecto,idActividad:idActividad,idTrimestre:idTrimestre,random:random,opc:1},
					success: function (data, status){
						$("#resultado").css({ color: "#ffffff", background: "#ffffff" });
						$("#resultado").html("");
						if(typeof(data.error) != 'undefined'){
							if(data.error != ''){
								$("#resultado").css({ color: "#ff0000", background: "#ffffff" });							
								$("#resultado").html(data.error);
							}else{
								$("#resultado").css({ color: "#006600", background: "#ffffff" });
								$("#resultado").html(data.msg);
								$("#downloadFiles").html(data.filename);
							}
						}
					},
					error: function (data, status, e){
						$("#resultado").html(e);
					}
			})
		return false;
	}
	</script>
</head>