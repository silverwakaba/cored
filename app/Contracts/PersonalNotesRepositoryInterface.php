<?php

namespace App\Contracts;

interface PersonalNotesRepositoryInterface{
    public function syncToUserShares($id);
    public function postComment($id, array $data);
}
