<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Riwayat extends Model
 
{
   //protected $table = 'profiles';
   protected $fillable = ['berobat_id', 'anamnese', 'diagnosa', 'terapi', 'dokter'];
}