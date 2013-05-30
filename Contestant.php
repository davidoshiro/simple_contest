<?php
	require_once("DB.php");
	
	class Contestant{
		public $id;
		public $contest_id;
		public $name;
		public $email;
		public $phone;
		public $answers;
		public $created_at;
		public $updated_at;
		public $errors;
		
		public function __construct(){
			$cur_datetime = new DateTime();
			$this->created_at = $cur_datetime->format('Y-m-d H:i:s');
			$this->updated_at = $cur_datetime->format('Y-m-d H:i:s');
		}
		
		public function find($where = null, $order = null){
			$db_conn = new DB();
			$db_conn->select('contestants', '*', null, $where, $order);
			$this->errors = $db_conn->errors;
			return $db_conn->get_resultset();
		}
		
		public function create(){
			$is_valid = true;
			$validation_errors = array();
			
			if(strlen($this->name) < 1){
				$is_valid = false;
				$validation_errors[] = "Name cannot be blank.";
			}
			if(strlen($this->contest_id) < 1){
				$is_valid = false;
				$validation_errors[] = "Contest cannot be blank.";
			}
			if(strlen(implode(',', $this->answers)) < 1){
				$is_valid = false;
				$validation_errors[] = "Please provide an answer.";
			}
			if(strlen($this->email) < 1){
				$is_valid = false;
				$validation_errors[] = "Email cannot be blank.";
			}
			
			if($is_valid){
				$db_conn = new DB();
				$db_conn->insert('contestants',
					array(
						'contest_id', 
						'name', 
						'email', 
						'phone', 
						'answers', 
						'created_at', 
						'updated_at'
						), 
					array(
						$this->contest_id, 
						$this->name, 
						$this->email, 
						$this->phone, 
						$this->answers,
						$this->created_at, 
						$this->updated_at)
						);
				$this->errors = $db_conn->errors;
				return $db_conn->get_resultset();
			}else{
				$this->errors = $validation_errors;
				return false;
			}
		}
		
	}
?>