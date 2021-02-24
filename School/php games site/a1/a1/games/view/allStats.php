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
				<h1>Summary Stats - Best Players</h1>
				<p>
					<?php echo "GuessGame Wins #1: User '" . $_SESSION['userData']->obtainMaxName('guessWins') . "' with Win stat of: ". $_SESSION['userData']->obtainMaxStat('guessWins');?>
					<br>
					<?php echo "GuessGame Wins #2: User '" . $_SESSION['userData']->obtainNName('guessWins',1) . "' with Win stat of: ". $_SESSION['userData']->obtainNStat('guessWins',1);?>
					<br>
					<?php echo "GuessGame Wins #3: User '" . $_SESSION['userData']->obtainNName('guessWins',2) . "' with Win stat of: ". $_SESSION['userData']->obtainNStat('guessWins',2);?>
				</p>
				<p>
					<?php echo "RPS Wins #1: User '" . $_SESSION['userData']->obtainMaxName('rpsWins') . "' with Win stat of: ". $_SESSION['userData']->obtainMaxStat('rpsWins');?>
					<br>
					<?php echo "RPS Wins #2: User '" . $_SESSION['userData']->obtainNName('rpsWins',1) . "' with Win stat of: ". $_SESSION['userData']->obtainNStat('rpsWins',1);?>
					<br>
					<?php echo "RPS Wins #3: User '" . $_SESSION['userData']->obtainNName('rpsWins',2) . "' with Win stat of: ". $_SESSION['userData']->obtainNStat('rpsWins',2);?>
				</p>
				<p>
					<?php echo "Frogs Wins #1: User '" . $_SESSION['userData']->obtainMaxName('frogsWins') . "' with Win stat of: ". $_SESSION['userData']->obtainMaxStat('frogsWins');?>
					<br>
					<?php echo "Frogs Wins #2: User '" . $_SESSION['userData']->obtainNName('frogsWins',1) . "' with Win stat of: ". $_SESSION['userData']->obtainNStat('frogsWins',1);?>
					<br>
					<?php echo "Frogs Wins #3: User '" . $_SESSION['userData']->obtainNName('frogsWins',2) . "' with Win stat of: ". $_SESSION['userData']->obtainNStat('frogsWins',2);?>
				</p>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

