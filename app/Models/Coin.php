<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    use HasFactory;

    protected $fillable = ['price','description','user_id'];
    
    /**
     * The coin that has one user
     */
    public function user(){
        return $this->hasOne(User::class);
    }
}
