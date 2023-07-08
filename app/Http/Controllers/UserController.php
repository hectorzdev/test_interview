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
}
