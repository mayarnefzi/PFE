<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paramarchive2 extends Model
{
   
    protected $table = 'paramarchive2';
    protected $primaryKey = 'idArchive2';
    public $timestamps = true;

    protected $fillable = [ 'idArchive','ficheMisService', 'APD', 'ficheExp'];
}
