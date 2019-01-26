<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\User;
use App\Profile;
use Auth;
use Validator;


class ProfileController extends Controller
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

    public function AdminGetAllUser(Request $request){
        $p_ = User::with(['profile' => function($query){
            $query->orderBy('id', 'desc');
        }])->get();
        return response()->json($p_);
    }

    public function UserGetProfile(Request $request){
        $p_ = Auth::user()->with(['profile' => function($query){
            $query->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
        }])->where('id', Auth::user()->id)->get();
        return response()->json($p_);
    }

    public function Store(Request $request, $id = 0)
    {
        $this->validate($request, [
        'user_id' => 'required',
        'nama' => 'required',
        'ttl' => 'required',
        'nik' => 'required',
        'jk' => 'required',
        'kerja' => 'required',
        'alamat' => 'required',
        'hp' => 'required',
        'ibu' => 'required',
        'role' => 'required',
         ]);


        if ($id == '0'){
            Profile::where('user_id', Auth::user()->id)->delete();
            if(Auth::user()->profile()->Create($request->all())){
                $p_ = Auth::user()->with(['profile' => function($query){
                    $query->where('user_id', Auth::user()->id)
                    ->orderBy('id', 'desc')->first();
                }])->where('id', Auth::user()->id)->get();
                return response()->json($p_);
            }else{
                return response()->json(['error' => 'Gagal memperbarui profile!']);
            }
        }else{
            $validatornorm = Validator::make($request->all(), [
                'user_id' => 'required',
                'nama' => 'required',
                'ttl' => 'required',
                'nik' => 'required',
                'jk' => 'required',
                'kerja' => 'required',
                'alamat' => 'required',
                'hp' => 'required',
                'ibu' => 'required',
                'role' => 'required',
                'norm' => 'required|unique:profiles']);
            Profile::where('user_id', $id)->delete();
            $request->merge(['user_id' => $id]);
            if ($validatornorm->fails())
            {
                return response()->json(['error'=>$validatornorm->errors()], 401); 
            }
            if(Profile::create($request->all())){
                $p_ = Profile::where('user_id', $id);
                return response()->json($p_->orderBy('id', 'desc')->first());
            }else{
                return response()->json(['status' => 'fail']);
            }
        }
    }
    
    public function AdminSearchByNoRM(Request $request){
        return Profile::where('norm', $request->input('norm'))->orderBy('id', 'desc')->first();
    }

    public function AdminSearchByNIK(Request $request){
        return Profile::where('nik', $request->input('nik'))->orderBy('id', 'desc')->first();
    }

    public function AdminSearchByNama(Request $request){
        return Profile::where('nama', $request->input('nama'))->orderBy('id', 'desc')->first();
    }

    public function AdminSearchByEmail(Request $request){
        return User::where('email', $request->input('email'))->with('profile')->first();
    }
}
