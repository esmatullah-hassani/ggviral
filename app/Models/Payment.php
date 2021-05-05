<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    public $fillable = ['amount',"title",'user_id'];

    /**
     * The coin that has one user
     */
    public function user(){
        return $this->hasOne(User::class);
    }
}
