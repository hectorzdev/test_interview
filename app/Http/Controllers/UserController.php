<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Exception;

class UserController extends Controller
{

    public function index(){
        $users  = User::all();
        return view('welcome' , ['users' => $users]);
    }

    public function ssm(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://ssm-th.com/api/v2',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('key' => 'ab2c31fa20bb20ecc5260c05a3b1d305','action' => 'services'),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);

        $services = json_decode($response);
        return view('ssm' , ['services' => $services]);
    }


    public function store(Request $request){
        try {
            
            $createUser = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'name' => $request->name,
                'point' => $request->point,
            ]);

            if(!$createUser){
                throw new Exception("เพิ่มข้อมูลสมาชิกไม่สำเร็จ");
            }
            $user = User::find($createUser->id);
            $export['success'] = true;
            $export['user'] = $user;
            $export['password'] = $user->password;
        } catch (Exception $th) {
            $export['success'] = false;
            $export['msg'] = $th->getMessage();
        }

        return response()->json($export);
    }

    
    public function updatePoint(Request $request){
        try {
            $user_id = $request->user_id;
            $userUpdate = User::where('id' , $user_id)->update(['point' => $request->point]);
            if(!$userUpdate){
                throw new Exception("อัพเดตข้อมูลสมาชิกไม่สำเร็จ");
            }

            $export['success'] = true;
        } catch (Exception $th) {
            $export['success'] = false;
            $export['msg'] = $th->getMessage();
        }

        return response()->json($export);
    }
    

    public function deleteUser(Request $request){
        try {
            $user_id = $request->user_id;
            $userDelete = User::where('id' , $user_id)->delete();
            if(!$userDelete){
                throw new Exception("ลบสมาชิกไม่สำเร็จ");
            }

            $export['success'] = true;
        } catch (Exception $th) {
            $export['success'] = false;
            $export['msg'] = $th->getMessage();
        }

        return response()->json($export);
    }
    
    public function getAPI(Request $request){
        try {

            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://ssm-th.com/api/v2',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('key' => 'ab2c31fa20bb20ecc5260c05a3b1d305','action' => 'services'),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);

            // $data = json_encode($response);

            $export['success'] = true;
            $export['api_data'] = $response;
        } catch (Exception $th) {
            $export['success'] = false;
            $export['msg'] = $th->getMessage();
        }

        return response()->json($export);
    }
}
