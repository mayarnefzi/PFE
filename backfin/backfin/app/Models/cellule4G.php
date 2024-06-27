<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cellule4G extends Model
{
    use HasFactory;

    protected $table = 'cellule4G';

    protected $primaryKey = 'idCel';
    public $timestamps = true;

    protected $fillable = [
        'codeCellule',
        'nomCellule',
        'EnodB',
        'tac',
        'sc',
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
