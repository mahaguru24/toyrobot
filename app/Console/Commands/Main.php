<?php
namespace App\Console\Commands;

class Main
{

    /**
     * Global variables
     * @var
     */
    protected $positionX;
    protected $positionY;
    protected $positionF;
    public $robotPlaced = false;


    /**
     * Setting the board dimensions as constants
     */
    const xMin = 0;
    const xMax = 5;
    const yMin = 0;
    const yMax = 5;

    /**
     * Allowed commands and directions to check console and file inputs.
     * @var array
     */

    public $allowedCommands = ['PLACE', 'MOVE', 'LEFT', 'RIGHT', 'REPORT'];
    protected $allowedDirections = ['NORTH', 'SOUTH', 'EAST', 'WEST'];

    /**
     * Function to handle first place command. Check if the command is place.
     * @param $command
     * @return bool
     */
    public function handleFirstCommand($command){
        while (!preg_match('/^PLACE /', strtoupper($command))) return false;
        return preg_match('/^PLACE /', strtoupper($command)) && $this->placeRobot($command) ? true : false;
    }

    /**
     * Function to switch between game commands and move the robot according to it.
     * @param $nextCommand
     * @return bool|string
     */

    public function playCommands($nextCommand) {
        if (strtoupper($nextCommand) == 'MOVE') {
            switch (strtoupper($this->positionF)) {
                case 'NORTH':
                    $this->positionY ++;
                    if($this->withinBoardLimits($this->positionX, $this->positionY)){
                        return true;
                    }
                    else{
                        $this->positionY --;
                        return false;
                    }
                    break;
                case 'SOUTH':
                    $this->positionY --;
                    if($this->withinBoardLimits($this->positionX, $this->positionY)){
                        return true;
                    }
                    else{
                        $this->positionY ++;
                        return false;
                    }
                    break;
                case 'EAST':
                    $this->positionX ++;
                    if($this->withinBoardLimits($this->positionX, $this->positionY)){
                        return true;
                    }
                    else{
                        $this->positionX --;
                        return false;
                    }
                    break;
                case 'WEST':
                    $this->positionX --;
                    if($this->withinBoardLimits($this->positionX, $this->positionY)){
                        return true;
                    }
                    else{
                        $this->positionX ++;
                        return false;
                    }
                    break;
                default:
                    return false;
                    break;
            }
        }
        elseif (strtoupper($nextCommand) == 'LEFT') {
            switch (strtoupper($this->positionF)) {
                case 'SOUTH':
                    $this->positionF = 'EAST';
                    return true;
                    break;
                case 'EAST':
                    $this->positionF = 'NORTH';
                    return true;
                    break;
                case 'NORTH':
                    $this->positionF = 'WEST';
                    return true;
                    break;
                case 'WEST':
                    $this->positionF = 'SOUTH';
                    return true;
                    break;
                default:
                    return false;
                    break;
            }

        }
        elseif (strtoupper($nextCommand) == 'RIGHT') {
            switch (strtoupper($this->positionF)) {
                case 'SOUTH':
                    $this->positionF = 'WEST';
                    return true;
                    break;
                case 'WEST':
                    $this->positionF = 'NORTH';
                    return true;
                    break;
                case 'NORTH':
                    $this->positionF = 'EAST';
                    return true;
                    break;
                case 'EAST':
                    $this->positionF = 'SOUTH';
                    return true;
                    break;
                default:
                    return false;
                    break;
            }

        }
        elseif (strtoupper($nextCommand) == 'REPORT') {
            return $this->positionX.','.$this->positionY.','.strtoupper($this->positionF);
        }
        else {return false;}
    }


    /**
     * Check if the placement of robot is in the board limits
     * @param $x
     * @param $y
     * @return bool
     */
    public function withinBoardLimits($x, $y) {
        return $x>=self::xMin && $x<=self::xMax && $y>=self::yMin && $y<=self::yMax;
    }

    /**
     * Setting the value for x and y gets the values from the place command and places the robot on the board
     * @param $command
     * @return bool
     */
    public function placeRobot($command) {
        $command = str_replace('PLACE ', '', strtoupper($command));
        $values = explode(',', $command);

        //Check if there are three values sent or else the user needs to be asked for input again
        if (count($values) != 3 || !in_array(strtoupper($values[2]), $this->allowedDirections)) {
            return $this->robotPlaced;
        }
        else {
            //Check if the input is within board limits
            if ($this->withinBoardLimits((int)$values[0], (int)$values[1])){
                $this->positionX = (int)$values[0];
                $this->positionY = (int)$values[1];
                $this->positionF = $values[2];
                $this->robotPlaced = true;
                return $this->robotPlaced;
            }
            else {
                return $this->robotPlaced;
            }

        }
    }
}
