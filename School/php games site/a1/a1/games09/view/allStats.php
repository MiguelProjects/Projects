<?php
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
				<h1>Stats by Game</h1>
				<table class='allStats'>
					<tr>
						<td><?php echo "GuessGame Guesses: " . $_SESSION['userData']->obtain('guessGuess');?></td>
						<td><?php echo "GuessGame Wins: " . $_SESSION['userData']->obtain('guessWins');?></td>
					</tr>
					<tr>
						<td><?php echo "Rock Paper Scissor Wins: " . $_SESSION['userData']->obtain('rpsWins');?></td>
						<td><?php echo "Rock Paper Scissor Losses: " . $_SESSION['userData']->obtain('rpsLoses');?></td>
						<td><?php echo "Rock Paper Scissor Games: " . $_SESSION['userData']->obtain('rpsPlayed');?></td>
					</tr>
					<tr>
						<td><?php echo "Frogs Wins: " . $_SESSION['userData']->obtain('frogsWins');?></td>
						<td><?php echo "Frogs Resets: " . $_SESSION['userData']->obtain('frogsResets');?></td>
						<td><?php echo "Frogs Stucks: " . $_SESSION['userData']->obtain('frogsStucks');?></td>
					</tr>
				</table>
			</section>
			<section class='stats'>
				<h1>Summary Stats</h1>
				<p><?php echo "GuessGame Wins: " . $_SESSION['userData']->obtain('guessWins');?></p>
				<p><?php echo "Rock Paper Scissor Wins: " . $_SESSION['userData']->obtain('rpsWins');?></p>
				<p><?php echo "Frogs Wins: " . $_SESSION['userData']->obtain('frogsWins');?></p>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

