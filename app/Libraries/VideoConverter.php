<?php

namespace App\Libraries;

use FFMpeg\FFMpeg;

class VideoConverter
{
    protected $mediaFile;
    protected $filename;

    protected $tags;

    // cutting
    protected $startTime;
    protected $endTime;

    const DOWNLOAD_FOLDER = '/downloads/';
    const CONVERTER_FOLDER = '/converted/';

    public function __construct($mediaFile, $filename = null, $tags = null)
    {
        $this->mediaFile = $mediaFile;

        $this->setFilename($filename);
        $this->setID3v2Tags($tags);
    }

    public function setFilename($filename)
    {
        if (!$filename) {
            $filename = 'something';
        }

        $this->filename = $filename;
    }

    public function setID3v2Tags($tags)
    {
        $ffmpegTags = ['title', 'artist', 'album'];

        foreach ($tags as $tag => $value) {
            if (!in_array($tag, $ffmpegTags)) {
                throw new \Exception("Invalid meta tag!");
            }
        }

        $this->tags = $tags;
    }

    public function cutStartEndTime($startTime = null, $endTime = null)
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    public function convertAudio()
    {
        $ffmpeg = FFMpeg::create();
        $audio = $ffmpeg->open($this->mediaFile);

        // waveform image
        $waveform = $audio->waveform(640, 120);
        $waveform->save(public_path() . self::CONVERTER_FOLDER . 'waveform.png');

        // // meta data
        // $audio->filters()->addMetadata($this->tags);

        // // format
        // $format = new FFMpeg\Format\Audio\Aac();
        // $format->setAudioKiloBitrate(256);

        // $audio->save($format, public_path(). self::CONVERTER_FOLDER . $this->filename);
    }
}