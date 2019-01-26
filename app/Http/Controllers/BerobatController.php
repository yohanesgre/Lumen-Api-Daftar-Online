<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\User;
use App\Berobat;
use App\Riwayat;
use Auth;


class BerobatController extends Controller
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

    public function AdminGetAllBerobat(Request $request){
        $p_ = Berobat::all();
        return response()->json($p_);
    }

    public function AdminGetAllBerobatByTgl(Request $request){
        $b_ = User::with(['berobat' => function($query) use ($request){
            $query->where('tgl', $request->input('tgl'))->orderBy('id', 'desc');
        }])->get();
        return response()->json($b_);
    }

    public function UserGetAllBerobat(Request $request){
        $b_ = Auth::user()->with(['berobat' => function($query){
            $query->where('user_id', Auth::user()->id);
        }])->where('id', Auth::user()->id)->get();
        return response()->json($b_);
    }

    public function UserGetBerobatByReservasi(Request $request){
        $b_ = User::with(['berobat' => function($query) use ($request){
            $query->where('reservasi', $request->input('reservasi'));
        }])->where('id', Auth::user()->id)->get();
        return response()->json($b_);
    }
    
    public function AdminGetUserBerobatById(Request $request, $id){
        $b_ = User::with(['berobat' => function($query) use ($id){
            $query->where('user_id', $id);
        }])->where('id', $id)->get();
        return response()->json($b_);
    }
    
    public function AdminGetBerobatByReservasi(Request $request){
        $b_ = Berobat::with(['riwayat' => function($query) use ($request){
            $query->orderBy('berobat_id', 'dec')->first();
        }])->where('reservasi', $request->input('reservasi'))->first();
        return response()->json($b_);
    }

    public function Store(Request $request, $id = 0)
    {
        $this->validate($request, [
        'user_id' => 'required',
        'poli'=> 'required',
        'dokter'=> 'required', 
        'jam'=> 'required', 
        'penjamin' => 'required',
         ]);
        $input = $request->all();
        if ($id == "0"){
            $date = $input['tgl'];
            $jam = $input['jam'];
            $jam = substr($jam, 0, strpos($jam, ':'));
            $explodePoli = explode(" ", $input['poli']);
            $poli = $explodePoli[1];
            if(strlen($jam)<2){
                $jam='0'.$jam;
            }
            $countBerobat = Berobat::where([['tgl', $date],['jam', 'LIKE', '%'.$jam.'%']])->count();
            if($countBerobat>0){
                $reservasi = Berobat::where('reservasi', 'LIKE', '%'.$date.'%')
                ->orderBy('jam', 'desc')->orderBy('created_at', 'desc')->first();
                $n = ' ';
                $rev = ' ';
                $revstrlen = 0;
                switch((string)$poli){
                    case "Gigi":
                        $revstrlen = 18;
                    break;
                    case "Umum":
                        $revstrlen = 18;
                    break;
                    case "KIA":
                        $revstrlen = 17;
                    break;
                }
                if(strlen($reservasi->reservasi)>$revstrlen){
                    $n = substr($reservasi->reservasi, -2); 
                    $n = strval(intval($n)+1);
                    $rev = substr($reservasi->reservasi, 0, $revstrlen-1).$n;
                }
                else{
                    $n = substr($reservasi->reservasi, -1);     
                    $n = strval(intval($n)+1);
                    $rev = substr($reservasi->reservasi, 0, $revstrlen-1).$n;
                }
                $request->request->add(['reservasi'=> $rev]);
            }else{
                $date .= '/'.$poli.'/'.$jam.'/'.strval(1);
                $request->request->add(['reservasi'=> $date]);
            }
            if(Auth::user()->berobat()->Create($request->all())){
                $b_ = Auth::user()->with(['berobat' => function($query){
                    $query->where('user_id', Auth::user()->id)
                    ->orderBy('id', 'desc')->first();
                }])->where('id', Auth::user()->id)->get();
                return response()->json($b_);
            }else{
                return response()->json(['status' => 'fail']);
            }
        }else{
            $p_ = Berobat::where("user_id", $id)->first();
            $request->merge(['user_id' => $id]);
            if($p_->Create($request->all())){
                return response()->json($p_->orderBy('id', 'desc')->first());
            }else{
                return response()->json(['status' => 'fail']);
            }
        }
    }

    public function AdminDeleteBerobatByReservasi(Request $request)
    {
        $input = $request->all();
        $b_ = Berobat::where('reservasi', $input['reservasi']);
        $r_ = Riwayat::where('berobat_id', $b_->first()->id);
        $r_->delete();
        $b_->delete();
        return response()->json("Removed Successfully.");
    }
}
