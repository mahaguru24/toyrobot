<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testingWrongFilename()
    {
        $this->setName('File Test - Providing file that does not exits');
        $this->artisan('toyRobot:file XPlor.txt')
            ->expectsOutput('File does not exist in path '.storage_path())
            ->assertExitCode(0);
    }

    public function testingWithTwoPlaceCommands()
    {
        $this->setName('file test - Two place commands');
        $this->artisan('toyRobot:file')
            ->expectsOutput('1,1,WEST');
    }
}
