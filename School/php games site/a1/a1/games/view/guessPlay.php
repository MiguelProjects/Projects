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
			<h1>Guess Game</h1>

			<?php if($_SESSION["GuessGame"]->getState()!="correct"){ ?>
				<form method="post">
					<input type="text" name="guess" value="<?php echo($_REQUEST['guess']); ?>" /> <input type="submit" name="submit" value="guess" />
				</form>
			<?php } ?>

		<?php echo(view_errors($errors)); ?> 

			<?php 
				foreach($_SESSION['GuessGame']->history as $key=>$value){
					echo("<br/> $value");
				}
				if($_SESSION["GuessGame"]->getState()=="correct"){ 
			?>
					<form method="post">
						<input type="submit" name="submit" value="start again" />
					</form>
			<?php 
				} 
			?>

			</section>
			<section class='stats'>
				<h1>Leader</h1>
				<p><?php echo "GuessGame Wins #1: '" . $_SESSION['userData']->obtainMaxName('guessWins') . "' with Win stat of: ". $_SESSION['userData']->obtainMaxStat('guessWins');?></p>
				<h1>Stats</h1>
				<p><?php echo "GuessGame Guesses: " . $_SESSION['userData']->obtain('guessGuess');?></p>
				<p><?php echo "GuessGame Wins: " . $_SESSION['userData']->obtain('guessWins');?></p>
				<p>*Wins are registered when a new game starts</p>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

