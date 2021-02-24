<?php
class DataStore{
    //user vars
    public $name = '';
    public $password = "";
    public $birthday = "";
    public $gender = "";
    public $status = "";
    public $updateName = "";
    public $citizen = "";
    //guessgame vars
    public $guessGuesses = 0;
    public $guessWins = 0;
    //rps vars
    public $rpsWins = 0;
    public $rpsLoses = 0;
    public $rpsPlayed = 0;
    //frog vars
    public $frogsWins = 0;
    public $frogsResets = 0;
    public $frogsStucks = 0;

	public function __construct($userName, $userPass){
        $this->password = $userPass;
        $this->name = $userName;
        $ismade = $this->getData($this->name);
    }

    public function getData($input){
        $query = "SELECT gender, birthday, status, citizenship, guessGuess, guessWins, rpsWins, rpsLoses, rpsPlayed, frogsWins, frogsResets, frogsStucks FROM appuser where userid=$1";
        $result = @pg_prepare(db_connect(), "",$query);
		$result= @pg_execute(db_connect(), "",array($this->name)); 
		
        if ($result==false) {
		echo "An error occurred while getting data.\n";
		return false;
		}

		while ($row = pg_fetch_row($result)) {
		$this->gender = $row[0];
        $this->birthday = $row[1];
        $this->status = $row[2];
        $this->citizen = $row[3];
        $this->guessGuesses = $row[4];
        $this->guessWins = $row[5];
        $this->rpsWins = $row[6];
        $this->rpsLoses = $row[7];
        $this->rpsPlayed = $row[8];
        $this->frogsWins = $row[9];
        $this->frogsResets = $row[10];
        $this->frogsStucks = $row[11];
		}
        return true;
	}

    public function obtainMaxName($stat){
		$query = "SELECT userid FROM appuser where $stat= (select max($stat) FROM appuser)";
        $result = @pg_prepare(db_connect(), "",$query);
		$result= @pg_execute(db_connect(), "",array());
		if ($result==false) {
		echo "An error occurred obtaining a max stat name retrieval.\n";
		return false;
        }
        while ($row = pg_fetch_row($result)) {
            $temp = $row[0];
            }
            return $temp;
    }
    public function obtainMaxStat($stat){
		$query = "SELECT max($stat) FROM appuser";
        //we pass it into the query directly because if we pass it via execute it gives it as 'name' and not name (yes the little quotation marks)
        $result = @pg_prepare(db_connect(), "",$query);
		$result= @pg_execute(db_connect(), "",array()); 
		if ($result==false) {
		echo "An error occurred obtaining a max stat retrieval.\n";
		return false;
        }
        while ($row = pg_fetch_row($result)) {
            $temp = $row[0];
            }
            return $temp;
    }

    public function obtainNStat($stat, $n){
        $query = "SELECT $stat FROM appuser ORDER BY $stat DESC LIMIT 1 offset $n";
        $result = @pg_prepare(db_connect(), "",$query);
		$result= @pg_execute(db_connect(), "",array()); 
        $temp = -1;
		if ($result==false) {
		echo "An error occurred obtaining a stat.\n";
		return false;
        }
        while ($row = pg_fetch_row($result)) {
            $temp = $row[0];
            }
            return $temp;
    }

    public function obtainNName($stat, $n){
        $query = "SELECT userId FROM appuser ORDER BY $stat DESC LIMIT 1 offset $n";
        $result = @pg_prepare(db_connect(), "",$query);
		$result= @pg_execute(db_connect(), "",array()); 
        $temp = "N/A";
		if ($result==false) {
		echo "An error occurred obtaining a stat.\n";
		return false;
        }
        while ($row = pg_fetch_row($result)) {
            $temp = $row[0];
            }
            return $temp;
    }
    
    public function obtain($stat){
		$query = "SELECT $stat FROM appuser where userid='$this->name'";
        $result = @pg_prepare(db_connect(), "",$query);
		$result= @pg_execute(db_connect(), "",array()); 
		if ($result==false) {
		echo "An error occurred obtaining a stat.\n";
		return false;
        }
        while ($row = pg_fetch_row($result)) {
            $temp = $row[0];
            }
            return $temp;
    }

    //the @ silences error echoes but does not stop the result from occuring - it allows us to fail silently 
    public function update(){
        $query = "UPDATE appuser SET guessGuess = $1, guessWins = $2, rpsWins = $3, rpsLoses = $4, rpsPlayed = $5, frogsWins = $6, frogsResets = $7, frogsStucks = $8 WHERE userid = '$this->name'";
        $result= @pg_prepare(db_connect(), "",$query);
        $result= @pg_execute(db_connect(), "", array($this->guessGuesses, $this->guessWins, $this->rpsWins, $this->rpsLoses, $this->rpsPlayed, $this->frogsWins,$this->frogsResets,$this->frogsStucks));

		if ($result==false) {
		echo "An error occurred during an update.\n";
		return false;
        }
        return true;
    }

    public function direUpdate(){
        $pass = md5($this->password);
        $query = "UPDATE appuser SET userid = $1, password = $2, gender = $3, birthday = $4, status = $5, citizenship=$6 WHERE userid = '$this->name'";
        $result = @pg_prepare(db_connect(), "",$query);
        $result = @pg_execute(db_connect(), "", array($this->updateName, $pass, $this->gender,$this->birthday,$this->status, $this->citizen));

		if ($result==false) {
		echo "An error occurred during a dire update.\n";
		return false;
        }
        return true;
    }

}
?>

