<?php

namespace Pro1\Changelog\Http\Controllers;

use App\Http\Controllers\Controller;
use Pro1\Changelog\Models\WhatsNew;
use Pro1\Changelog\Models\ChangeLog;
use Illuminate\Http\Request;
use Pro1\Changelog\Http\Resources\ChangeLogsResource;

class WhatsNewsController extends Controller
{
    public function index(Request $request){
        // $changelog_ids = $whatnews->pluck("change_log_id");
        // $changelogs = ChangeLog::whereIn("id",$changelog_ids)->get();

        // ajax route
        if($request->ajax()){
            $whatsnews = WhatsNew::getAuthUserWhatNews($request->status);
            $changelog_ids = $whatsnews->pluck("change_log_id");
            $changelogs = ChangeLog::whereIn("id",$changelog_ids)->get();

            return  ChangeLogsResource::collection($changelogs);
        }

        return view('changelogs::whatsnews.index');
    }


}
