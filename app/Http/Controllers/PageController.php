<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\VideoDownloader;
use App\Libraries\VideoConverter;

class PageController extends Controller
{
    public function home(Request $request)
    {
        return view('pages.home');
    }

    public function convert(Request $request)
    {

    }

    public function download()
    {
        $url = "https://www.youtube.com/watch?v=iuZqraRSLaE&list=PLmCX3nN4cbi5GRa2vyl2jyXypOK6Lank4&index=4";
        $title = "Prom - Sugarfree";
        $tags = [
            'title' => 'Prom',
            'artist' => 'Sugarfree',
            'album' => 'Dramachine'
        ];

        $downloader = new VideoDownloader($url);
        $mediaFile = $downloader->downloadVideo();

        $filename = explode('.', $mediaFile);
        $filename = $filename[0];

        $filename = str_replace($filename, $title, $mediaFile);

        $converter = new VideoConverter($mediaFile, $filename, $tags);
        $converter->convertAudio();
    }
}
