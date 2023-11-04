<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails{
        sendResetLinkEmail as trait_sendResetLinkEmail;
    }

    /**
     * Create a new controller instance.
     *
     * @return void 
     */
    public function showLinkRequestForm() //override form show
    {
        $user=Auth::user();
        if($user && ($user->privilege>=10)){            
            return view('auth.passwords.email_superuser')->with(["message"=>""]);
        }else{
            return view('auth.passwords.email')->with(["message"=>""]);
        }
    }
    public function sendResetLinkEmail(Request $request){
        $user=Auth::user();
        $blade=($user->privilege>=10)?"auth.passwords.email_superuser":"auth.passwords.email";
        if(true || $user && ($user->privilege>=10)){
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );//CHECK 
            $date=$user->timezone;$date=Carbon::now($user->timezone)->format($user->dateformat. " H:m:s");
            if(Password::RESET_LINK_SENT){
                $message=str_replace("{time}",$date,trans("rdp.sent_reset_to_mail"));
                if(false && in_array($user->id,[15])){//SVSV 15=stefano
                    $message.="<br><a style='font-size:80%;' href='/passwordResetToken'>reset manuale</a>";
                }
            }else{
                $message=$date . " - " . $response . " " .$request->email;
            }
            return view($blade)->with(['message' => $message]);
            return $response == Password::RESET_LINK_SENT?
                view($blade)->with(['message' => $message]):
                view($blade)->with(['message' => $message]);
            
        }else{
            return trait_sendResetLinkEmail($request); //standard
        }
    }
    
    public function __construct()
    {
        //$this->middleware('guest');
        $this->middleware('log');
    }
}
