<?php
// So I don't have to deal with unset $_REQUEST['user'] when refilling the form
// You can also take a look at the new ?? operator in PHP7

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
				</ul>
			</nav>
		</header>

		<main>
			<section class="registration">
				<h1>Profile Information</h1>
                <legend>Profile</legend>
                    <form action="index.php" method="post">
                    <table>
						<!-- Trick below to re-fill the user form field -->
                        
                        <?php
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

						<tr><th><label for="user">User</label></th></tr>
                        <tr><td><input type="text" required="required" name="user" value="<?php echo($tempUser); ?>" /> </td><td><span class="error" style="color:red;font-weight:bold"><?php echo $nameErr;?></span></td></tr>
                        

						<tr><th><label for="password">Password</label></th></tr>
                        <tr><td> <input type="password" required="required" name="password" value="<?php echo($tempPass); ?>" /></td><td><span class="error" style="color:red;font-weight:bold"><?php echo $passErr;?></span></td></tr>

                        <tr><th><label for="passwordVerify">Password Verification</label></th></tr>
                        <tr><td> <input type="password" required="required" name="passwordVerify" /></td><td><span class="error" style="color:red;font-weight:bold"><?php echo $verErr;?></span></td></tr>

                        <tr><th><label for="birthday">Birthday:</label></th></tr>
                        <tr><td><input type="date" id="birthday" name="birthday" value="<?php echo($tempBirth); ?>" ></td></tr>
                        
                        <tr><th><label for="gender">Gender</label></th></tr>
                        <tr><td>
                        <select name="gender" value=''>
                        <option selected hidden value='<?php echo($tempGender); ?>'><?php echo($tempGender); ?></option>
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
                        <tr><td><input type="radio" id="statusStudent" name="status" value="Student" <?php if($tempStatus == 'Student') echo 'checked';?>>
                        <label for="statusStudent">Student</label><td><tr>
                        
                        <tr><td><input type="radio" id="statusTA" name="status" value="TA" <?php if($tempStatus == 'TA') echo 'checked';?>>
                        <label for="statusTA">TA</label><td><tr>
                        
                        <tr><td><input type="radio" id="statusProf" name="status" value="Professor" <?php if($tempStatus == 'Professor') echo 'checked';?>>
                        <label for="statusProf">Professor</label><td><tr>

                        <tr><td><input type="radio" id="statusNo" name="status" value="None" <?php if($tempStatus == 'None') echo 'checked';?>>
                        <label for="statusNo">Not Affilated</label><td><tr>

                        <tr><td>Are You a Canadian Citizen?</td></tr>
                        <tr><td><input type="checkbox" name="citizenship" value="citizenship" <?php if($tempCitizen == "Canadian Citizen") echo 'checked';?>>

                        <label for="user">Check Button if You Are</label><td><tr>

                        <tr><td><input type="submit" name="sendRegister" value="Register"/></td></tr>

						<tr style="color: red"><th>&nbsp;</th><td><?php echo(view_errors($errors)); ?></td></tr>
					</table> 
                    </form>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

