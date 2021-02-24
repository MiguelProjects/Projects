<?php
class Frogs {

	// public function __construct($id) {

    // 	}

	public $zones = array();
    public $state = "";
    public $moves = 0;

	public function __construct() {
            $this->zones[0] = new Froggy(1);
            $this->zones[1] = new Froggy(1);
            $this->zones[2] = new Froggy(1);
            $this->zones[3] = new Froggy(0);
            $this->zones[4] = new Froggy(2);
            $this->zones[5] = new Froggy(2);
            $this->zones[6] = new Froggy(2);
            $this->state = "play";
            $this->moves = 0;
    	}

    public function getState(){
        return $this->state;
    }

    public function change($inp){
        if($this->zones[$inp]->getFace() == "L"){
        $this->zones[$inp]->setFace(2);
        }
        else if($this->zones[$inp]->getFace() == "R"){ 
        $this->zones[$inp]->setFace(1);
        }

        if($this->isWon() == true){
            $this->state = "won";
        }
        $this->checkStuck();
    }

    public function delta($inp){
        return $this->zones[$inp]->alpha();
    }

    public function getMoves(){
        $temp = "Number of moves ";
        $temp .= $this->moves;
        return $temp;
    }

    public function isWon(){
        for ($x = 0; $x <= 6; $x++) {
            $temp = $this->zones[$x]->getFace();
            if($x <= 2 &&$temp != 'R'){
                    return false;
            }
            if($x >= 4 &&$temp != 'L'){
                    return false;
            }
        }
        return true;
    }

    public function checkStuck(){
        for($x = 0; $x <= 6; $x++) {
            if($this->canMove($x) == true){
                return;
            }
        }
        $this->state = "stuck";
    }

    public function canMove($inp){
        if($this->zones[$inp]->getDirection() == 2){
            if($inp-1 >= 0 && $this->zones[$inp-1]->getDirection() == 0){
                return true;
            }//the thing next to you is empty
            if($inp-2 >= 0 && $this->zones[$inp-2]->getDirection() == 0){
                return true;
            }//the thing two steps infront is empty
        }
        else if($this->zones[$inp]->getDirection() == 1){
            if($inp+1 <= 6 && $this->zones[$inp+1]->getDirection() == 0){
                return true;
            }//the thing next to you is empty
            if($inp+2 <= 6 && $this->zones[$inp+2]->getDirection() == 0){
                return true;
            }//the thing two steps infront is empty
        }
        return false;
    }

    public function move($inp){
        //0 = empty
        //1 = facing right, aka a left frog
        //2 = facing left, aka a right frog
        if($this->zones[$inp]->getDirection() == 0){
            return false; 
        }
        if($inp == 6 && $this->zones[$inp]->getDirection() != 2){
            return false; 
        }
        else if($inp == 0 && $this->zones[$inp]->getDirection() != 1){
            return false; 
        }
        else if($this->zones[$inp]->getDirection() == 2){
            if($inp-1 >= 0 && $this->zones[$inp-1]->getDirection() == 0){
                $this->zones[$inp]->setFace(0);
                $this->zones[$inp-1]->setFace(2);
                $this->moves+=1;
            }//the thing next to you is empty
            if($inp-2 >= 0 && $this->zones[$inp-2]->getDirection() == 0){
                $this->zones[$inp]->setFace(0);
                $this->zones[$inp-2]->setFace(2);
                $this->moves+=1;
            }//the thing two steps infront is empty
        }
        else if($this->zones[$inp]->getDirection() == 1){
            if($inp+1 <= 6 && $this->zones[$inp+1]->getDirection() == 0){
                $this->zones[$inp]->setFace(0);
                $this->zones[$inp+1]->setFace(1);
                $this->moves+=1;
            }//the thing next to you is empty
            if($inp+2 <= 6 && $this->zones[$inp+2]->getDirection() == 0){
                $this->zones[$inp]->setFace(0);
                $this->zones[$inp+2]->setFace(1);
                $this->moves+=1;
            }//the thing two steps infront is empty
        }
        if($this->isWon() == true){
            $this->state = "won";
        }
        $this->checkStuck();
        
    }
}

class Froggy {

	public $facing = 0;
    public $image = "";

	public function __construct($inp) {
        	$this->setFace($inp);
    	}
	
	public function setFace($inp){
            switch($inp){
                case 0;
                $this->image = "  ";
                $this->facing = 0;
                break;
                case 1;
                $this->image = "L";
                $this->facing = 1;
                break;
                case 2;
                $this->image = "R";
                $this->facing = 2;
                break;
                default:
                $this->image = "  ";
                $this->facing = 0;
                break;
            }
    }

    public function alpha(){
        switch($this->image){
            case "L";
            return "https://axiom.utm.utoronto.ca/~csc309/21s/lectures/javascript/frogs/yellowFrog.gif";
            break;
            case "R";
            return "https://axiom.utm.utoronto.ca/~csc309/21s/lectures/javascript/frogs/greenFrog.gif";
            break;
            default:
            return "https://axiom.utm.utoronto.ca/~csc309/21s/lectures/javascript/frogs/empty.gif";
            break;
        }
	}

	public function getFace(){
		return $this->image;
	}

    public function getDirection(){
        return $this->facing;
    }

    public function trial(){
        if($this->image == "L"){
            $this->setFace(2);
            }
            else if($this->image == "R"){ 
            $this->setFace(1);
            }
    }
}

// gravyard of Frogs() methods 

	// public function getState(){
	// 	return $this->state;
	// }

    // public function getImage($inp){
    //     $this->sayName($inp);
    //     $this->zones[$inp]->sayFace();
    //     return $this->zones[$inp]->getFace();
    // }

    // public function sayName($inp){
    //     $this->zones[$inp]->sayFace();
    //     //$this->change($inp);
    //     echo "call method";
    // }

    // public function getName($inp){
    //     // $this->sayName($inp);
    //     return $this->zones[$inp]->getFace();
    //     // return "https://axiom.utm.utoronto.ca/~csc309/21s/lectures/javascript/frogs/yellowFrog.gif";
    // }
    // public function getDisplay(){
    //     $form = "<main>";
    //     $form .= "<section>";
    //     $form .= "<h1>Frogs</h1>";
    //     $form .= "<div class='frogs'>";
    //     $temp = 0;
    //     foreach ($this->zones as $frohg){
    //         $curr = $this->getName($temp);
    //         $form .= '<form method="post">';
    //         $form .= '<input type="submit" name="f' . $temp . '" value="' . $curr . '"';
    //         $form .= ' onclick="';
    //         $form .= '$_SESSION';
    //         $form .= "['Frogs']->";
    //         $form .= 'getImage"';
    //         $form .= "(". $temp. ")'/>";
    //         $form .= '</form>';
    //         $temp +=1;
    //     }
    //     $form .= "</div>";
    //     $form .= "</section>";
    //     $form .= "</main>";
    //     return $form;
    // }
?>