<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Repositories\CompanhiaRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{


    protected $companhiaRepository;
    use AuthenticatesUsers;

    protected $redirectTo = '/';


    public function __construct( CompanhiaRepository $companhiaRepository )
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->companhiaRepository = $companhiaRepository;
    }

    public function authenticate(Request $request)
    {
              
        $this->validateLogin($request);

        $input=$request->all();
        $password=$input['password'];
        $email=$input['email'];
        $companhia=$input['companhia'];

        $res = $this->companhiaRepository->getByName($companhia);

        if (Auth::attempt(['email' => $email, 'password' => $password, 'ativo'=>true, 'companhia_id'=> $$res->id])) {

            return redirect()->intended('/');
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

   
}

/**

A rota para autenticação ficou da seguinte forma: 


Route::post('/login', 'Auth\LoginController@authenticate');

**/