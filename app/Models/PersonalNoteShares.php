<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalNoteShares extends Model{
    // Property
    protected $table = 'personal_note_shares';
    protected $primaryKey = 'id';
    public $timestamps = false;

    // Fillable
    protected $fillable = [
        'users_id',
        'personal_notes_id',
        'comment',
    ];

    // Load comment owner
    public function belongsToUser(){
        return $this->belongsTo(User::class, 'users_id', 'id')->select(['id', 'name']); // By default only select needed column while ommit personal info
    }
}
