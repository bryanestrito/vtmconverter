<?php

use YoutubeDl\YoutubeDl;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;
use Carbon\Carbon;

class VideoDownloader
{
    protected $url;
    protected $downloadPath;
    protected $filename

    const DOWNLOAD_FOLDER = 'downloaded';

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function setDownloadPath()
    {
        $carbon = Carbon::now();
        $folderDate = $carbon->format('Y/m/d');

        $folderPath = self::DOWNLOAD_FOLDER . '/' . $folderDate . '/';

        $this->downloadPath = public_path($folderPath);
    }

    public function setFilename()
    {

    }

    public function downloadVideo($url)
    {
        try {
            $video = $dl->download('https://www.youtube.com/watch?v=oDAw7vW7H0c');

        } catch (NotFoundException $e) {
            // Video not found
        } catch (PrivateVideoException $e) {
            // Video is private
        } catch (CopyrightException $e) {
            // The YouTube account associated with this video has been terminated due to multiple third-party notifications of copyright infringement
        } catch (\Exception $e) {
            // Failed to download
        }
    }
}