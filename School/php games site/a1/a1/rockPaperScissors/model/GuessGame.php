<?php

class GuessGame {
	public $secretNumber = 5;
	public $numGuesses = 0;
	public $numWins = 0;
	public $numLoses = 0;
	public $history = array();
	public $state = "";

	public function __construct() {
        	$this->secretNumber = rand(1,3);
    	}
	
	public function makeGuess($guess){
		$this->numGuesses++;
		if($guess>$this->secretNumber){
			$this->state="too high";
		} else if($guess<$this->secretNumber){
			$this->state="too low";
		} else {
			$this->state="correct";
		}
		$this->history[] = "Guess #$this->numGuesses was $guess and was $this->state.";
	}

	public function playRPS($guess){
		$this->numGuesses++;

		if($guess == $this->secretNumber){
			$this->state="tied";
		}
		else if($guess == 1 && $this->secretNumber == 3){
			$this->state="win";
			$this->numWins++;
		}
		else if($guess == 2 && $this->secretNumber == 1){
			$this->state="win";
			$this->numWins++;

		}
		else if($guess == 3 && $this->secretNumber == 2){
			$this->state="win";
			$this->numWins++;
		}
		else{
			$this->state="lose";
			$this->numLoses++;
		}
		$temp = $this->convert($guess);
		$temp2 = $this->convert($this->secretNumber);
		$this->history[] = "Game #$this->numGuesses was $temp vs $temp2 and you $this->state.";
		$this->state="play";
		$this->secretNumber = rand(1,3);
	}

	public function convert($val){
		if($val == 1){
			return "rock";
		}
		if($val == 2){
			return "paper";
		}
		else{
			return "scissors";
		}
	}

	public function getState(){
		return $this->state;
	}

	public function getWins(){
		return $this->numWins;
	}

	public function getLoses(){
		return $this->numLoses;
	}
}
?>
