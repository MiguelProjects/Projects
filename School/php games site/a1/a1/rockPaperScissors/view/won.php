<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>RPS</title>
	</head>
	<body>
		<h1>Welcome to Rock, Paper, Scissors</h1>
		<?php echo(view_errors($errors)); ?>
		<?php 
			foreach($_SESSION['GuessGame']->history as $key=>$value){
				echo("<br/> $value");
			}
			$value=$_SESSION["GuessGame"]->getWins();
			echo("<br/> Wins: $value");
			$value=$_SESSION["GuessGame"]->getLoses();
			echo("<br/> Loses: $value");
		?>
		<br/>
		You have chosen to reset!
		<br/>
		<form method="post">
			<input type="submit" name="submit" value="start again" />
		</form>
	</body>
</html>

