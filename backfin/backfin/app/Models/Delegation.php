<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    use HasFactory;

    protected $table = 'delegation';

    protected $primaryKey = 'idDel';

    public $timestamps = true;

    protected $fillable = [
        'codeDel',
        'libelleDel',
        'region_id'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
}
