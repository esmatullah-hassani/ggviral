<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['video_path','discription','user_id'];

    /**
     * The post that belong to the user.
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
