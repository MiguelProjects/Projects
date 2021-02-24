<?php

class RPS {
	public $secretNumber = 5;
	public $numGuesses = 0;
	public $numWins = 0;
	public $numLoses = 0;
	public $history = array();
    public $temp = "";

	public function __construct() {
        	$this->secretNumber = rand(1,3);
    	}

	public function playRPS($guess){
		$this->numGuesses++;

		if($guess == $this->secretNumber){
			$this->temp="tied";
		}
		else if($guess == 1 && $this->secretNumber == 3){
			$this->temp="win";
			$this->numWins++;
		}
		else if($guess == 2 && $this->secretNumber == 1){
			$this->temp="win";
			$this->numWins++;

		}
		else if($guess == 3 && $this->secretNumber == 2){
			$this->temp="win";
			$this->numWins++;
		}
		else{
			$this->temp="lose";
			$this->numLoses++;
		}
		$temp1 = $this->convert($guess);
		$temp2 = $this->convert($this->secretNumber);
		$this->history[] = "Game #$this->numGuesses was $temp1 vs $temp2 and you $this->temp.";
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

	// public function getState(){
	// 	return $this->state;
	// }

	public function getWins(){
		return $this->numWins;
	}

	public function getLoses(){
		return $this->numLoses;
	}
}
?>
