<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use function GuzzleHttp\Psr7\_caseless_remove;
use function Psy\debug;

class Console extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toyRobot:console';
    private $main;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Play Toy Robot game through the Console';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Main $main)
    {
        parent::__construct();
        $this->main = $main;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Check if robot is placed or send to first command
        if (!$this->main->robotPlaced) {
            $command = $this->ask('Please place the robot');
            if(!$this->main->handleFirstCommand($command)){
                //If report command was issued before placing
                if(strtoupper($command) === 'REPORT'){
                    $this->error('Robot not placed, exiting');
                    return 0;
                }
                $this->warn('You need to place the Robot correctly, Please check your input');
                $this->handle();
            }
            else{
                $this->warn('Robot Placed');
                $this->handle();
            }
        } else {
            $nextCommand = $this->ask('Next Command');
            if (in_array(strtoupper($nextCommand), $this->main->allowedCommands) && !preg_match('/^PLACE /', strtoupper($nextCommand))) {
                if(strtoupper($nextCommand) === 'REPORT'){
                    $this->warn($this->main->playCommands($nextCommand));
                    return 1;
                } elseif ($this->main->playCommands($nextCommand)) {
                    $this->handle();
                }
                else{
                    $this->warn('You are on the limit, go back or turn');
                    $this->handle();
                }
            } elseif (preg_match('/^PLACE /', strtoupper($nextCommand))) {
                $this->main->handleFirstCommand($nextCommand);
                $this->handle();
            }
            else {
                $this->warn('wrong input, please use the right commands');
                $this->handle();
            }
        }
    }
}
