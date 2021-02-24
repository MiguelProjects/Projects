<?php
	$_REQUEST['guess']=!empty($_REQUEST['guess']) ? $_REQUEST['guess'] : '';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Games</title>
	</head>
	<body>
		<main>
			<section>
				<h1>Rock Paper Scissors</h1>
					<form method="post">
						<input type="submit" name="rock" value="rock"/>
						<input type="submit" name="paper" value="paper"/>
						<input type="submit" name="scissor" value="scissor"/>
					</form>
					<form method="post">
						<input type="submit" name="reset" value="reset"/>
					</form>

				<?php echo(view_errors($errors)); ?> 

				<?php 
					foreach($_SESSION['RPS']->history as $key=>$value){
						echo("<br/> $value");
					}
					$value=$_SESSION["RPS"]->getWins();
					echo("<br/> Wins: $value");
					$value=$_SESSION["RPS"]->getLoses();
					echo("<br/> Loses: $value");
				?>
			</section>
			<section class='stats'>
				<h1>Stats</h1>
					<p><?php echo "Rock Paper Scissor Wins: " . $_SESSION['userData']->obtain('rpsWins');?></p>
					<p><?php echo "Rock Paper Scissor Losses: " . $_SESSION['userData']->obtain('rpsLoses');?></p>
					<p><?php echo "Rock Paper Scissor Games: " . $_SESSION['userData']->obtain('rpsPlayed');?></p>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

