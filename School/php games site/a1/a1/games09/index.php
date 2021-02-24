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

	/* controller code */
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

	function login(){
		$query = "SELECT * FROM appuser WHERE userid=$1 and password=$2;";
        $result = pg_prepare(db_connect(), "", $query);
        $result = pg_execute(db_connect(), "", array($_REQUEST['user'], md5($_REQUEST['password'])));
		return $result;
	}

	/* local actions, these are state transforms */
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
					// $query = "SELECT * FROM appuser WHERE userid=$1 and password=$2;";
                	// $result = pg_prepare($dbconn, "", $query);
                	// $result = pg_execute($dbconn, "", array($_REQUEST['user'], md5($_REQUEST['password'])));
					$result = login();
                	if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
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
			$view="unavailable.php";
			break;
		//--------------------------------------------------------------------------
		case "stats":
			$view="allStats.php";
			break;
		//--------------------------------------------------------------------------
		case "guess":
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
			if($_SESSION["Frogs"]->getState()=="won"){
				if(!empty($_REQUEST['submit'])&&$_REQUEST['submit']=="start again"){
					$_SESSION["userData"]->frogsWins += 1;
					$_SESSION["userData"]->update();
					$_SESSION["Frogs"]=new Frogs();
					$_SESSION['state']="frogs";
				}
			}

			$view="frogsPlay.php";
			if ( isset( $_POST['f'] ) ) {
				foreach ( $_POST['f'] as $key => $value ) {
					$_SESSION["Frogs"]->move($key);
				}
			}

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
			if(!empty($_REQUEST['cancel'])){
				$_SESSION['state']="login";
				$view="login.php";
			}

			if(!empty($_REQUEST['sendRegister'])){
				if(empty($_REQUEST['user']))$errors[]='user is required';
				if(empty($_REQUEST['password']))$errors[]='password is required';
				if(empty($_REQUEST['passwordVerify']))$errors[]='verify is required';
				if(empty($_REQUEST['status']))$errors[]='CSC309 status is not set';
				if(!empty($_REQUEST['password']) && !empty($_REQUEST['passwordVerify']) && $_REQUEST['passwordVerify'] != $_REQUEST['password'])$errors[]='password mismatch';
				if(!empty($_REQUEST['citizenship'])){
					$citizen = "Canadian Citizen";
				} else{//if unchekced its empty, other wise its the value
					$citizen = "Not a Citizen";
				}//citizenship addition
				
				if(empty($errors)){
						$regi = register($_REQUEST['user'],$_REQUEST['password'],$_REQUEST['gender'],$_REQUEST['birthday'],$_REQUEST['status'],$citizen);
						if($regi == false){
							$errors[]='Register error. Possibility: User already exists';
						}
					}
					if(empty($errors)){
						$_SESSION['state']='login';
						$view="login.php";
					}
				}

			break;
		//--------------------------------------------------------------------------
		case "profile":
			$tempUser = $_SESSION["userData"]->name;
			$tempPass = $_SESSION["userData"]->password;
			$tempStatus = $_SESSION["userData"]->status;
			$tempBirth = $_SESSION["userData"]->birthday;
			$tempGender = $_SESSION["userData"]->gender;
			$tempCitizen = $_SESSION["userData"]->citizen;
			$view = "profile.php";

			if(!empty($_REQUEST['sendRegister'])){
				if(empty($_REQUEST['user']))$errors[]='user is required';
				if(empty($_REQUEST['password']))$errors[]='password is required';
				if(empty($_REQUEST['passwordVerify']))$errors[]='verify is required';
				if(empty($_REQUEST['status']))$errors[]='CSC309 status is not set';
				if(!empty($_REQUEST['password']) && !empty($_REQUEST['passwordVerify']) && $_REQUEST['passwordVerify'] != $_REQUEST['password'])$errors[]='password mismatch';
				if(empty($errors)){
					$_SESSION["userData"]->updateName = $_REQUEST['user'];
					$_SESSION["userData"]->password = $_REQUEST['password'];
					$_SESSION["userData"]->birthday = $_REQUEST['birthday'];
					$_SESSION["userData"]->gender = $_REQUEST['gender'];
					$_SESSION["userData"]->status = $_REQUEST['status'];
					if(!empty($_REQUEST['citizenship'])){
						$tempCitizen = "Canadian Citizen";
					} else{//if unchekced its empty, other wise its the value
						$tempCitizen = "Not a Citizen";
					}//citizenship addition
					$_SESSION["userData"]->citizen = $tempCitizen;
						$regi = $_SESSION["userData"]->direUpdate();
						if($regi == false){
							$errors[]='Profile update error';
						}
					}
					if(empty($errors)){
						$_SESSION['userData']=new DataStore($_REQUEST['user'], $_REQUEST['password']);
						$_SESSION['state']="stats";
						$view="allStats.php";
					}
				}


			break;
		
		default:
			$view="unavailable.php";
			break;
			
	}//end switch
	//$_SESSION['Navigation']=new Navigation($_SESSION['state']);
	
	if($_SESSION['state']!='login' && $_SESSION['state']!='register'){
		$_SESSION['Navigation']=new Navigation($_SESSION['state']);
		 echo $_SESSION['Navigation']->getNav();
		}
	// if(isset($_SESSION['userData'])){
	// $_SESSION['userData']->rpsPlayed = 56;
	// $_SESSION['userData']->update();
	// echo $_SESSION['userData']->debug();
	// }

	// echo $_SESSION['state'];
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
