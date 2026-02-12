<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name']; // Ajoute 'name' comme attribut remplissable

    // Relation avec User (plusieurs utilisateurs peuvent avoir un rôle)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
