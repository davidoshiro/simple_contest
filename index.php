<?php
	// Load models
	require_once('Contest.php');
	require_once('Contestant.php');
	
	$errors = array();
	$contest = new Contest();
	$cur_contest = $contest->find('id=1');

	// Detect method
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		// Is today in contest date range?
		if(strtotime("now") < strtotime($cur_contest[0]['start_at'])){
			$title = "Contest is not active yet";
			$display_div = "before_start";
		}elseif(strtotime("now") > strtotime($cur_contest[0]['end_at'])){
			$title = "Contest has expired";
			$display_div = "after_end";
		}else{
			$title = "New Contest Form";
			$display_div = "new";
		}
	}elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
		$contestant = new Contestant();
		$display_div = "create";
		
		# Compile multiple answers to JSON string
		$answers = array();
		foreach($_POST as $key=>$value){
			if(substr($key, 0, 6) == 'answer'){
				if(strlen($value) > 0){
					$answers[] = $value;
				}
			}
		}
		
		$contestant->contest_id = $cur_contest[0]['id'];
		$contestant->name = $_POST['name'];
		$contestant->email = $_POST['email'];
		$contestant->phone = $_POST['phone'];
		$contestant->answers = json_encode($answers);
		$contestant->create();
		$errors = $contestant->errors;
		if(count($errors) > 0){
			$title = "There was an error with your submission.";
		}else{
			$title = "Thank you for your submission.";
		}
	}

?>

<!DOCTYPE html>
<html lang="eng">
	<head>
		<title><?php echo $title; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
		<link href="css/bootstrap-tweaks.css" rel="stylesheet">
	</head>
	<body>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">
					<div class="hero-unit">
					</div>
					
<?php 
	if($display_div == "before_start"){
		echo "<div id='before_start'>";
		echo "<h1>{$title}</h1>";
		echo "</div>";
	}
	
	if($display_div == "after_end"){
		echo "<div id='after_end'>";
		echo "<h1>{$title}</h1>";
		echo "</div>";
	}
	
	if($display_div == "new"){
		echo "<div id='new'>";
		echo "<h1>{$title}</h1>";
		echo "<form method='POST' id='contest_form'>";
		echo "<div>";
		echo "	<div><label for='name'>Name: </label><input type='text' name='name' id='name' required></div>";
		echo "	<div><label for='email'>Email: </label><input type='email' name='email' id='email' required></div>";
		echo "	<div><label for='phone'>Phone: </label><input type='text' name='phone' id='phone'></div>";
		echo "</div>";

		echo "<p>{$cur_contest[0]['question']}</p>";
		echo "<div id='answers'>";
		echo "	<div>";
		echo "		<div class='input-append'>";
		echo "			<input type='text' name='answer_1' required>";
		echo "			<button class='btn' type='button' onclick='add_answer();'>Add</button>";
		echo "		</div>";
		echo "	</div>";
		echo "</div>";
		echo "<p><input type='submit' value='Submit' class='btn btn-primary btn-large'></p>";
		echo "</form>";
		echo "</div>";
	}
	
	if($display_div == "create"){
		echo "<div id='create'>";
		echo "<h1>{$title}</h1>";
		if(count($errors) > 0){
			echo "<div id='errors'>";
			echo "<ul class='error_list'>";
			foreach($errors as $err){
				echo "<li class='text-error'>{$err}</li>";
			}
			echo "</ul>";
			echo "<p><a href='#' onclick='history.back();'>Please go back and try again.</a></p>";
			echo "</div>";
		}
		echo "</div>";
	}
?>
				</div>
			</div>
			
			<hr>
			
			<footer>
				Developed by David Oshiro 2013 &copy; All Rights Reserved.
			</footer>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/happy.js"></script>
		<script>
			var answer_count = 1;
			
			/**
			 * Appends a new answer field to the form
			 */
			function add_answer(){
				answer_count++;
				$('form div#answers').append("<div><div id='answer_" + answer_count + "' class='input-append'><input type='text' name='answer_" + answer_count + "' id='answer_" + answer_count + "' required><button class='btn' onclick='remove_answer(\"div div#answer_" + answer_count + "\");return false;'>Remove</button><button class='btn' type='button' onclick='add_answer();'>Add</button></div></div>");
			}
			
			/**
			 * Removes current answer field from form
			 */
			function remove_answer(id){
				$(id).css('display', 'none');
			}
			
			/**
			 * Configure client-side form validation.
			 */
			$(document).ready(function(){
				$('#contest_form').isHappy({
					fields:{
						'#name':{
							required: true,
							message: 'Name is required'
						},
						'#email':{
							required: true,
							message: 'Email isrequired'
						}
					}
				});
			});
		</script>
	</body>
</html>