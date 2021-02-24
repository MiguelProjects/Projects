<?php
	ini_set('display_errors', 'On');
	require_once "lib/lib.php";
	require_once "model/something.php";
	require_once "model/GuessGame.php";
	require_once "view/Navigation.php";
	require_once "model/RPS.php";
	require_once "model/DataStore.php";
	require_once "model/Frogs.php";

	session_save_path("sess");
	session_start(); 

	$dbconn = db_connect();

	$errors=array();
	$view="";

	//the @ silences error echoes but does not stop the result from occuring - it allows us to fail silently 
	// cannot directly call $dbconn in the function for some reason, no reason to pass it either - just call db_connect()
	function register($varU, $varP, $varG, $varB, $varS,$varC){
		$pass = md5($varP);
		$query="INSERT INTO appuser (userid, password, gender, birthday, status, citizenship, guessGuess, guessWins, rpsWins, rpsLoses, rpsPlayed, frogsWins, frogsResets, frogsStucks) values($1, $2, $3, $4, $5, $6,0,0,0,0,0,0,0,0)";
		$result=@pg_prepare(db_connect(), "",$query);
		$result=@pg_execute(db_connect(), "", array($varU, $pass, $varG, $varB, $varS,$varC));
		if($result==false){//errors can occur if theres a duplicate value
			return false;
		}
		echo "Succesful Regstration";
		return true;
	}

	//error checking of registration
	function checkPasses(){
		$errs = "";
		$marker = false;
		if(empty($_REQUEST['user'])){
			$errs .= "user is required<br>";
			$marker = true;
		}
		if(empty($_REQUEST['password'])){
			$errs .= "password is required<br>";
			$marker = true;
		}
		if(empty($_REQUEST['passwordVerify'])){
			$errs .= "verification is required<br>";
			$marker = true;
		}
		if(empty($_REQUEST['status'])){
			$errs .= "CSC309 status is required<br>";
			$marker = true;
		}
		if(!empty($_REQUEST['password']) && !empty($_REQUEST['passwordVerify']) && $_REQUEST['passwordVerify'] != $_REQUEST['password']){
			$errs .= "Password verification mismatch<br>";
			$marker = true;
		}
		if($marker == true){
		return $errs;
		}
		return $marker;
	}

	//the @ silences error echoes but does not stop the result from occuring - it allows us to fail silently 
	function login(){
		$query = "SELECT * FROM appuser WHERE userid=$1 and password=$2;";
        $result = pg_prepare(db_connect(), "", $query);
        $result = pg_execute(db_connect(), "", array($_REQUEST['user'], md5($_REQUEST['password'])));
		return $result;
	}

	//set session variables
	if(!isset($_SESSION['state'])){
		$_SESSION['state']='login';
	}
	if(isset($_GET['link'])){
		$_SESSION['state'] = $_GET['link'];
	}
	if(!isset($_SESSION["GuessGame"])){
		$_SESSION["GuessGame"]=new GuessGame();
	}
	if(!isset($_SESSION["RPS"])){
		$_SESSION["RPS"]=new RPS();
	}
	if(!isset($_SESSION["Frogs"])){
		$_SESSION["Frogs"]=new Frogs();
	}

	switch($_SESSION['state']){
		case "logout":
			session_destroy();
			header("Location: index.php");
			//head to the top
			break;
		case "login":
			// the view we display by default
			$view="login.php";

			if(!empty($_REQUEST['register'])){
				$_SESSION['state']="register";
				$view="registration.php";
				}

			// check if submit or not
			if(empty($_REQUEST['submit']) || $_REQUEST['submit']!="login"){
				break;
			}

			// validate and set errors
			if(empty($_REQUEST['user']))$errors[]='user is required';
			if(empty($_REQUEST['password']))$errors[]='password is required';
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			if(!$dbconn){
				$errors[]="Can't connect to db";
				break;
			}	
				//login, check if it worked as well
					$result = login();
                	if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
				//initialize the user since they logged in
				$_SESSION['user']=$_REQUEST['user'];
				$_SESSION['userData']=new DataStore($_REQUEST['user'], $_REQUEST['password']);
				// set initial page here
				$_SESSION['state']='stats';
				$view="allStats.php";
			} else {
				$errors[]="invalid login";
			}
			break;
		//--------------------------------------------------------------------------
		case "unavailable":
			//the default handles this but I kept it in in case the markers wanted to see it here for some reason
			$view="unavailable.php";
			break;
		//--------------------------------------------------------------------------
		case "stats":
			//there is literally nothing to do in stats
			$view="allStats.php";
			break;
		//--------------------------------------------------------------------------
		case "guess":
			//Before any button presses, check they won
			if($_SESSION["GuessGame"]->getState()=="correct"){
				if(!empty($_REQUEST['submit'])&&$_REQUEST['submit']=="start again"){
					$_SESSION["userData"]->guessWins += 1;
					$_SESSION["userData"]->update();
					$_SESSION["GuessGame"]=new GuessGame();
					$_SESSION['state']="guess";
				}
			}
			$view="guessPlay.php";
			// check if submit or not
			if(empty($_REQUEST['submit'])||$_REQUEST['submit']!="guess"){
				break;
			}

			// validate and set errors
			if(!is_numeric($_REQUEST["guess"]))$errors[]="Guess must be numeric.";
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			$_SESSION["GuessGame"]->makeGuess($_REQUEST['guess']);
			$_SESSION["userData"]->guessGuesses += 1;
			$_SESSION["userData"]->update();
			
			$_REQUEST['guess']="";
			break;
		//--------------------------------------------------------------------------
		case "rps":
				if(!empty($_REQUEST['reset'])){
					$_SESSION["RPS"]=new RPS();
					$_SESSION['state']="rps";
				}
			$view="rpsPlay.php";

			//rock paper scissor part
			if(!empty($_REQUEST['rock'])){
				$tempRps = $_SESSION["RPS"]->playRPS(1);
				}
				else if(!empty($_REQUEST['paper'])){
				$tempRps = $_SESSION["RPS"]->playRPS(2);
				}
				else if(!empty($_REQUEST['scissor'])){
				$tempRps = $_SESSION["RPS"]->playRPS(3);
				}

				//if any button has been pressed, update the necessary stats
				if(!empty($_REQUEST['rock']) || !empty($_REQUEST['paper']) ||!empty($_REQUEST['scissor'])){
					$_SESSION["userData"]->rpsPlayed += 1;
					if($tempRps == 'win'){
						$_SESSION["userData"]->rpsWins += 1;
					}else if ($tempRps == 'lose'){
						$_SESSION["userData"]->rpsLoses += 1;
					}
					$_SESSION["userData"]->update();
				}
			break;
		//--------------------------------------------------------------------------
		case "frogs":
			//same as guess, check if win before any button presses
			if($_SESSION["Frogs"]->getState()=="won"){
				if(!empty($_REQUEST['submit'])&&$_REQUEST['submit']=="start again"){
					$_SESSION["userData"]->frogsWins += 1;
					$_SESSION["userData"]->update();
					$_SESSION["Frogs"]=new Frogs();
					$_SESSION['state']="frogs";
				}
			}

			$view="frogsPlay.php";
			//check the array of frog images
			if ( isset( $_POST['f'] ) ) {
				foreach ( $_POST['f'] as $key => $value ) {
					$_SESSION["Frogs"]->move($key);
				}
			}

			//if reset is pressed start a new frog game and update stats
			if(!empty($_REQUEST['reset'])){
				$_SESSION["userData"]->frogsResets += 1;
				if($_SESSION["Frogs"]->getState()=='stuck'){
					$_SESSION["userData"]->frogsStucks += 1;
				}
				$_SESSION["userData"]->update();
				$_SESSION["Frogs"]=new Frogs();
				}
			
			break;
		//--------------------------------------------------------------------------
		case "register":
			$view="registration.php";
			//if cancel, switch page before any button presses
			if(!empty($_REQUEST['cancel'])){
				$_SESSION['state']="login";
				$view="login.php";
			}

			//if register request, see if no errors
			if(!empty($_REQUEST['sendRegister'])){
				$check = checkPasses();
				if(!empty($_REQUEST['citizenship'])){
					$citizen = "Canadian Citizen";
				} else{//if unchekced its empty, other wise its the value
					$citizen = "Not a Citizen";
				}
				
				if($check != false){//true as in there is an error
					$errors[] = $check;//add error to errors
				}
				//if no errors then register
				if(empty($errors) && $check == false){
						$regi = register($_REQUEST['user'],$_REQUEST['password'],$_REQUEST['gender'],$_REQUEST['birthday'],$_REQUEST['status'], $citizen);
						if($regi == false){
							$errors[]='Register error. Possibility: User already exists';
						}
					}
					//after successful register, send back to login
					if(empty($errors) && checkPasses() == false){
						$_SESSION['state']='login';
						$view="login.php";
					}
				}
			break;
		//--------------------------------------------------------------------------
		case "profile":
			//obtain all user information to set the view prefills
			$tempUser = $_SESSION["userData"]->name;
			$tempPass = $_SESSION["userData"]->password;
			$tempStatus = $_SESSION["userData"]->status;
			$tempBirth = $_SESSION["userData"]->birthday;
			$tempGender = $_SESSION["userData"]->gender;
			$tempCitizen = $_SESSION["userData"]->citizen;
			$view = "profile.php";

			//this entire thing is basically the same as register
			if(!empty($_REQUEST['sendRegister'])){
				$check = checkPasses();
				if($check != false){
					$errors[] = $check;
				}
				if(empty($errors) && $check == false){
					//this sets the new values of the account
					$_SESSION["userData"]->updateName = $_REQUEST['user'];
					$_SESSION["userData"]->password = $_REQUEST['password'];
					$_SESSION["userData"]->birthday = $_REQUEST['birthday'];
					$_SESSION["userData"]->gender = $_REQUEST['gender'];
					$_SESSION["userData"]->status = $_REQUEST['status'];
					if(!empty($_REQUEST['citizenship'])){
						$tempCitizen = "Canadian Citizen";
					} else{//if unchekced its empty, other wise its the value
						$tempCitizen = "Not a Citizen";
					}
					$_SESSION["userData"]->citizen = $tempCitizen;
						$regi = $_SESSION["userData"]->direUpdate();
						if($regi == false){
							$errors[]='Profile update error';
						}
					}
					//the data has been updated, make a new user
					if(empty($errors) && checkPasses() == false){
						$_SESSION['userData']=new DataStore($_REQUEST['user'], $_REQUEST['password']);
						$_SESSION['state']="stats";
						$view="allStats.php";
					}
				}
			break;
		//--------------------------------------------------------------------------
		default:
			$view="unavailable.php";
			break;
			
	}//end switch
	if($_SESSION['state']!='login' && $_SESSION['state']!='register'){
		$_SESSION['Navigation']=new Navigation($_SESSION['state']);
		 echo $_SESSION['Navigation']->getNav();
		}
		
	require_once "view/$view";
?>
<!-- 
statuses:

stats
guess/Won
rps/Won
frogs/Won
profile
login 
-->
