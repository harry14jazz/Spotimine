<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use  App\Band;

class BandController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createBand(Request $request){
        //validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'dob' => 'required|string',
            'genre' => 'required|string',
            'logo' => 'required|image',
            'user_id' => 'required|integer'
        ]);
        
        $logo = Str::random(34);
        $request->file('logo')->move(storage_path('logo'), $logo);
        $user_logo = Band::where('user_id', Auth::user()->id)->first();

        if($user_logo){
            $current_logo_path = storage_path('logo') . '/' . $user_logo->logo;
            if(file_exists($current_logo_path)){
                unlink($current_logo_path);
            }

            $user_logo->logo = $logo;
            $user_logo->name = $request->name;
            $user_logo->genre = $request->genre;
            $user_logo->user_id = $request->user_id;
            $user_logo->save();

            //return successful respone
            return response()->json([
                'band' => $user_logo, 
                'message' => 'Band has been created'
            ], 201);

        } else {
            $band = new Band;
            $band->name = $request->input('name');
            $band->dob = $request->input('dob');
            $band->genre = $request->input('genre');
            $band->logo = $logo;
            $band->user_id = $request->input('user_id');
            $user_logo->save();

            //return successful respone
            return response()->json([
                'band' => $band, 
                'message' => 'Band has been created'
            ], 201);
        }
    }

    public function getLogo($name){
        $logo_path = storage_path('logo') . '/' . $name;

        if(file_exists($logo_path)){
            $file = file_get_contents($logo_path);
            return response($file, 200)->header('Content-Type', 'image/jpeg');
        }
        $res['success'] = false;
        $res['message'] = "Avatar not found";
    
        return $res;
    }  
}
