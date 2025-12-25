<?php

namespace Pro1\Changelog\Http\Controllers\Api;

use Pro1\Changelog\Models\WhatsNew;
use Pro1\Changelog\Models\ChangeLog;
use Illuminate\Http\Request;
use Pro1\Changelog\Http\Controllers\Controller;
use Pro1\Changelog\Http\Resources\ChangeLogsResource;

class WhatsNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $whatsnews = WhatsNew::getAuthUserWhatNews();
        $changelog_ids = $whatnews->pluck("change_log_id");
        $changelogs = ChangeLog::whereIn("id",$changelog_ids)->get();

        return  ChangeLogsResource::collection($changelogs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
