<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RedirectIfAuthenticated;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{
    //
private $config = array(
'appId' => '1983932818538216',
'secret' => 'be73fe93c78eed3a8c30f9809e40b880',
'cookie'=>true,
'fileUpload' => true, // optional
    'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
);
    public function login(){
        $data = array();

        if (Auth::check()) {
            $data = Auth::user();
        }
        return view('loginpage.login', array('data'=>$data));
    }
    public function logout(){
        Auth::logout();
        return Redirect::to('/');
    }
    public function loginFB(){

        $facebook = new \Facebook($this->config);

        $params = array(
            'redirect_uri' => url('/login/fb/callback'),
            'scope' => 'email'
        );
        return Redirect::to($facebook->getLoginUrl($params));
    }

    public function loginCallback()
    {
        $code = Input::get('code');
        if (strlen($code) == 0) return Redirect::to('/')->with('message', 'There was an error communicating with Facebook');

        $facebook = new \Facebook($this->config);
        $uid = $facebook->getUser();
        if ($uid == 0) return Redirect::to('/')->with('message', 'There was an error');
        $me = $facebook->api('/me?fields=email,first_name,last_name');
        $profile = Profile::whereUid($uid)->first();
        if (empty($profile)) {

            $user = new User();
            $user->facebook_id=$me['id'];
            $user->name = $me['first_name'] . ' ' . $me['last_name'];
            $user->email = $me['email'];
            $user->photo = 'https://graph.facebook.com/' . $me['id'] . '/picture?type=large';

            $url = 'https://graph.facebook.com/' . $me['id'] . '/picture?type=large';
            $image=public_path().'/images/'.$me['id'].'_profile.jpg';
            file_put_contents($image,file_get_contents($url));

            $new_file = $this->addframe($image);
            $user->save();
            $profile = new Profile();
            $profile->uid = $uid;
            $profile->username = $me['first_name'];
            $profile = $user->profiles()->save($profile);
        }

        $profile->access_token = $facebook->getAccessToken();
        $profile->access_token_secret = $facebook->getApiSecret();

        $profile->save();

        $user = $profile->user;

        Auth::login($user);


        return Redirect::away('/')->with('message', 'Logged in with Facebook');
    }

    public function addframe($file){
        include('class.upload.php');

        $handle = new \upload($file);
        if ($handle->uploaded) {
            $handle->image_resize = true;
            $handle->image_x = 283;
            $handle->image_y = 221;
            $handle->image_ratio_crop = true;
            $handle->jpeg_quality = 100;
            $handle->image_watermark = public_path().'/images/frame.png';

            $handle->Process('./images');
            if ($handle->processed) {
                $filename = $handle->file_dst_name;
            }else{
                echo 'Error: ' . $handle->error . '';
            }
            $handle-> Clean();
        }else{
            echo 'Error: ' . $handle->error . '';
        }

        return $filename;
    }
}
