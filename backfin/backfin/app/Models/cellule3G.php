<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cellule3G extends Model
{
    use HasFactory;

    protected $table = 'cellule3G';

    protected $primaryKey = 'idcel';
    public $timestamps = true;

    protected $fillable = [
        'codeCellule',
        'nomCellule',
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
