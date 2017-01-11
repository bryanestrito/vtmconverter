<?php

require __DIR__.'/vendor/autoload.php';

use Carbon\Carbon as Carbon;

class VTMConverter
{
    const PHP_ENV = "cli";

    // commands
    const YOUTUBEDL_CMD = "youtube-dl";
    const FFMPEG_CMD = "ffmpeg";

    // folders
    const DOWNLOAD_FOLDER = "/downloaded/";
    const CONVERTED_FOLDER = "/converted/";

    const FILE_EXTENSION = "m4a";

    const ARGUMENT_COUNT = 3;

    // arguments
    public $youtubeUrl;
    public $title;
    public $artist;

    public $filename;
    public $duration;

    public function __construct($argv)
    {
        if (PHP_SAPI != self::PHP_ENV) {
            return;
        }

        $this->vtmconvert($argv);
    }

    public function vtmconvert($argv)
    {
        try {
            $this->checkAndAssignArguments($argv);

            $this->checkCommandsExistence();

            $this->generateFilename();

            // $this->downloadVideo();

            $this->getVideoDuration();

            $this->stripStartTime();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function stripStartTime()
    {
        $startTime = explode(":", $this->startTime);

        switch (count($startTime)) {
            case 3:
                # code...
                break;
            case 2:
                # code...
                break;
            case 1:
                # code...
                break;
        }
    }

    public function checkAndAssignArguments($argv)
    {
        if (count($argv) < self::ARGUMENT_COUNT) {
            throw new Exception("Insufficient argument(s)");
        }

        $this->youtubeUrl = $argv[1];
        $this->title = $argv[2];
        $this->artist = $argv[3];
        $this->startTime = $argv[4];
        $this->endTime = $argv[5];
    }

    public function checkCommandsExistence()
    {
        $commands = [
            self::YOUTUBEDL_CMD,
            self::FFMPEG_CMD
        ];

        foreach ($commands as $command) {
            if (!$this->commandExists($command)) {
                throw new Exception($command . " is not installed");
            }
        }
    }

    public function generateFilename()
    {
        $this->filename = $this->title . " - " . $this->artist;
    }

    public function downloadVideo()
    {
        $location = __DIR__ . self::DOWNLOAD_FOLDER;

        $execute = "youtube-dl -f 140 --output " . $location . "\"{$this->filename}\".%\(ext\)s " . $this->youtubeUrl;

        echo shell_exec($execute);
    }

    public function commandExists($command)
    {
        $executable = shell_exec(sprintf("which %s", escapeshellarg($command)));

        return !empty($executable);
    }

    public function getVideoDuration()
    {
        $location = __DIR__ . self::DOWNLOAD_FOLDER . $this->filename . "." . self::FILE_EXTENSION;

        $duration = shell_exec("ffmpeg -i \"{$location}\" 2>&1 | grep Duration | awk '{print $2}' | tr -d ,");

        return $this->convertTimeToSeconds($duration);
    }

    public function convertTimeToSeconds($duration)
    {
        $time = explode(":", $duration);

        list($hours, $minutes, $seconds) = $time;

        $hours = $hours * 3600;

        $minutes = $minutes * 60;

        return $hours + $minutes + $seconds;
    }

}

$vtmconverter = new VTMConverter($argv);
