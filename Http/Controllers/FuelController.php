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


class FuelController extends Controller {

    public function __construct() {        
        $this -> middleware('auth');
    }

    public function saveFuel() {
        $data = Request::all();
        $user = Auth::user();
        $data['id_user'] = $user->id;
        if (!$data['distance'])
          unset($data['fuelEndDate']);
        if (isset($data['id'])){
            $id = $data['id'];
            unset($data['id']);
            DB::table('fuel')->where('id',$id)->update($data);
        }else{
            DB::table('fuel')->insert($data);
        }

        return response()->json(array('save'=>'ok'));
    }

    public function getFuels() {
        $user = Auth::user();
        $data = DB::table('fuel')->where('id_user',$user->id)->where('archived',(int)Request::input('arhived'))->orderBy('created_ad','DESC')->get();
        return response()->json($data);
    }

    public function delfuel($id) {
        $user = Auth::user();
        $res = DB::table('fuel')->where('id', $id)->where('id_user', $user->id)->delete();
        if($res)
            return response()->json(array('del'=>'ok'));
        else
            return response()->json(array('del'=>'error'));
    }
    public function archivefuel($id) {
        $user = Auth::user();
        $res = DB::table('fuel')->where('id', $id)->where('id_user', $user->id)->update(array('archived'=>1));
        if($res)
            return response()->json(array('del'=>'ok'));
        else
            return response()->json(array('del'=>'error'));
    }
    public function restorefuel($id) {
        $user = Auth::user();
        $res = DB::table('fuel')->where('id', $id)->where('id_user', $user->id)->update(array('archived'=>0));
        if($res)
            return response()->json(array('del'=>'ok'));
        else
            return response()->json(array('del'=>'error'));
    }
    public function getfuel($id) {
        $fuel = DB::table('fuel')->where('id',$id)->first();
        return response()->json($fuel);
    }

    public function getMileage(){
      $user = Auth::user();
      $data = DB::table('fuel')->where('id_user', $user->id)->where('archived', 0)->where('distance','<>', 0)->orderBy('created_ad','DESC')->first();
      return response()->json($data);
    }

}
