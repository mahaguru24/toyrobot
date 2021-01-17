<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConsoleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testConsole()
    {
        $this->setName('console test - Simple Console Test');
        $this->artisan('toyRobot:console')
            ->expectsQuestion('Please place the robot', 'PLACE 0,1,NORTH')
            ->expectsQuestion('Next Command','MOVE')
            ->expectsQuestion('Next Command', 'MOVE')
            ->expectsQuestion('Next Command', 'report')
            ->expectsOutput('0,3,NORTH')
            ->assertExitCode(0);
    }

    public function testConsoleReportWithoutPlace()
    {
        $this->setName('console test - Not proividing place command and issuing a report command');
        $this->artisan('toyRobot:console')
            ->expectsQuestion('Please place the robot', 'MOVE')
            ->expectsQuestion('Please place the robot', 'report')
            ->expectsOutput('Robot not placed, exiting')
            ->assertExitCode(0);
    }

    public function testConsolePlaceCommandOutsideBoard()
    {
        $this->setName('console test - place command outside board');
        $this->artisan('toyRobot:console')
            ->expectsQuestion('Please place the robot', 'PLACE 6,6,NORTH')
            ->expectsOutput('You need to place the Robot correctly, Please check your input')
            ->expectsQuestion('Please place the robot', 'report')
            ->expectsOutput('Robot not placed, exiting')
            ->assertExitCode(0);
    }
}
