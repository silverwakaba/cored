<?php

namespace App\Repositories\Core;

// Helper
use App\Helpers\Core\GeneralHelper;

// Model
use App\Models\Core\Notification;

// Interface
use App\Contracts\Core\NotificationRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;

class EloquentNotificationRepository extends BaseRepository implements NotificationRepositoryInterface{
    // Constructor
    public function __construct(Notification $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }
}
