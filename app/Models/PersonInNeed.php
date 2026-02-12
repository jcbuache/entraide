<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonInNeed extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'task'];

    // Relation avec les inscriptions d'aide
    public function helpSignups()
    {
        return $this->hasMany(HelpSignup::class, 'person_in_need_id');
    }
}
