<?php
	// So I don't have to deal with uninitialized $_REQUEST['guess']
	$_REQUEST['guess']=!empty($_REQUEST['guess']) ? $_REQUEST['guess'] : '';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>RPS</title>
	</head>
	<body>
		<h1>Welcome to Rock, Paper, Scissors</h1>
		<?php if($_SESSION["GuessGame"]->getState()!="correct"){ ?>
			<!-- <form method="post">
			//	<input type="text" name="guess" value="<?php echo($_REQUEST['guess']); ?>" /> <input type="submit" name="submit" value="guess" />
			//</form> -->

			<form method="post">
				<input type="submit" name="rock" value="rock"/>
				<input type="submit" name="paper" value="paper"/>
				<input type="submit" name="scissor" value="scissor"/>
			</form>
			<form method="post">
				<input type="submit" name="reset" value="reset"/>
			</form>

		<?php } ?>

		<?php echo(view_errors($errors)); ?> 

		<?php 
			foreach($_SESSION['GuessGame']->history as $key=>$value){
				echo("<br/> $value");
			}
			$value=$_SESSION["GuessGame"]->getWins();
			echo("<br/> Wins: $value");
			$value=$_SESSION["GuessGame"]->getLoses();
			echo("<br/> Loses: $value");
			if($_SESSION["GuessGame"]->getState()=="correct"){ 
		?>
				<form method="post">
					<input type="submit" name="submit" value="start again" />
				</form>
		<?php 
			} 
		?>
	</body>
</html>

