<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Profile extends Model
 
{
   //protected $table = 'profiles';
   protected $fillable = ['user_id', 'norm', 'nama', 'ttl', 'nik', 'jk', 'kerja', 
                            'alamat', 'hp', 'ibu', 'role'];
}