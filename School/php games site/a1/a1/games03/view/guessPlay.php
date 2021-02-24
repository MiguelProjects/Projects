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
		<header>
			<nav>
				<ul>
				<li> <a href="">All Stats</a> </li>
				<li> <a href="">Guess Game</a> </li>
				<li> <a href="">Rock Paper Scissors</a> </li>
				<li> <a href="">Frogs</a> </li>
				<li> <a href="">Profile</a> </li>
				<li> <a href="">Logout</a> </li> 
                        	</ul>
			</nav>
		</header>
		<main>
			<section>
			<h1>Guess Game</h1>
			<!-- guessgame forms -->
			<?php if($_SESSION["GuessGame"]->getState()!="correct"){ ?>
				<form method="post">
					<input type="text" name="guess" value="<?php echo($_REQUEST['guess']); ?>" /> <input type="submit" name="submit" value="guess" />
				</form>
			<?php } ?>
			<!-- guessgame forms -->
		<?php echo(view_errors($errors)); ?> 
		    <!-- guessgame results -->
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
			<!-- guessgame results -->
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

