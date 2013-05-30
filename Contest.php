<?php
/**
 * @author David Oshiro
 * 
 * Contest Class
 * 
 * Contest DB model. The Contest model has many Contestants.
 * Requires DB Class.
 */
 
	require_once("DB.php");
	
	class Contest{
		// Instance variables
		public $id;
		public $name;
		public $description;
		public $question;
		public $start_at;
		public $end_at;
		public $created_at;
		public $updated_at;
		public $errors;
		
		/**
		 * Class constructor. Sets default values.
		 */
		public function __construct(){
			$cur_datetime = new DateTime();
			$this->start_at = $cur_datetime->format('Y-m-d H:i:s');
			$this->end_at = $cur_datetime->add(new DateInterval('P1D'))->format('Y-m-d H:i:s');
			$this->created_at = $cur_datetime->format('Y-m-d H:i:s');
			$this->updated_at = $cur_datetime->format('Y-m-d H:i:s');
		}
		
		/**
		 * Performs select queries and saves resultset instance variable.
		 */
		public function find($where = null, $order = null){
			$db_conn = new DB();
			$db_conn->select('contests', '*', null, $where, $order);
			$this->errors = $db_conn->errors;
			return $db_conn->get_resultset();
		}
		
		/** 
		 * Creates a new record.
		 */
		public function create(){
			$is_valid = true;
			$validation_errors = array();
			
			if(strlen($this->name) < 1){
				$is_valid = false;
				$validation_errors[] = "Name cannot be blank.";
			}
			if(strlen($this->question) < 1){
				$is_valid = false;
				$validation_errors[] = "Question cannot be blank.";
			}
			if(strlen($this->start_at) < 1){
				$is_valid = false;
				$validation_errors[] = "Start date cannot be blank.";
			}
			if(strlen($this->end_at) < 1){
				$is_valid = false;
				$validation_errors[] = "End date cannot be blank.";
			}
			if(strtotime($this->start_at) > strtotime($this->end_at)){
				$is_valid = false;
				$validation_errors[] = "Start date cannot be after End date.";
			}
			
			if($is_valid){
				$db_conn = new DB();
				$db_conn->insert('contests',
					array(
						'name', 
						'description', 
						'question', 
						'start_at', 
						'end_at', 
						'created_at', 
						'updated_at'
						), 
					array(
						$this->name, 
						$this->description, 
						$this->question, 
						$this->start_at, 
						$this->end_at,
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