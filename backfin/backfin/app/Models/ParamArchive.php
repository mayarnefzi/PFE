<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ParamArchive extends Model
{
    protected $table = 'paramarchive';
    protected $primaryKey = 'idArchive';
    public $timestamps = true;

    protected $fillable = [ 'idArchive','ficheMisService', 'APD', 'ficheExp'];
}
