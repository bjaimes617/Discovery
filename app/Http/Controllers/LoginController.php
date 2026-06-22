<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer as BaconQrCodeWriter;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $maxAttempts = 5; // Default is 5
    protected $decayMinutes = 1;

    //protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'fa2']);
    }

    /**
     * Display a client login form view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('login/index');
    }

    /**
     * Signin admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function signin(Request $request)
    {
        $data = request()->validate([
            'usuario' => 'required',
            'password' => 'required'
        ]);


        if (User::where('usuario', $request->usuario)->exists()) {
            if (User::where('usuario', $request->usuario)->WhereIn('estatus_id', [1, 3])->exists()) {
                Session::forget('urlQR');
                $user = User::where('usuario', '=', $request->usuario)->first();
                if ($user->fa2 == 'Si') {
                    if (\Hash::check($request->password, $user->password)) {
                        if ($user->fa2 == 'Si' && $user->fa2_secret == null) {
                            $user->fa2_secret = (new Google2FA)->generateSecretKey();
                            $user->save();
                            $urlQR = $this->createUserUrlQR($user);
                            Session::put('urlQR', $urlQR);
                        }
                        return response()->json(['msg' => 'No lo sueñes, hazlo realidad, ' . " " . $user->nombre_apellido, 'status' => 'info', 'url' => route('login.fa2', $user)]);
                    } else {
                        return response()->json(['msg' => 'Usuario y/o contraseña incorrectos', 'status' => 'danger']);
                    }
                } else {
                    if (Auth::attempt(['usuario' => $request->usuario, 'password' => $request->password], $request->remember)) {
                        if (Auth::user()->email_verified_at == null && Auth::user()->estatus_id == 3) {
                            Auth::user()->markEmailAsVerified();
                        }
                        return response()->json(['msg' => 'No lo sueñes, hazlo realidad, ' . " " . Auth::user()->nombre_apellido, 'status' => 'info', 'url' => route('dashboard')]);
                    } else {
                        return response()->json(['msg' => 'Usuario y/o contraseña incorrectos', 'status' => 'danger']);
                    }
                }
            } else {
                return response()->json(['msg' => 'Usuario inactivo, comuniquese con el administrador del sistema.', 'status' => 'warning']);
            }
        } else {
            return response()->json(['msg' => 'Usuario y/o contraseña incorrectos.', 'status' => 'danger']);
        }
    }

    public function createUserUrlQR($user)
    {
        $bacon = new BaconQrCodeWriter(new ImageRenderer(
            new RendererStyle(200),
            new ImagickImageBackEnd()
        ));

        $data = $bacon->writeString(
            (new Google2FA)->getQRCodeUrl(
                config('app.name'),
                $user->email,
                $user->fa2_secret
            ),
            'utf-8'
        );

        return 'data:image/png;base64,' . base64_encode($data);
    }

    public function fa2(User $user)
    {
        return response()->view('login/2facode', ['user' => $user]);
    }

    public function login2FA(Request $request, User $user)
    {
        $request->validate(['code_verification' => 'required']);

        if ((new Google2FA())->verifyKey($user->fa2_secret, $request->code_verification)) {
            $request->session()->regenerate();

            Auth::login($user);
            if (Auth::user()->email_verified_at == null && Auth::user()->estatus_id == 3) {
                Auth::user()->markEmailAsVerified();
            }

            return redirect()->intended('dashboard');
        }
        Session::flash('error2fa', 'El código de verificación que ingresaste es incorrecto, por favor verificalo en la aplicación de tu teléfono celular e ingresalo nuevamente.');
        return redirect()->route('login.fa2', $user);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
