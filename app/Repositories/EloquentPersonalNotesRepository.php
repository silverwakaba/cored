<?php

namespace App\Repositories;

// Helper
use App\Helpers\GeneralHelper;

// Model
use App\Models\PersonalNote;
use App\Models\User;

// Interface
use App\Contracts\PersonalNotesRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;

class EloquentPersonalNotesRepository extends BaseRepository implements PersonalNotesRepositoryInterface{
    // Property
    protected $userSharesSync;

    // Constructor
    public function __construct(PersonalNote $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }

    // Sync user
    public function toUser(mixed $user = null){
        // Get data type and its data
        $users = GeneralHelper::getType($user);

        // Set permission data
        $this->userToSync = collect(User::select('id')->whereIn('id', $users)->get())->pluck('id');
        
        // Chainable
        return $this;
    }

    // Sync notes with user
    public function syncToUserShares($id){
        return DB::transaction(function() use($id){
            // Find notes
            $datas = parent::find($id);

            // Sync notes to user via relation
            $datas->belongsToManyShares()->sync($this->userToSync);

            // Return response
            return $datas;
        });
    }

    // Post comment to notes
    public function postComment($id, array $data){
        return DB::transaction(function() use($id, $data){
            // Find notes
            $datas = parent::find($id);

            // Post comment to notes via relation
            $comment = $datas->hasManyComments()->create($data);

            // Return response
            return $comment;
        });
    }
}
