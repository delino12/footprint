<?php

namespace Codedreamer\Footprint;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;
use Auth;
use DB;

class Footprint extends Model
{
	protected $table 		= "foot_prints";
	protected $guarded 		= "id";
	protected $primaryKey 	= "id";
	public $timestamp 		= true;

	const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at';

	/*
    |-----------------------------------------
    | ADD TO FOOTPRINT ON STATIC
    |-----------------------------------------
    */
    public static function logTrail($payload){
    	// get route information
    	if(Auth::check()){
    		$payload->by 		= Auth::user()->id;
    		$guest_name 		= Auth::user()->name ?? Auth::user()->email; 
    	}else{
    		$payload->by 		= $payload->ip();
    		$guest_name         = $payload->ip();
    	}

    	$payload->page 		= $payload->route()->getName() ?? env("APP_URL").'/'.$payload->path();
    	$payload->details 	= $guest_name." is currently active on ".$payload->page;
    	$payload->avatar 	= "";
    	$payload->level 	= 1;
   
    	// body
    	$add_new           	= new FootPrint();
    	$add_new->by       	= $payload->by;
    	$add_new->page 	   	= $payload->page;
    	$add_new->details 	= $payload->details;
    	$add_new->avatar 	= $payload->avatar;
    	$add_new->level 	= $payload->level;
    	$add_new->ip 		= $payload->ip();
    	$add_new->browser 	= $payload->header('User-Agent');
    	$add_new->save();
    }

    /*
    |-----------------------------------------
    | FETCH EVENT
    |-----------------------------------------
    */
    public function fetchAll(){
    	// body
    	$all_trails = FootPrint::orderBy("created_at", "DESC")->limit('100')->get();
    	$audit_box = [];
    	foreach ($all_trails as $key => $value) {
            $user = User::where("id", $value->by)->first();
            if($user !== null){
            	$value->by = $user->name;
            	$value->email = $user->email;
            }
            $value->email = "anonymous@domain.com";
            $value->browser = $this->resolvePlatform($value->browser);
            $value->last_seen = $value->created_at->diffForHumans();
            $value->date_seen = $value->created_at->isoFormat('LLL'); 

            array_push($audit_box, $value);
        }


    	return $audit_box;
    }

    /*
    |-----------------------------------------
    | SHOW
    |-----------------------------------------
    */
    public function deleteAll($payload){
        // use query option
        $data = $this->deleteQueryOption($payload);

    	// return
    	return $data;
    }

    /*
    |-----------------------------------------
    | SHOW
    |-----------------------------------------
    */
    public function deleteQueryOption($payload){
        // body
        if($payload->action == 1){
            // body
            if(Footprint::truncate()){
                $data = [
                    "status"    => "success",
                    "message"   => "Footprint log has been deleted successfully!" 
                ];
            }else{
                $data = [
                    "status"    => "error",
                    "message"   => "Error deleting footprint logs" 
                ];
            }
        }else if($payload->action == 2){
            // body
            $date = \Carbon\Carbon::today()->subDays(7);
            $footprints = Footprint::where('created_at', '>=', $date)->get();
            $total_deleted = 0;
            foreach ($footprints as $key => $value) {
                $footprint = Footprint::find($value->id)->delete();
                $total_deleted++;
            }

            $data = [
                "status"    => "success",
                "message"   => $total_deleted." footprint log has been deleted successfully!" 
            ];
        }else if($payload->action == 3){
            // body
            $date = \Carbon\Carbon::today()->subDays(30);
            $footprints = Footprint::where('created_at', '>=', $date)->get();
            $total_deleted = 0;
            foreach ($footprints as $key => $value) {
                $footprint = Footprint::find($value->id)->delete();
                $total_deleted++;
            }

            $data = [
                "status"    => "success",
                "message"   => $total_deleted." footprint log has been deleted successfully!" 
            ];
        }

        // return
        return $data;
    }

    /*
    |-----------------------------------------
    | FETCH EVENT
    |-----------------------------------------
    */
    public function fetchOne($payload){
        // body
        $all_trails = FootPrint::where('by', $payload->user_id)->orderBy("created_at", "DESC")->limit('100')->get();
        $audit_box = [];
        foreach ($all_trails as $key => $value) {
            $user = User::where("id", $value->by)->first();
            if($user !== null){
            	$value->by = $user->name;
            	$value->email = $user->email;
            }
            $value->email = "anonymous@domain.com";
            $value->browser = $this->resolvePlatform($value->browser);
            array_push($audit_box, $value);
        }

        return $audit_box;
    }

    /*
    |-----------------------------------------
    | GET PLATFORM 
    |-----------------------------------------
    */
    public function resolvePlatform($user_agent){
	    // Get Platform
	   	if(preg_match('/windows|win64/i', $user_agent)){
	   		$data = "Windows X64";
	   	}elseif(preg_match('/windows|win32/i', $user_agent)){
	   		$data = "Windows X32";
	   	}elseif(preg_match('/linux/i', $user_agent)){
	   		$data = "Linux";
	   	}elseif(preg_match('/macintosh|mac os x/i', $user_agent)){
	   		$data = "Apple Macintosh";
	   	}elseif(preg_match('/CrOS/i', $user_agent)){
	   		$data = "Chromebook CrOS";
	   	}elseif(preg_match('/windows phone/i', $user_agent)){
	   		$data = "Window Mobile Device";
	   	}elseif(preg_match('/android/i', $user_agent)){
	   		$data = "Android Device";
	   	}elseif(preg_match('/iPad|iPhone|iPod/i', $user_agent)){
	   		$data = "Apple Mobile Device";
	   	}else{
	   		$data = "---";
	   	}

	   	// resolve and attach browser information
	   	$browser = $this->resolveBrowser($user_agent);

	   	// return
	   	return $data.' '.$browser;
    }

    /*
    |-----------------------------------------
    | GET AGENT 
    |-----------------------------------------
    */
    public function resolveBrowser($user_agent){
	    if(preg_match('/OPR/i', $user_agent)){
	   		$data = "Opera Browser";
	   	}elseif(preg_match('/Chrome/i', $user_agent)){
	   		$data = "Chrome Browser";
	   	}elseif(preg_match('/Firefox/i', $user_agent)){
	   		$data = "Mozilla Firefox Browser";
	   	}elseif(preg_match('/Edge/i', $user_agent)){
	   		$data = "Microsoft Edge Browser";
	   	}elseif(preg_match('/Trident/i', $user_agent)){
	   		$data = "Internet Explorer";
	   	}elseif(preg_match('/MSIE/i', $user_agent)){
	   		$data = "Internet Explorer";
	   	}elseif(preg_match('/Safari/i', $user_agent)){
	   		$data = "Apple Safari";
	   	}else{
	   		$data = "---";
	   	}

	   	// return
	   	return $data;
    }
}

?>