<?php
namespace App\Customize;

use App\Models\Branch;
use App\Models\Log;

Class Common

{
    public static function Log($url,$text)
    {
        $log            = new Log();
        $log->user_id   = getAuth()->id;
        $log->history   = $url;
        $log->action    = $text;
        // $log->save();
    }

    public static function branch_data()
    {
        $branch = Branch::get();
        view()->share(['branch'=>$branch]);
    }
}
