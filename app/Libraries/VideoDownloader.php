<?php

namespace App\Libraries;

use YoutubeDl\YoutubeDl;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;
use Carbon\Carbon;

class VideoDownloader
{
    public $url;
    public $youtubeID;
    public $downloadPath;

    const DOWNLOAD_FOLDER = '/downloads/';

    public function __construct($url)
    {
        $this->url = $url;

        $this->generateDownloadPath();
    }

    public function composeDownloadPath()
    {
        // $folderPath = Carbon::now()->format('Y/m/d');

        // return public_path() . self::DOWNLOAD_FOLDER . $folderPath;

        return public_path() . self::DOWNLOAD_FOLDER;
    }

    public function createDownloadFolder($downloadPath)
    {
        if (!is_dir($downloadPath)) {
            return mkdir($downloadPath, 0755, true);
        }

        return true;
    }

    public function generateDownloadPath()
    {
        $downloadPath = $this->composeDownloadPath();

        if (!$this->createDownloadFolder($downloadPath)) {
            throw new Exception("Cannot create download folder");
        }

        $this->downloadPath = $downloadPath;
    }

    public function getYoutubeID()
    {
        parse_str(parse_url($this->url, PHP_URL_QUERY), $queryString);

        if (!array_key_exists('v', $queryString)) {
            throw new Exception("Unable to find youtube id");
        }

        return $queryString['v'];
    }

    public function downloadVideo()
    {
        $youtubeID = $this->getYoutubeID();

        $options = [
            'format' => '140',
            'output' => "{$youtubeID}.%(ext)s"
        ];

        $youtubeDl = new YoutubeDl($options);
        $youtubeDl->setDownloadPath($this->downloadPath);

        try {
            $video = $youtubeDl->download($this->url);

            return $video->getId();
        } catch (NotFoundException $e) {
            dd('Video not found');
        } catch (PrivateVideoException $e) {
            dd('Video is private');
        } catch (CopyrightException $e) {
            dd('The YouTube account associated with this video has been terminated due to multiple third-party notifications of copyright infringement');
        } catch (\Exception $e) {
            dd($e);
            dd('Failed to download');
        }
    }
}
