<?php

namespace Pro1\Changelog\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Pro1\Changelog\Models\Status;
use Pro1\Changelog\Models\WhatsNew;
use Pro1\Changelog\Models\ChangeLog;
use Pro1\Changelog\Models\SubChange;
use Pro1\Changelog\Models\ChangeType;
use Pro1\Changelog\Models\ReleaseType;
use Pro1\Changelog\Models\Authorizable;
use Illuminate\Http\Request;
use Pro1\Changelog\Models\ChangeLogFile;
use Pro1\Changelog\Models\PriorityLevel;
use Illuminate\Support\Facades\DB;
use Pro1\Changelog\Models\BaseRole as Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ChangeLogsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:view-change-log', ['only' => ['index','show']]);
        // $this->middleware('permission:create-change-log', ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit-change-log', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete-change-log', ['only' => ['destroy']]);
    }

    public function index(Request $request){
        // $changelogs = ChangeLog::orderBy('created_at','desc')->get();

        $title = $request->title;
        $document_from_date     = $request->document_from_date;
        $document_to_date       = $request->document_to_date;


        $result                 = ChangeLog::query();
        if (!empty($title)) {
            $result = $result->where('title',"like","%".$title."%");
        }

        if (!empty($document_from_date) || !empty($document_to_date)) {
            // $result = $result->where('created_at', $document_from_date);
            if($document_from_date === $document_to_date)
            {
                $result = $result->whereDate('created_at', $document_from_date);
            }
            else
            {
                if($document_from_date && $document_to_date){
                    $from_date = Carbon::parse($document_from_date);
                    $to_date = Carbon::parse($document_to_date)->endOfDay();
                    $result = $result->whereBetween('created_at', [$from_date , $to_date]);
                }else if($document_from_date){
                    $result = $result->whereDate('created_at', ">=", $document_from_date);
                }else if($document_to_date){
                    $result = $result->whereDate('created_at', "<=", $document_to_date);
                }
            }
        }

        // $isAdmin = Auth::user()->isAdmin();
        // if($isAdmin){
            $changelogs = $result->latest()->paginate(10);
        // }else{
        //     $roles_ids = Auth::user()->roles()->pluck('id');
        //     $changelogs = $result->whereHas('roles',function($query) use($roles_ids){
        //         $query->whereIn('roles.id',$roles_ids);
        //     })
        //     ->latest()->paginate(10);
        // }



        return view('changelogs::changelogs.index',compact('changelogs'));
    }

    public function create(Request $request){
        $roles = Role::all();
        $statuses = Status::whereIn('id',[7,19,20])->get();
        $releasetypes = ReleaseType::where('status_id',1)->get();
        $prioritylevels = PriorityLevel::where('status_id',1)->get();

        return view('changelogs::changelogs.create',compact('roles',"statuses","releasetypes","prioritylevels"));
    }



    public function show(Request $request,$id){
        $changelog = ChangeLog::find($id);

        $whatsnews = WhatsNew::getAuthUserWhatNews();
        return view('changelogs::changelogs.show',compact('changelog', "whatsnews"));
    }

    public function edit(Request $request,$id){
        $changelog = ChangeLog::find($id);
        $changetypes = ChangeType::all();
        $roles = Role::all();
        $statuses = Status::whereIn('id',[7,19,20])->get();
        $releasetypes = ReleaseType::where('status_id',1)->get();
        $prioritylevels = PriorityLevel::where('status_id',1)->get();


        $role_ids = $changelog->roles->pluck('id')->toArray();
        return view('changelogs::changelogs.edit',compact('changelog','roles',"statuses","releasetypes","prioritylevels"));
    }



     public function agree(Request $request,$id){
        $changelog = ChangeLog::find($id);

        return view('changelogs::changelogs.agree',compact('changelog'));
    }

    public function fetchalldatas()
    {
        try{
            $changelogs = ChangeLog::all();
            return response()->json(["status"=>"scuccess","data"=>$changelogs]);
        }catch(Exception $e){
            return response()->json(["status"=>"failed","message"=>$e->getMessage()]);
        }

    }

}
