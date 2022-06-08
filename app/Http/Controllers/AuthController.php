<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auth;
use Session;
use DateTime;

class AuthController extends Controller
{    
    public function login()
    {
        return view("dashboard.login");
    }

    public function auth(Request $req){    
        if(isset($_COOKIE['penalty']) && $_COOKIE['penalty'] == true){
            $time_left =  ($_COOKIE["expire"]);
            $time_left = $this->penalty_remaining(date("Y-m-d H:i:s", $time_left));
            
            return redirect('login')->with('err_msg', 'Terlalu banyak permintaan login<br>Coba lagi dalam '.$time_left.'!!');
        }else{
            $user = Auth::where([
                'USERNAME_USER' => $req->input('username'),
                'PASS_USER' => hash('sha256', md5($req->input('password')))
            ])->first();
            if ($user != null) {
                if ($user->ID_ROLE == 1) {

                    $this->setSession($req->input('username'), $user->NAME_USER, $user->ID_ROLE);

                    return redirect('dashboard');  
                } else {
                    $attempt = session('attempt');
                    $attempt++;
                    Session::put('attempt', $attempt);
            
                    if ($attempt == 3) {
                        $attempt = 0;
                        Session::put('attempt', $attempt);
            
                        setcookie("penalty", true, time() + 300);
                        setcookie("expire", time() + 300, time() + 300);

                        return redirect('login')->with('err_msg', 'Terlalu banyak permintaan login<br>Harap tunggu selama 5 menit !!');            
                    } else {
                        return redirect('login')->with('err_msg', 'Anda tidak memiliki hak akses!<br><b>Kesempatan login - '.(3-$attempt).'</b>');
                    }                    
                }
            }
            $attempt = session('attempt');
            $attempt++;
            Session::put('attempt', $attempt);
    
            if ($attempt == 3) {
                $attempt = 0;
                Session::put('attempt', $attempt);
    
                setcookie("penalty", true, time() + 300);
                setcookie("expire", time() + 300, time() + 300);
    
                return redirect('login')->with('err_msg', 'Terlalu banyak permintaan login<br>Harap tunggu selama 5 menit !!');      
            } else {
                return redirect('login')->with('err_msg', 'Username/Password tidak cocok!<br><b>Kesempatan login - '.(3-$attempt).'</b>');
            }
        }        
    }

    function penalty_remaining($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'i' => 'menit ',
			's' => 'detik',
		);
		$a = null;
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v;
				$a .= $v;
			} else {
				unset($string[$k]);
			}
		}
		return $a;
	}

    public function setSession($username, $nama, $role){
        session([
            'username' => $username,
            'nama'     => $nama,
            'role'     => $role
        ]);
    }
    
    public function logout(Request $req){
        $req->session()->flush();
        return redirect('login');
    }
}
