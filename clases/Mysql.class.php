<?php
class Mysql {
	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	function __construct($sqlserver, $sqluser, $sqlpassword, $database, $persistency = 3306){
		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
        $this->dbname = $database;
        
		try{
			$this->db_connect_id = mysqli_connect($this->server, $this->user, $this->password, $this->dbname);
			return $this->db_connect_id;
		}
		catch(\Exception $e){
			return false;
		}
	}
	//
	// Other base methods
	//
	function sql_close() {
		if ($this->db_connect_id) {
			if ($this->query_result) {
				@mysqli_free_result($this->query_result);
			}
			$result = @mysqli_close($this->db_connect_id);
			return $result;
		} else {
			return false;
		}
	}
	//
	// Base query method
	//
	function sql_query($query = "", $transaction = FALSE){
		$contadorQuery = 0;
		if($this->query_result){
			$contadorQuery = $this->query_result;
		}
		//unset($contadorQuery);
		if ($query != "") {
			try{
				$this->query_result = @mysqli_query($this->db_connect_id,$query);
			}
			catch(Exception $e){
				die("Error:  ".print_r($e));
			}
			
		}
		if ($this->query_result)
		{
//			unset($this->row[$contadorQuery]);
//			unset($this->rowset[$contadorQuery]);
			return $this->query_result;
		}
		else{
			return false;
		}
	}
	//
	// Other query methods
	//
	function sql_numrows($query_id = 0) {
		if (!$query_id) {
			$query_id = $this->query_result;
		}
		if ($query_id) {
			$result = @mysqli_num_rows($query_id);
			return $result;
		} else {
			return false;
		}
	}
	function sql_affectedrows($query_id = 0) {
		if (!$query_id) {
			$query_id = $this->query_result;
		}
		if ($query_id) {
			$result = @mysqli_affected_rows($query_id);
			return $result;
		} else {
			return false;
		}
	}
	function sql_numfields($query_id = 0) {
		if (!$query_id) {
			$query_id = $this->query_result;
		}
		if ($query_id) {
			$result = @mysqli_num_fields($query_id);
			return $result;
		} else {
			return false;
		}
	}
	function sql_fetchass($result) {
		$contadorQuery = 0;
		if (!$result) {
			$result = $this->query_result;
		}
		if ($result) {
			$contadorQuery = $result;
			try{
				$this->row = @mysqli_fetch_array($result,MYSQLI_ASSOC);				
				return $this->row;
			}
			catch(Exception $e){
				die("Error:  ".print_r($e));
			}
		} else {
			return false;
		}
	}
	function sql_fetchrow($result) {
		$contadorQuery = 0;
		if (!$result) {
			$result = $this->query_result;
		}
		if ($result) {
			//$contadorQuery = $result;
			try{
				$this->row = @mysqli_fetch_array($result,MYSQLI_NUM);
				return $this->row;
			}
			catch(Exception $e){
				die("Error:  ".print_r($e));
			}
		} 
		else{
			return false;
		}
	}
	function sql_fetchrowset($result) {
		$contadorQuery = 0;
		if (!$result) {
			$result = $this->query_result;
		}
		if ($result) {
			$contadorQuery = $result;
			try{
				unset($this->rowset[$contadorQuery]);
				unset($this->row[$contadorQuery]);
				while ($this->rowset[$contadorQuery] = @mysqli_fetch_array($result,MYSQLI_BOTH)) {
					$result[] = $this->rowset[$contadorQuery];
					$contadorQuery++;
				}
				return $result;	
			}
			catch(Exception $e){
				die("Error en base de datos:  ".$e->getMessage());
				return false;
			}
		} else {
			return false;
		}
	}
	function sql_fetchfield($field, $rownum = -1, $query_id = 0) {
		if (!$query_id) {
			$query_id = $this->query_result;
		}
		if ($query_id) {
			if ($rownum > -1) {
				$result = @mysqli_result($query_id, $rownum, $field);
			} else {
				if (empty($this->row[$query_id]) && empty($this->rowset[$query_id])) {
					if ($this->sql_fetchrow()) {
						$result = $this->row[$query_id][$field];
					}
				} else {
					if ($this->rowset[$query_id]) {
						$result = $this->rowset[$query_id][$field];
					} else if ($this->row[$query_id]) {
						$result = $this->row[$query_id][$field];
					}
				}
			}
			return $result;
		} else {
			return false;
		}
	}
	function sql_rowseek($rownum, $query_id = 0) {
		if (!$query_id) {
			$query_id = $this->query_result;
		}
		if ($query_id) {
			$result = @mysqli_data_seek($query_id, $rownum);
			return $result;
		} else {
			return false;
		}
	}
	function sql_nextid() {
		if ($this->db_connect_id) {
			$result = @mysqli_insert_id($this->db_connect_id);
			return $result;
		} else {
			return false;
		}
	}
	function sql_freeresult($query_id = 0) {
		if (!$query_id) {
			$query_id = $this->query_result;
		}
		if ($query_id) {
			unset($this->row[$query_id]);
			unset($this->rowset[$query_id]);
			@mysqli_free_result($query_id);
			return true;
		} else {
			return false;
		}
	}
	function sql_error($query_id = 0) {
		$result["message"] = @mysqli_error($this->db_connect_id);
		$result["code"] = @mysqli_errno($this->db_connect_id);
		return $result;
	}
	
	function url(){
		$caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890=!';
		$length     = 30;
		$rand		= "";
		$i 			= 0;
		while ($i < $length) {
			$num = rand() % strlen($caracteres);
			$tmp = substr($caracteres, $num, 1);
			$rand = $rand . $tmp;
			$i++;
		}
		return $rand;
	}
	
	function debug($data){
		echo"<pre>";print_r($data);die();
	}
}
?>