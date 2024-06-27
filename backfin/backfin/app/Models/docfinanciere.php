<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class docfinanciere extends Model
{
    protected $table = 'docfinanciere';
    protected $primaryKey = 'iddocfin';
    public $timestamps = true;

    protected $fillable = ['contract', 'propritere', 'montant', 'datecontract', 'datemaj'];
}
