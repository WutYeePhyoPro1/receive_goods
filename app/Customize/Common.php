<?php
namespace App\Customize;

use App\Models\Log;

Class Common

{
    public static function Log($url,$text)
    {
        $log            = new Log();
        $log->user_id   = getAuth()->id;
        $log->history   = $url;
        $log->action    = $text;
        $log->save();
    }
}
