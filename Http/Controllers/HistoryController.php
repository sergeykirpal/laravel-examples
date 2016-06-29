<?php
namespace App\Http\Controllers;
use Auth;
use App\User;
use Request;
use Hash;
use Validator;
use DB;
use DateTime;
use DateTimeZone;


class HistoryController extends Controller {

    public function __construct() {        
        $this -> middleware('auth');
    }

    public function history() {
                        
        $data['oil'] = DB::table('history')->where('type','oil')->orderBy('order','ASC')->get();                
        foreach ($data['oil'] as $key => $value) {
            $logs = DB::table('history_log')->where('id_user',Auth::id())->where('id_history',$value->id)->orderBy('replacingDate','DESC')->first();
            if($logs)
                $data['oil'][$key]->replacingDate = $logs->replacingDate;
        }
        
        $data['filters'] = DB::table('history')->where('type','filter')->orderBy('order','ASC')->get();
        foreach ($data['filters'] as $key => $value) {
            $logs = DB::table('history_log')->where('id_user',Auth::id())->where('id_history',$value->id)->orderBy('replacingDate','DESC')->first();
            if($logs)
                $data['filters'][$key]->replacingDate = $logs->replacingDate;
        }
        
        $data['engine'] = DB::table('history')->where('type','engine')->orderBy('order','ASC')->get();
        foreach ($data['engine'] as $key => $value) {
            $logs = DB::table('history_log')->where('id_user',Auth::id())->where('id_history',$value->id)->orderBy('replacingDate','DESC')->first();
            if($logs)
                $data['engine'][$key]->replacingDate = $logs->replacingDate;
        }
        
        $data['chassis'] = DB::table('history')->where('type','chassis')->orderBy('order','ASC')->get();
        foreach ($data['chassis'] as $key => $value) {
            $logs = DB::table('history_log')->where('id_user',Auth::id())->where('id_history',$value->id)->orderBy('replacingDate','DESC')->first();
            if($logs)
                $data['chassis'][$key]->replacingDate = $logs->replacingDate;
        }
        
        
        return response()->json($data);                
    }
    
    public function item($id) {
        $item = DB::table('history')->where('url',$id)->first();
        $data['item'] = $item;          
        $data['items'] = DB::table('history_log')->where('id_user',Auth::id())->where('id_history',$item->id)->orderBy('created_at','DESC')->get();      
        return response()->json($data);                
    }
    
    public function itemedit($id, $logid) {
        $item = DB::table('history')->where('url',$id)->first();
        $data['item'] = $item;          
        $data['data'] = DB::table('history_log')->where('id_user',Auth::id())->where('id',$logid)->first();      
        return response()->json($data);                
    }
        
    public function caresave(){
        $errors = array();
        $data = Request::all(); 
        if ($data['brandName']==""){
            $errors['brandNameError'] = "The field is required";
        }    
        
        if($errors){
            return response()->json($errors);      
        }else{
            $history = DB::table('history')->where('url',$data['historyName'])->first();            
            if ($history){
                $data['id_history'] = $history->id;
                $data['id_user'] = Auth::id();
                unset($data['historyName']);
                
                if (!empty($data['id']))
                    DB::table('history_log')->where('id',$data['id'])->where('id_user',$data['id_user'])->update($data);
                else
                    $data['id'] = DB::table('history_log')->insertGetId($data);
            }
            $data['historyName'] = Request::input('historyName');     
            return response()->json($data); 
        }
    }
    
    public function delitem($id) {
        $user = Auth::user();
        $res = DB::table('history_log')->where('id', $id)->where('id_user', $user->id)->delete();
        if($res)        
            return response()->json(array('del'=>'ok'));
        else
            return response()->json(array('del'=>'error'));
    }
    
    
    
}
