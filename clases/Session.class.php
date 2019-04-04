<?php
class Session {
    var $db;
    var $sesion;
    var $sesion_generada;
    var $sessionData;
    var $server;    
    function __construct($db,$sessionData,$server) {
        $this->db=$db;
        $this->sesion='';
        $this->server=$server;
        $this->sessionData=$sessionData;
        $this->Consulta_Sesion();
    }
    
    function Genera_Sesion()
    {
        $this->sesion_generada=md5(rand(100000,10000000));        
    }
    
    function Consulta_Sesion()
    {
        if($this->sessionData['userId']>0)
        {
            $this->sesion=$this->sessionData['session'];
            $fecha_i=date('Y-m-d')." 00:01:01";
            $fecha_c=date('Y-m-d')." 23:59:59";
            $sql="SELECT id FROM sessiones WHERE session='".$this->sessionData['session']."' AND timestamp BETWEEN '".$fecha_i."' AND '".$fecha_c."' LIMIT 1;";
            $res = $this->db->sql_query($sql) or die ("Error:   ".$sql);
            if($this->db->sql_numrows($res) == 0){
                $this->Inserta_Sesion();
            }
        }
        else{
            $this->Genera_Sesion();
            $this->sesion=$this->sesion_generada;
        }
    }
    
    function Inserta_Sesion()
    {
        $fecha=date('Y-m-d H:i:s');
        $ins="INSERT INTO sessiones(session_user,session,timestamp,session_ip) VALUES ('".$this->sessionData['userId']."','".$this->sesion."','".$fecha."','".$this->server['REMOTE_ADDR']."');";
        $res_ins = $this->db->sql_query($ins) or die ("Error:   ".$ins);
    }
    function Obten_Sesion()
    {
        return $this->sesion;
    }
}

?>