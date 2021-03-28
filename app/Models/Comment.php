<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    public $table = "post_user";

    protected $fillable = ['comment','user_id','post_id'];

    /**
     * The comment that belong to the user
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
