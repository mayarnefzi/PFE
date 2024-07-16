<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'region';

    // Primary key
    protected $primaryKey = 'idReg';

    // Disable timestamps
    public $timestamps = true;

    // Fillable fields
    protected $fillable = [
        'codeReg',
        'libelleReg'
    ];
}