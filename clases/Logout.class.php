<?php
class Logout
{
  var $db;
  var $session;
  var $server;
  
  function __construct($db,$session,$server){
    $this->db      = $db;
    $this->session = $session;
    $this->server  = $server;
    if($this->session['userId'] > 0){
      $this->CierraSession();
    }
  }
  
  function CierraSession(){
    $fecha=date('Y-m-d H:i:s');
    $insLog="INSERT INTO log_accesos(user_id,estatus,timestamp,ip)
               VALUES ('".$this->session['userId']."','2','".$fecha."','".$this->server['REMOTE_ADDR']."');";
    $this->db->sql_query($insLog) or die ($this->cadena_error);
  }
}
?>