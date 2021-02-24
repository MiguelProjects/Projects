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
				<!-- <?php if($_SESSION["GuessGame"]->getState()!="correct"){ ?> -->
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

				<!-- <?php } ?> -->

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
				stats go here
				stats go here
				stats go here
				stats go here
				stats go here
				stats go here
				stats go here
				stats go here
				stats go here
				stats go here
				stats go here
				stats go here
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

