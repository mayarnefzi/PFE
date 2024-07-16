<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cellule2G extends Model
{
       use HasFactory;

    protected $table = 'cellule2G';

    protected $primaryKey = 'idCel';
    public $timestamps = true;

    protected $fillable = [
        'codeCellule',
        'nomCellule',
        'lac',
        'bcch',
        'power',
        'mlt',
        'azimuth',
        'bande',
        'idSite',
    ];

    public function site()
    {
        return $this->belongsTo(SiteGSM::class, 'idSite', 'idSite');
    }




















      
}




