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
    
    public function area(){
   
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=%E0%B8%AA%E0%B8%A1%E0%B8%B8%E0%B8%97%E0%B8%A3%E0%B8%AA%E0%B8%87%E0%B8%84%E0%B8%A3%E0%B8%B2%E0%B8%A1&destinations=%E0%B8%AA%E0%B8%B8%E0%B8%A1%E0%B8%97%E0%B8%A3%E0%B8%9B%E0%B8%A3%E0%B8%B2%E0%B8%81%E0%B8%B2%E0%B8%A3%7C%E0%B8%9A%E0%B8%B2%E0%B8%87%E0%B8%81%E0%B8%B0%E0%B8%9B%E0%B8%B4%7C%E0%B8%AD%E0%B8%A2%E0%B8%B8%E0%B8%98%E0%B8%A2%E0%B8%B2%7C%E0%B8%A5%E0%B8%B2%E0%B8%94%E0%B8%81%E0%B8%A3%E0%B8%B0%E0%B8%9A%E0%B8%B1%E0%B8%87%7C%E0%B8%99%E0%B8%84%E0%B8%A3%E0%B8%9B%E0%B8%90%E0%B8%A1%7C%E0%B9%80%E0%B8%9E%E0%B8%8A%E0%B8%A3%E0%B8%9A%E0%B8%B8%E0%B8%A3%E0%B8%B5&key=AIzaSyCvfowu3sliTOFVf93XLgHO86qB1wG4zOc',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        $results = $response->rows[0]->elements;

        $destinations = $response->destination_addresses;

      
        usort($results, function ($a, $b) {
            return $a->distance->value - $b->distance->value;
        });
    
        $sortedDestinations = [];
        foreach ($results as $result) {
            $sortedDestinations[] = $destinations[array_search($result, $results)] . '( '.$result->distance->text.' )';
        }
    
  
        // แสดงลำดับจังหวัดทั้งหมด
        foreach ($sortedDestinations as $destination) {
            echo $destination . "<br>";
        }
        return view('area' , ['responses' => $response]);
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
