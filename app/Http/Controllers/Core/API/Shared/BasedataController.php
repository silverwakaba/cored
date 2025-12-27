<?php

namespace App\Http\Controllers\Core\API\Shared;
use App\Http\Controllers\Controller;

// Helper
use App\Helpers\Core\GeneralHelper;

// Model
use App\Models\Core\BaseBoolean;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BasedataController extends Controller{
    // Boolean
    public function boolean(){
        return GeneralHelper::safe(function(){
            $datas = BaseBoolean::orderBy('id', 'ASC')->get();

            return $datas;
        }, ['status' => 409, 'message' => false]);
    }
}
