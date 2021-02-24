
<?php
class Navigation{
	public $state = "";

	public function __construct($curr) {
        	$this->state = $curr;
    	}

        public function getState(){
            return $this->state;
        }

        public function setState($curr){
            $this->state = $curr;
        }

        public function getNav(){
            $form = '<header><nav><ul>';
				if($this->state =="stats"){ 
                    $form .= '<li class="current"><a class="current">All Stats</a></li>'; 
                    } else{
                        $form .= '<li> <a href="index.php?link=stats" name="AllStats">All Stats</a> </li>';
                    }
				if($this->state =="guess"){ 
                    $form .= '<li class="current"><a class="current">Guess Game</a></li>'; 
                    } else{
                        $form .= '<li> <a href="index.php?link=guess" name="GuessGame">Guess Game</a> </li>';
                    }
				if($this->state =="rps"){ 
                    $form .= '<li class="current"><a class="current">Rock Paper Scissors</a></li>'; 
                    } else{
                        $form .= '<li> <a href="index.php?link=rps" name="RPSGame">Rock Paper Scissors</a></li>';
                    }
				if($this->state =="frogs"){ 
                    $form .= '<li class="current"><a class="current">Frogs</a></li>'; 
                    } else{
                        $form .= '<li> <a href="index.php?link=frogs" name="FrogsGame">Frogs</a> </li>';
                    }
				if($this->state =="profile"){ 
                    $form .= '<li class="current"><a class="current">Profile</a></li>'; 
                    } else{
                    $form .= '<li> <a href="index.php?link=profile" name="Profile">Profile</a> </li>';
                    }
            $form .= '<li> <a href="index.php?link=logout" name="Logout">Logout</a> </li>';       
            $form .= '</ul></nav></header>';
            return $form;
        }
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Games</title>
	</head>  

<!-- 
statuses:

stats
guess/Won
rps/Won
frogs/Won
profile
login 
-->
