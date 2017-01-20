<?php

// require __DIR__.'/vendor/autoload.php';

// use Carbon\Carbon as Carbon;

class VTMConverter
{
    // commands
    const YOUTUBEDL_CMD = "youtube-dl";
    const FFMPEG_CMD = "ffmpeg";

    // folders
    const DOWNLOAD_FOLDER = "/downloaded/";
    const CONVERTED_FOLDER = "/converted/";

    const FILE_EXTENSION = "m4a";

    const ARGUMENT_COUNT = 1;

    // arguments
    protected $url;

    protected $startTime;
    protected $endTime;

    protected $title;
    protected $artist;
    protected $album;

    protected $filename;
    protected $duration;

    public function __construct($url)
    {
        // $this->checkAndAssignArguments($arguments);

        $this->setUrl($url);
    }

    // public function vtmconvert($argv)
    // {
    //     try {
    //         $this->checkAndAssignArguments($argv);

    //         $this->checkCommandsExistence();

    //         $this->generateFilename();

    //         // $this->downloadVideo();

    //         $this->getVideoDuration();

    //         $this->stripStartTime();
    //     } catch (Exception $e) {
    //         echo $e->getMessage();
    //     }
    // }

    // public function stripStartTime()
    // {
    //     $startTime = explode(":", $this->startTime);

    //     switch (count($startTime)) {
    //         case 3:
    //             # code...
    //             break;
    //         case 2:
    //             # code...
    //             break;
    //         case 1:
    //             # code...
    //             break;
    //     }
    // }

    protected function setUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid URL received");
        }

        $this->url = $url;
    }

    protected function setTime($startTime = null, $endTime = null)
    {
        // optional hour/millseconds pattern
        // $pattern = '^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)(\.[0-9]{1,3})?$';

        // enforced time format pattern
        $pattern = '(?:[01]\d|2[0123]):(?:[012345]\d):(?:[012345]\d)\.(?:[0-9]{1,3})$';

        if ($startTime) {
            $timeFormat = preg_match($pattern, $startTime);

            if (!$timeFormat) {
                throw new Exception("Incorrect time format");
            }

            $this->startTime = $startTime;
        } else {
            $startTime = "00:00:00.000";
        }

        if ($endTime) {
            $timeFormat = preg_match($pattern, $endTime);

            if (!$timeFormat) {
                throw new Exception("Incorrect time format");
            }

            $this->endTime = $endTime;
        }
    }

    // public function checkAndAssignArguments($arguments)
    // {
    //     if (!is_array($arguments)) {
    //         throw new Exception("Only accept array argument(s)");
    //     }

    //     if (count($this->arguments) < self::ARGUMENT_COUNT) {
    //         throw new Exception("Insufficient argument(s)");
    //     }

    //     foreach ($variable as $key => $value) {
    //         # code...
    //     }

    //     $this->setUrl($arguments[1]);

    //     if (count($arguments) == 2) {
    //         $this->setTtitle($arguments[2]);
    //     }

    //     if (count($arguments) == 3) {
    //         $this->setArtist($arguments[3]);
    //     }

    //     if (count($arguments) == 4) {
    //         $this->setAlbum($arguments[4]);
    //     }
    // }

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

        $execute = "youtube-dl -f 140 --output " . $location . "\"{$this->filename}\".%\(ext\)s " . $this->url;

        echo shell_exec($execute);
    }

    private function commandExists($command)
    {
        $executable = shell_exec(sprintf("which %s", escapeshellarg($command)));

        return !empty($executable);
    }

    private function getVideoDuration()
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

// if the script is executed through terminal
// if (PHP_SAPI == "cli") {
//     $vtmconverter = new VTMConverter($argv);
// }
