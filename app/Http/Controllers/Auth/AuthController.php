<?php

namespace App\Http\Controllers\Auth;


use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    protected $loginPath = '/index/login';
    protected $redirectTo = '/index/judge';

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins {
        AuthenticatesAndRegistersUsers::postLogin as laravelPostLogin;
    }
    

    public function postLogin(Request $request)
    {
        $field = preg_match("/^1(3|5|8|7){1}[0-9]{9}$/", $request->input('username')) ? 'phone' : 'username';
        $request->merge([$field => $request->input('username')]);
        $this->username = $field;

        return self::laravelPostLogin($request);
    }

    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::login($this->create($request->all()));

        return redirect($this->redirectTo);
    }

     /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->redirectPath = session('lastUrl');
        $this->redirectPath = '/index/judge';
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
//            'name' => 'required|max:255',
//            'email' => 'required|email|max:255|unique:users',
//            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $yaoqingma = '';
        $randomNum     = ['0','1','2','3','4','5','6','7','8','9'];
        $randomLowcase = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
        $randomUpcase  = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $randomarr     = array_merge($randomNum,$randomLowcase,$randomUpcase);
        $keyarr        = array_rand($randomarr,6);
        $keyarr        = array_rand($randomarr,6);

        for ($i=0;$i<6;$i++){
            $yaoqingma .= $randomarr[$keyarr[$i]];
        }

        $data['yaoqingma'] = $yaoqingma;

        $data['password'] = bcrypt($data['password']);
        $data = array_filter($data);
        return User::create($data);
    }


    public function adminLogin(Request $request)
    {
        if (Auth::attempt(['username' => $request -> username, 'password' => $request -> password, 'type' => 3,'checks'=>0])) {
            $this -> OperationLog('登陆了后台', 0);
            return redirect() -> intended('admin/index');
        } else {
            return redirect('admin/login') -> withErrors('账号或密码错误,如都不是请联系管理员!');
        }
    }
}
