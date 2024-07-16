<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Secteur extends Model
{
    use HasFactory;

    protected $table = 'secteur';
    
    protected $primaryKey = 'idSecteur';

    protected $fillable = [
        'codeSec',
        'libelleSec',
        'delegation_id'
    ];

}
