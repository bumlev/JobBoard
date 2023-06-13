<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    use HasFactory;

    protected $table = "degrees";

    protected $fillable = ["name"];
    protected $hidden = [];

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }
}
