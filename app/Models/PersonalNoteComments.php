<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalNoteComments extends Model{
    // Trait
    use SoftDeletes;
    
    // Property
    protected $table = 'personal_note_comments';
    protected $primaryKey = 'id';
    public $timestamps = true;

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
