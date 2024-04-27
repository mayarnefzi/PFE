<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteGSM extends Model
{
    protected $table = 'sitegsm';
    protected $primaryKey = 'idSite'; // Utilisez idSite comme clé primaire

    public $timestamps = true;

    // Vous pouvez définir les attributs fillable si nécessaire
    protected $fillable = ['codesite', 'nomsite', 'region', 'delegotion', 'secteur', 'x', 'y', 'fournisseur', 'HBA', 'antenne', 'alimentation', 'acces'];

    use HasFactory;
}


