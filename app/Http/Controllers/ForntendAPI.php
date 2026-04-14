<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoType;
use App\Models\VideoInfo;

class ForntendAPI extends Controller
{
    //
    public function videoType()
    {
        $VideoType = VideoType::all();

        return response([
            "message" => "success",
            "data" => $VideoType
        ], 200);
    }

    public function videoInfo()
    {
        $VideoInfo = VideoInfo::all();

        return response([
            "message" => "success",
            "data" => $VideoInfo
        ]);
    }
}
