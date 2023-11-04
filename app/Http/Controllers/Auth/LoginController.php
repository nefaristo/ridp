<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\_s_session;

class LoginController extends Controller
{
    protected $redirectTo = '/'; 
    public function username(){return 'username';} /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers{login as protected traitlogin;}
    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
      
    public function login(Request $request) {
        //return $request->all();
        /*if (\Auth::attempt(['username' => $request->username, 'password' => $request->password])){                    
            $session=new _s_session;
            $session->user = \Auth::user()["id"];
            $session->start_time = date("Y-m-d H:i:s");
            $session->ip = $_SERVER['REMOTE_ADDR'];
            $session->save();
            $user=\App\Center::where('id',$session->user);
            session(['id' => $session->id]);
        }*/
        //used to contain code to write new log@db row, and then call the standard:
        return $this->traitlogin($request);
   }
   protected function authenticated(Request $request, $user)
    {
        if ($user->active !== 1) {
            Auth::logout();

            return redirect(route('login'))->withErrors(['common' => trans('rdp.disabled_user')]);
        }
    }
   
    public function logout()//overrides a standard logout
    {
        $session=\App\_s_session::find(session('id'));
        if(! $session==null){//last update of the db
            $session->end_time = date("Y-m-d H:i:s");
            $session->save();
        }
        session(['id' => null]);//loses the reference
        Auth::logout();//proceed to the standard
        return redirect(property_exists($this, 'redirectTo') ? $this->redirectTo : '/');
    }    
}
