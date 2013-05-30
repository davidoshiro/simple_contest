<?php 

class DB{
	
	private $mysqli;
	
	private $host = '127.0.0.1';
	private $username = 'root';
	private $password = '';
	private $dbname = 'simple_contest';
	private $port = 3306;
	
	public $errors = array();
	public $resultset = array();
	
	public function __construct(){
		$this->mysqli = new mysqli($this->host, $this->username, $this->password, $this->dbname, $this->port);
		if($this->mysqli->connect_errno > 0){
			$this->errors = $this->mysqli->error_list;
		}
	}
	
	public function disconnect(){
		$this->error = array();
		$this->mysqli->close;
		if($this->mysqli->errno > 0){
			$this->errors = $this->mysqli->error_list;
		}
	}
	
	public function select($table, $cols='*', $join=null, $where=null, $order=null){
		$result = false;
		$this->error = array();
		$query = 'select ' . $cols . ' from ' . $table;
		
		// Check for joins
		if($join != null){
			$query .= ' join ' . $join;
		}
		
		// Check for where
		if($where != null){
			$query .= ' where ' . $where;
		}
		
		// Check for order
		if($order != null){
			$query .= ' order ' . $order;
		}
		$query .= ";";
		
		$result = $this->mysqli->query($query);
		
		if($result){
			$result->data_seek(0);
			while($row = $result->fetch_assoc()){
				$this->resultset[] = $row;
			}
		}else{
			$this->errors = $result->error_list;
		}
	}
	
	public function insert($table, $cols = array(), $vals = array()){
		$result = false;
		$this->error = array();
		
		// Make sure number of columns equals number of values
		if(count($cols) == count($vals)){
			// Auto populate created_at and updated_at'
			$query = 'insert into ' . $table;
			$query .= " (" . implode(',', $cols) . ")";
			$query .= " values ('" . implode("','", $vals) . "')";
			$query .= ";";
			print_r($query);
			
			$result = $this->mysqli->query($query);
			
			if($result === true){
				$this->select($table, '*', 'id=' . $this->insert_id);
			}else{
				$this->errors = $result->error_list;
			}
		}else{
			print_r("Error inserting row. Number of columns do not match number of values.\n");
		}
	}
	
	public function get_resultset(){
		$result = "";
		if(count($this->errors) > 0){
			$result = $this->errors;
		}else{
			$result = $this->resultset;
		}
		return $result;
	}
}

?>