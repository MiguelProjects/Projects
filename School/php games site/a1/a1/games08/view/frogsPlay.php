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
            <h1>Frogs</h1>
                <div class='frogs'>
                
                <form method="post">
                    <!-- <input type="submit" name="f0" value="L"/> -->
					<!-- onClick=<?php// $_SESSION['Frogs']->change(4);?> -->
                    <input type="image" name="f[0]" src=<?php echo $_SESSION['Frogs']->delta(0);?>  width="50" height="50"/>
                </form>
                <form method="post">
                    <input type="image" name="f[1]" src=<?php echo $_SESSION['Frogs']->delta(1);?>  width="50" height="50"/>
                </form>
                <form method="post">
                    <input type="image" name="f[2]" src=<?php echo $_SESSION['Frogs']->delta(2);?>  width="50" height="50"/>
                </form>
                <form method="post">
                    <input type="image" name="f[3]" src=<?php echo $_SESSION['Frogs']->delta(3);?>  width="50" height="50"/>
                </form>
                <form method="post">
                    <input type="image" name="f[4]" src=<?php echo $_SESSION['Frogs']->delta(4);?>  width="50" height="50"/>
                </form>
                <form method="post">
                    <input type="image" name="f[5]" src=<?php echo $_SESSION['Frogs']->delta(5);?>  width="50" height="50"/>
                </form>
                <form method="post">
                    <input type="image" name="f[6]" src=<?php echo $_SESSION['Frogs']->delta(6);?>  width="50" height="50"/>
                </form>
                </div>
			<form method="post">
				<?php if($_SESSION["Frogs"]->getState()!="won"){ ?>	
							<input type="submit" name="reset" value="reset"/>
				<?php } ?>
				<?php if($_SESSION["Frogs"]->getState()=="won"){ ?>
						YOU WON!
						<input type="submit" name="submit" value="start again" />
				<?php } ?>

				<input type="text" id="frogGuess" name="frogGuess" value="<?php echo $_SESSION['Frogs']->getMoves();?>" readonly>
							<?php if($_SESSION["Frogs"]->getState()=="stuck"){ ?>	
				<input type="text" id="frogGuess" name="frogGuess" value="You are stuck!" readonly>
				<?php } else{?>
				<input type="text" id="frogGuess" name="frogGuess" value="Status: OK!" readonly>
				<?php }?>
			</form>

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

