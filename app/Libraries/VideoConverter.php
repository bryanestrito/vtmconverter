<?php

class VideoConverter
{
    protected $filename;

    // id v3 tags
    protected $title;
    protected $artist;
    protected $album;

    // cutting
    protected $startTime;
    protected $endTime;

    const DOWNLOAD_FOLDER = '/downloads/';
    const CONVERTER_FOLDER = '/converted/';

    public function __construct($mediaFile, $filename)
    {
        $this->mediaFile = $mediaFile;
        $this->filename = $filename;
    }

    public function setID3v2Tags($tags)
    {
        # code...
    }
}