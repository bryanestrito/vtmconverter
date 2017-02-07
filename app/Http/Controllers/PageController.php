<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\VideoDownloader;

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

        $videoDownloader = new VideoDownloader($url);
        $videoDownloader->downloadVideo('Prom - Sugarfree');
    }
}
