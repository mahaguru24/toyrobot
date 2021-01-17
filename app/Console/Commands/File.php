<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class File extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toyRobot:file {filename=test.txt}';
    private $main;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Play the toy robot game using txt File input';

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
        $filename = $this->argument('filename');
        //Check if file exists
        if (file_exists(storage_path($filename))){
            $fileInfo = pathinfo(storage_path($filename));
            if($fileInfo['extension'] == 'txt'){
                //read txt file
                $file = file(storage_path($filename));
                foreach ($file as $line) {
                    $command = trim(preg_replace('/\s\s+/', ' ', $line));
                    //Check if robot is placed or send to first command
                    if (!$this->main->robotPlaced) {
                        //If report command was issued before placing
                        if(strtoupper($command) === 'REPORT'){
                            $this->error('Robot was not placed in the file, exiting');
                            return 0;
                        }
                        $this->main->handleFirstCommand($command);
                    } else {
                        if (in_array(strtoupper($command), $this->main->allowedCommands) && !preg_match('/^PLACE /', strtoupper($command))) {
                            if(strtoupper($command) === 'REPORT' && $this->main->playCommands($command)){
                                $this->warn($this->main->playCommands($command));
                                return 1;
                            } else {
                                $this->main->playCommands($command);
                            }
                        } elseif (preg_match('/^PLACE /', strtoupper($command))){
                            $this->main->handleFirstCommand($command);
                        }

                    }
                }
            }
        }
        else {
            $this->warn('File does not exist in path '.storage_path());
            return 0;
        }


    }
}
