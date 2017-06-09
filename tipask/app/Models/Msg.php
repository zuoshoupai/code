<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Msg extends Model
{ 
    protected $table = 'msg';
    protected $fillable = ['id','title','content','to_user','create_time'];
    public $timestamps = false;

    


}
