<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    protected $fillable = ['start_date'];

    // Relation avec les inscriptions d'aide
    public function helpSignups()
    {
        return $this->hasMany(HelpSignup::class, 'week_id');
    }
}
