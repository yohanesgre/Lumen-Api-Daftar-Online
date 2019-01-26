<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Berobat extends Model
 
{
   //protected $table = 'profiles';
   protected $fillable = ['user_id', 'tgl', 'poli', 'dokter', 'reservasi', 'jam', 'penjamin'];

   public function riwayat(){
       return $this->HasOne('App\Riwayat', 'berobat_id');
   }
}