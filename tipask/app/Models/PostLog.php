<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Msg extends Model
{ 
    protected $table = 'postlog';
    protected $fillable = ['id','title','article_id','post_time'];
    public $timestamps = false;

    


}
