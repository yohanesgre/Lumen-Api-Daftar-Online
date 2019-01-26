<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\User;
use App\Berobat;
use App\Riwayat;
use Auth;


class RiwayatController extends Controller
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

    public function UserGetRiwayatByNomerIdBerobat(Request $request)
    {
        $b_id = $request->input('berobat_id');
        $r_ = Auth::user()->berobat()->where([['user_id', Auth::user()->id],
        ['id', $b_id]])->with(['riwayat'=>function($query) use ($request){
            $query->where('berobat_id', $request->input('berobat_id'))->orderBy('id', 'decs')->first();
        }])->get();
        return response()->json($r_);
    }

    public function Store(Request $request, $user_id){
        $this->validate($request,[
            'berobat_id' => 'required',
            'anamnese' => 'required',
            'diagnosa' => 'required',
            'terapi' => 'required',
            'dokter' => 'required'
        ]);
        $b_id = $request->input('berobat_id');
        //$request->request->add(['berobat_id' => $b_->id]);
        $riwayat_ = Riwayat::where('berobat_id', $b_id);
        $riwayat_->delete();
        if(Riwayat::Create($request->all())){
            $r_ = Berobat::with(['riwayat' => function ($q){
                $q->orderBy('id', 'desc')->first();
            }])->where([['user_id', $user_id], ['id', $b_id]])->first();
            return response($r_);
        }
    }

    public function AdminDeleteRiwayatByNomerIdBerobat(Request $request, $berobat_id)
    {
        $r_ = Riwayat::where('berobat_id', $berobat_id);
        $r_->delete();
        return response()->json("Removed Successfully.");
    }
}
