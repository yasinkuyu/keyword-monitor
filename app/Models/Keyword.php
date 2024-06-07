<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;
    
    protected $fillable = ['keyword', 'domain_id'];

    public function domain()
    {
        return $this->belongsTo(Domain::class,);
    }

    public function positions()
    {
        return $this->hasMany(KeywordPosition::class);
    }
}
