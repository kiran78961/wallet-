<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserFactory;


class Post extends Model
{
     protected $fillable=[
        'title',
        'description',
        'image'
    ];
}
