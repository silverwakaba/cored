<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalNote extends Model{
    // Trait
    use SoftDeletes;
    
    // Property
    protected $table = 'personal_notes';
    protected $primaryKey = 'id';
    public $timestamps = true;

    // Fillable
    protected $fillable = [
        'users_id',
        'is_public',
        'title',
        'content',
    ];

    // Load note owner
    public function belongsToUser(){
        return $this->belongsTo(User::class, 'users_id', 'id')->select(['id', 'name']); // By default only select needed column while ommit personal info
    }

    // Load share permission
    public function belongsToManyShares(){
        return $this->belongsToMany(User::class, 'personal_note_shares', 'personal_notes_id', 'shared_to_users_id')->select(['users.id', 'users.name']); // By default only select needed column while ommit personal info
    }

    // Load note comment alongside with commenter
    public function hasManyComments(){
        return $this->hasMany(PersonalNoteComments::class, 'personal_notes_id', 'id')->orderBy('created_at', 'DESC')->with(['belongsToUser']);
    }
}
