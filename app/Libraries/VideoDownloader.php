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
    public $title;
    public $artist;

    const DOWNLOAD_FOLDER = '/downloads/';

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function composeDownloadPath()
    {
        $folderPath = Carbon::now()->format('Y/m/d');

        return public_path() . self::DOWNLOAD_FOLDER . $folderPath;
    }

    public function generateDownloadPath()
    {
        $downloadPath = $this->composeDownloadPath();

        if (!$this->createDownloadFolder($downloadPath)) {
            throw new Exception("Cannot create download folder");
        }

        return $downloadPath;
    }

    public function createDownloadFolder($downloadPath)
    {
        if (!is_dir($downloadPath)) {
            return mkdir($downloadPath, 0755, true);
        }

        return true;
    }

    public function generateFilename($filename = null)
    {
        if (!$filename) {
            $filename = Carbon::now()->format('YmdHis');
        }

        return $filename;
    }

    public function downloadVideo($filename = null)
    {
        $downloadPath = $this->generateDownloadPath();
        $filename = $this->generateFilename($filename);

        $options = [
            'format' => '140',
            'output' => "{$filename}.%(ext)s"
        ];

        $youtubeDl = new YoutubeDl($options);
        $youtubeDl->setDownloadPath($downloadPath);

        try {
            $video = $youtubeDl->download($this->url);
            echo $video->getTitle(); // Will return Phonebloks
            // $video->getFile(); // \SplFileInfo instance of downloaded file
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
