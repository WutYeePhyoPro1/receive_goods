<?php

namespace Pro1\Changelog\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\User;
use Pro1\Changelog\Models\WhatsNew;
use Pro1\Changelog\Models\ChangeLog;
use Pro1\Changelog\Models\SubChange;
use Illuminate\Http\Request;
use Pro1\Changelog\Models\ChangeLogFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Pro1\Changelog\Http\Resources\ChangeLogsResource;

class ChangeLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $change_type_id = $request->change_type_id;
        $title = $request->title;

        $results = ChangeLog::query();
        if(!empty($change_type_id) && $change_type_id){
            $results = $results->whereHas("subchanges",function($query) use($change_type_id){
                $query->where("change_type_id",$change_type_id);
            });
        }

        if (!empty($title)) {
            $loweredTitle = strtolower($title); // Convert input to lowercase

            $results = $results->where(function ($query) use ($loweredTitle) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%' . $loweredTitle . '%'])
                    ->orWhereHas('subchanges', function ($subquery) use ($loweredTitle) {
                        $subquery->whereRaw('LOWER(title) LIKE ?', ['%' . $loweredTitle . '%']);
                    });
        });
        }
        $changelogs = $results->latest()->paginate(10);

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

        // Validator
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "description" => "required",
            "release_type_id" => "required",
            "version_number" => "",
            "release_date" => "required",
            "priority_level_id" => "required",
            "status_id" => "required",
            "changes" => "array|required",
            "changes.*" => "required",
            "role_ids" => "array|required",
        ], [
            "changes.*" => "Please write changes information",
            "role_ids.required"=>"Please choose roles"
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()],422);
        }
        // dd($request);

        DB::beginTransaction();
        try{

            // $user = Auth::user();
            $user_id = $request->user_id;

            $changelog = new ChangeLog();
            $changelog->title = $request->title;
            $changelog->description = $request->description;
            $changelog->release_type_id = $request->release_type_id;
            $changelog->version_number = $request->version_number;
            $changelog->release_date = $request->release_date;
            $changelog->priority_level_id = $request->priority_level_id;
            $changelog->user_id = $user_id;
            $changelog->status_id = $request->status_id;
            $changelog->save();

            $role_ids = $request->role_ids;
            $changelog->roles()->sync($role_ids);


            $changes = $request->changes;
            foreach($changes as $change){
                $subchange = SubChange::create([
                    "change_log_id" => $changelog->id,
                    "title" => $change['title'],
                    "description" => $change['description'],
                    "change_type_id" => $change['change_type_id'],
                    "user_id" => $user_id,
                    // "assignee_id" =>
                ]);

                // Multi Images Upload
                // dd($change["mediafiles"][0]->move(public_path('assets/img/changelogs/'), $change["mediafiles"][0]->getClientOriginalName()));

                // dd($change["mediafiles"]);
                foreach($change["mediafiles"] ?? [] as $mediafile){
                    $changelogfile = new ChangeLogFile();
                    $changelogfile->change_log_id = $changelog->id;
                    $changelogfile->sub_change_id = $subchange->id;

                    $file = $mediafile;
                    $fname = $file->getClientOriginalName();
                    $imagenewname = uniqid($user_id).$changelog['id'].$fname;
                    $file->move(public_path('assets/img/changelogs/'),$imagenewname);


                    $filepath = 'assets/img/changelogs/'.$imagenewname;
                    $changelogfile->mediafile = $filepath;

                    $changelogfile->save();
                }
            }


            // What's New Notification

            $users = User::whereHas('roles',function($query) use($role_ids){
                $query->whereIn('id',$role_ids);
            })->get();
            $changelog->sendWhatsNews($users);


            DB::commit();

            // return redirect()->route('changelogs.index')->with('success',"Change Log Successfully Created.");

            return response()->json(["status"=>"success","data"=>$changelog]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            // return back()->with('error', 'An error occurred while processing your request. Please try again later.'.$e->getMessage());
        }
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
        // dd($request);
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "description" => "required",
            "release_type_id" => "required",
            "version_number" => "",
            "release_date" => "required",
            "priority_level_id" => "required",
            "status_id" => "required",
            "role_ids" => "array|required",
        ], [
            "role_ids.required"=>"Please choose roles"
        ]);
        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()]);
        }


        DB::beginTransaction();
        try{

            // $user = Auth::user();
            $user_id = $request->user_id;

            $changelog = ChangeLog::find($id);
            $changelog->title = $request->title;
            $changelog->description = $request->description;
            $changelog->release_type_id = $request->release_type_id;
            $changelog->version_number = $request->version_number;
            $changelog->release_date = $request->release_date;
            $changelog->priority_level_id = $request->priority_level_id;
            $changelog->user_id = $user_id;
            $changelog->status_id = $request->status_id;
            $changelog->save();


            $role_ids = $request->role_ids;
            $changelog->roles()->sync($role_ids);



            // What's New Notification
            $users = User::whereHas('roles',function($query) use($role_ids){
                $query->whereIn('id',$role_ids);
            })->get();
            $changelog->sendWhatsNews($users);

            // Remove inappropriate changelog
            WhatsNew::where('change_log_id',$changelog->id)
            ->whereNotIn("user_id",$users->pluck("id"))->delete();


             DB::commit();

            return response()->json(["status"=>"success","data"=>$changelog]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            // return back()->with('error', 'An error occurred while processing your request. Please try again later.'.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $changelog = ChangeLog::find($id);

            $changelog->subchanges()->delete();
            $changelog->roles()->detach();

            $changelogfiles = ChangeLogFile::where('change_log_id',$changelog->id)->get();
            foreach($changelogfiles as $changelogfile){
                $path = $changelogfile->mediafile;
                if(File::exists($path)){
                    File::delete($path);
                }
            }
            ChangeLogFile::where('change_log_id',$changelog->id)->delete();
            WhatsNew::where('change_log_id',$changelog->id)->delete();


            $changelog->delete();
            DB::commit();

            return response()->json(["status"=>"success","data"=>$changelog]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            // return back()->with('error', 'An error occurred while processing your request. Please try again later.'.$e->getMessage());
        }

    }

    public function changes(Request $request,$id){
        $changelog = ChangeLog::find($id);

        $changes = $changelog->subchanges()->with('changetype')->with('changelogfiles')->get();

        return response()->json($changes);
    }

    public function storechanges(Request $request,$id){

          // Validator
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "description" => "required",
            "change_type_id" => "required",
        ], [
            "change_type_id.required"=>"Please choose change type"
        ]);
        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()]);
        }

        DB::beginTransaction();
        try{
            $user_id = $request->user_id;

            $changelog = ChangeLog::find($id);
            $subchange = SubChange::create([
                "change_log_id" => $id,
                "title" => $request->title,
                "description" => $request->description,
                "change_type_id" => $request->change_type_id,
                "user_id" => $user_id,
                // "assignee_id" =>
            ]);

            if($request->hasFile('mediafiles')){
                foreach($request->file("mediafiles")as $mediafile){
                    $changelogfile = new ChangeLogFile();
                    $changelogfile->change_log_id = $id;
                    $changelogfile->sub_change_id = $subchange->id;

                    $file = $mediafile;
                    $fname = $file->getClientOriginalName();
                    $imagenewname = uniqid($user_id).$changelog['id'].$fname;
                    $file->move(public_path('assets/img/changelogs/'),$imagenewname);


                    $filepath = 'assets/img/changelogs/'.$imagenewname;
                    $changelogfile->mediafile = $filepath;

                    $changelogfile->save();
                }
            }


            DB::commit();

            return response()->json(["status"=>"success","data"=>$subchange]);

        }catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            // return back()->with('error', 'An error occurred while processing your request. Please try again later.'.$e->getMessage());
        }

    }

    public function updatechanges(Request $request,$id,$changeid){
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "description" => "required",
            "change_type_id" => "required",
        ], [
            "change_type_id.required"=>"Please choose change type"
        ]);
        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()]);
        }

        try{

            $user_id = $request->user_id;

            $changelog = ChangeLog::find($id);
            $subchange = Subchange::find($changeid);

            $subchange->update([
                "change_log_id" => $id,
                "title" => $request->title,
                "description" => $request->description,
                "change_type_id" => $request->change_type_id,
                "user_id" => $user_id,
                // "assignee_id" =>,

            ]);

            ChangeLogFile::where('sub_change_id', $subchange->id)
            ->whereNotIn('id', $request->change_ids ?? [])
            ->delete();

            if($request->hasFile('mediafiles')){
                foreach($request->file("mediafiles")as $mediafile){
                    $changelogfile = new ChangeLogFile();
                    $changelogfile->change_log_id = $id;
                    $changelogfile->sub_change_id = $subchange->id;

                    $file = $mediafile;
                    $fname = $file->getClientOriginalName();
                    $imagenewname = uniqid($user_id).$changelog['id'].$fname;
                    $file->move(public_path('assets/img/changelogs/'),$imagenewname);


                    $filepath = 'assets/img/changelogs/'.$imagenewname;
                    $changelogfile->mediafile = $filepath;

                    $changelogfile->save();
                }
            }



            DB::commit();

            return response()->json(["status"=>"success","data"=>$subchange]);
        }catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            // return back()->with('error', 'An error occurred while processing your request. Please try again later.'.$e->getMessage());
        }


    }


    public function deletechanges(Request $request,$id,$changeid){
        $subchange = Subchange::find($changeid);

        $changelogfiles = ChangeLogFile::where('sub_change_id',$changeid)->get();
        foreach($changelogfiles as $changelogfile){
            $path = $changelogfile->mediafile;
            if(File::exists($path)){
                File::delete($path);
            }
        }

        ChangeLogFile::where('sub_change_id',$changeid)->delete();

        $subchange->delete();

        return response()->json(["status"=>"success","data"=>$subchange]);
    }

    public function agree(Request $request,$id){
        $changelog = ChangeLog::find($id);
        $user = User::where("user_id",$request->user_id);

        $whatsnew = WhatsNew::where("change_log_id",$id)
                    ->where("user_id",$request->user_id)->first();

        $whatsnew->update([
            "read_at" => now()
        ]);
        return response()->json(["status"=>"success","data"=>$whatsnew]);

    }


    public function dashboard(){
        $changelogs = ChangeLog::all();
        $subchanges = SubChange::all();
        $datas = [
            "totalchangelogs" => $changelogs->count(),
            "all" => $subchanges->count(),
            "bugfixes" => $subchanges->where("change_type_id",1)->count(),
            "improvements" => $subchanges->where("change_type_id",2)->count(),
            "newfeatures" => $subchanges->where("change_type_id",3)->count(),
        ];

        return response()->json($datas);
    }
}
