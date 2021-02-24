
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
            $form = '<header>';
            $form .= '<nav>';
            $form .= '<ul>';
				if($this->state =="stats"){ 
                    $form .= '<li class="current">'; 
                    $form .= '<a class="current">All Stats</a>'; 
                    $form .= '</li>';
                    } else{
                        $form .= '<li> <a href="">All Stats</a> </li>';
                    }
				if($this->state =="guess" || $this->state =="guessWon"){ 
                    $form .= '<li class="current">';
                    $form .= '<a class="current">Guess Game</a>'; 
                    $form .= '</li>';
                    } else{
                        $form .= '<li> <a href="">Guess Game</a> </li>';
                    }
				if($this->state =="rps" || $this->state =="rpsWon"){ 
                    $form .= '<li class="current">'; 
                    $form .= '<a class="current">Rock Paper Scissors</a>'; 
                    $form .= '</li>';
                    } else{
                        $form .= '<li> <a href="">Rock Paper Scissors</a> </li>';
                    }
				if($this->state =="frogs" || $this->state =="frogsWon"){ 
                    $form .= '<li class="current">'; 
                    $form .= '<a class="current">Frogs</a>'; 
                    $form .= ' </li>';
                    } else{
                        $form .= '<li> <a href="">Frogs</a> </li>';
                    }
				if($this->state =="profile"){ 
                    $form .= '<li class="current">'; 
                    $form .= '<a class="current">Profile</a>'; 
                    $form .= '</li>';
                    } else{
                    $form .= '<li> <a href="">Profile</a> </li>';
                    }
            $form .= '<li> <a href="">Logout</a> </li>';       
            $form .= '</ul>';
            $form .= '</nav>';
            $form .= '</header>';
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
logout 
-->
