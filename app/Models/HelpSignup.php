<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpSignup extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'person_in_need_id', 'week_id'];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec la personne en besoin d'aide
    public function personInNeed()
    {
        return $this->belongsTo(PersonInNeed::class, 'person_in_need_id');
    }

    // Relation avec la semaine
    public function week()
    {
        return $this->belongsTo(Week::class, 'week_id');
    }
}
