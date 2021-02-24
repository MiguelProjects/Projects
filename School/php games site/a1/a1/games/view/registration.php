<?php
$_REQUEST['user']=!empty($_REQUEST['user']) ? $_REQUEST['user'] : '';
$_REQUEST['password']=!empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
$_REQUEST['status']=!empty($_REQUEST['status']) ? $_REQUEST['status'] : '';
$nameErr = "";
$passErr = "";
$verErr = "";
$statErr = "";
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
                    <!-- it looks like the registration page with the bar and looks nice i think -->
				</ul>
			</nav>
		</header>

		<main>
			<section class="registration">
				<h1>Register for Games</h1>
                <legend>Register</legend>
                    <form action="index.php" method="post">
                    <table>
                        <?php
                        //set error values - if certain things arent filled then set span values
                        if (empty($_POST["user"])) {
                            $nameErr = "* Name is required";
                        }
                        if (empty($_POST["password"])) {
                            $passErr = "* Password is required";
                        }
                        if (empty($_POST["passwordVerify"])) {
                            $verErr = "* Verify is required";
                        }
                        if (empty($_POST["status"])) {
                            $statErr = "* Status is required";
                        }
                        ?>
                        <!-- super long thing but basically we set up a form with a red error part -->
						<tr><th><label for="user">User</label></th></tr>
                        <tr><td><input type="text" required="required" name="user" value="<?php echo($_REQUEST['user']); ?>" /> </td><td><span class="error" style="color:red;font-weight:bold"><?php echo $nameErr;?></span></td></tr>
                        
                        <!-- required forms have the required tag -->
						<tr><th><label for="password">Password</label></th></tr>
                        <tr><td> <input type="password" required="required" name="password" /></td><td><span class="error" style="color:red;font-weight:bold"><?php echo $passErr;?></span></td></tr>

                        <tr><th><label for="passwordVerify">Password Verification</label></th></tr>
                        <tr><td> <input type="password" required="required" name="passwordVerify" /></td><td><span class="error" style="color:red;font-weight:bold"><?php echo $verErr;?></span></td></tr>

                        <!-- I saw this in w3 schools and thought it was cool -->
                        <tr><th><label for="birthday">Birthday:</label></th></tr>
                        <tr><td><input type="date" id="birthday" name="birthday"></td></tr>
                        
                        <!-- yes the attack helicopter is a joke -->
                        <tr><th><label for="gender">Gender</label></th></tr>
                        <tr><td>
                        <select name="gender" value=''>
                        <option value='Male'>Male</option>
                        <option value='Female'>Female</option>
                        <option value='Transgender'>Transgender</option>
                        <option value='Agender'>Agender</option>
                        <option value='Other'>Other (Unlisted)</option>
                        <option value='Prefer Not to Answer'>Prefer Not to Answer</option>
                        <option value='Attack Helicopter'>Attack Helicopter</option>
                        </select>
                        </td></tr>
                        
                        <tr><th><br>For CSC309 You Are:</th></tr>
                        <tr><td><span class="error" style="color:red;font-weight:bold"><?php echo $statErr;?></span></td></tr>
                        <tr><td><input type="radio" id="statusStudent" name="status" value="Student">
                        <label for="statusStudent">Student</label><td><tr>
                        
                        <tr><td><input type="radio" id="statusTA" name="status" value="TA">
                        <label for="statusTA">TA</label><td><tr>
                        
                        <tr><td><input type="radio" id="statusProf" name="status" value="Professor">
                        <label for="statusProf">Professor</label><td><tr>

                        <tr><td><input type="radio" id="statusNo" name="status" value="None">
                        <label for="statusNo">Not Affilated</label><td><tr>
                        
                        <tr><td>Are You a Canadian Citizen?</td></tr>
                        <tr><td><input type="checkbox" name="citizenship" value="citizenship">
                        <label for="user">Check Button if You Are</label><td><tr>

                        <tr><td><input type="submit" name="sendRegister" value="Register"/></td></tr>

						<tr style="color: red"><th>&nbsp;</th><td><?php echo(view_errors($errors)); ?></td></tr>
					</table> 
                    </form>
                <form method="post">
                    <input type="submit" name="cancel" value="Cancel"/>
                </form>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

