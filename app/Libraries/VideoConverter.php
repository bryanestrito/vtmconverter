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

        foreach ($tags as $t => $tag) {
            if (!in_array($tag, $ffmpegTags)) {
                throw new Exception("Invalid meta tag!");
            }
        }

        $this->tags = $tags;
    }

    public function cutStartEndTime($startTime = null, $endTime = null)
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
}