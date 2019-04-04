<?php 

global $Config;
// SECURITY: You must explicitelly enable this "uploader". 
$Config['Enabled'] = false ;

// Path to uploaded files relative to the document root.
$Config['UserFilesPath'] = '/var/www/iis_web/images/' ;

$Config['DeniedExtensions']['File']	= array('php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;
$Config['AllowedExtensions']['File']	= array() ;

$Config['AllowedExtensions']['Image']	= array('jpg','gif','jpeg','png') ;
$Config['DeniedExtensions']['Image']	= array() ;

$Config['AllowedExtensions']['Flash']	= array('swf','fla') ;
$Config['DeniedExtensions']['Flash']	= array() ;

?>
