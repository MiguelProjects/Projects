<?php
	ini_set('display_errors', 'On');
	require_once "lib/lib.php";
	require_once "model/something.php";
	require_once "model/GuessGame.php";
	require_once "view/Navigation.php";
	require_once "model/RPS.php";
	require_once "model/Frogs.php";

	session_save_path("sess");
	session_start(); 

	$dbconn = db_connect();

	$errors=array();
	$view="";

	/* controller code */

	/* local actions, these are state transforms */
	if(!isset($_SESSION['state'])){
		$_SESSION['state']='login';
	}
	if(isset($_GET['link'])){
		$_SESSION['link'] = $_GET['link'];
		$_SESSION['state'] = $_SESSION['link'];
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
		case "login":
			// the view we display by default
			$view="login.php";

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
			$query = "SELECT * FROM appuser WHERE userid=$1 and password=$2;";
                	$result = pg_prepare($dbconn, "", $query);

                	$result = pg_execute($dbconn, "", array($_REQUEST['user'], $_REQUEST['password']));
                	if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
				$_SESSION['user']=$_REQUEST['user'];
				// set initial page here
				// $_SESSION['GuessGame']=new GuessGame();
				$_SESSION['state']='stats';
				$view="allStats.php";
			} else {
				$errors[]="invalid login";
			}
			break;

		case "unavailable":
			$view="unavailable.php";
			break;
		//--------------------------------------------------------------------------stats
		case "stats":
			$view="allStats.php";
			break;
		//--------------------------------------------------------------------------stats

		//--------------------------------------------------------------------------guessgame
		case "guess":
			if($_SESSION["GuessGame"]->getState()=="correct"){
				if(!empty($_REQUEST['submit'])&&$_REQUEST['submit']=="start again"){
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
			
			$_REQUEST['guess']="";
			break;
		//--------------------------------------------------------------------------guessgame
		//--------------------------------------------------------------------------stats
		case "rps":
				if(!empty($_REQUEST['reset'])){
					$_SESSION["RPS"]=new RPS();
					$_SESSION['state']="rps";
				}
			$view="rpsPlay.php";

			//rock paper scissor part
			if(!empty($_REQUEST['rock'])){
				$_SESSION["RPS"]->playRPS(1);
				}
	
				else if(!empty($_REQUEST['paper'])){
				$_SESSION["RPS"]->playRPS(2);
				}
	
				else if(!empty($_REQUEST['scissor'])){
				$_SESSION["RPS"]->playRPS(3);
				}

			break;
		//--------------------------------------------------------------------------stats

		case "frogs":
			if($_SESSION["Frogs"]->getState()=="won"){
				if(!empty($_REQUEST['submit'])&&$_REQUEST['submit']=="start again"){
					$_SESSION["Frogs"]=new Frogs();
					$_SESSION['state']="frogs";
				}
			}

			$view="frogsPlay.php";
			if ( isset( $_POST['f'] ) ) {
				foreach ( $_POST['f'] as $key => $value ) {
					// echo 'Image number '.$key.' was clicked.';
					// $_SESSION["Frogs"]->change($key);
					$_SESSION["Frogs"]->move($key);
				}
			}

			if(!empty($_REQUEST['reset'])){
				$_SESSION["Frogs"]=new Frogs();
				}
			break;

		default:
			$view="unavailable.php";
			break;
			
	}//end switch
	//$_SESSION['Navigation']=new Navigation($_SESSION['state']);
	
	

	// require_once "view/unavailable.php";
	// we can put the nav here if just make it a new php file and require_once it
	if($_SESSION['state']!='login'){
		$_SESSION['Navigation']=new Navigation($_SESSION['state']);
		 echo $_SESSION['Navigation']->getNav();
		}
	// echo $_SESSION['link'];
	// echo "<br>";
	// echo $_SESSION['state'];
	// echo "<br>";
	// echo $view;
	// if($_SESSION['state']!='login'){
	// 	echo $_SESSION['Navigation']->getNav();
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