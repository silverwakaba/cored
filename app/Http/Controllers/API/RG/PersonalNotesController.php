<?php

namespace App\Http\Controllers\API\RG;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\PersonalNotesRepositoryInterface;

// Helper
use App\Helpers\GeneralHelper;
use App\Helpers\RoleHelper;

// Request
use App\Http\Requests\PersonalNoteCommentRequest;
use App\Http\Requests\PersonalNoteCreateRequest;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonalNotesController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(PersonalNotesRepositoryInterface $repositoryInterface){
        $this->uid = isset(auth()->user()->id) ? auth()->user()->id : null;
        $this->repositoryInterface = $repositoryInterface;
    }

    // List
    public function list(Request $request){
        try{
            // Get data while sorting
            $datas = $this->repositoryInterface;

            // Sort data
            $datas->sort([
                'updated_at' => 'DESC',
            ]);

            // Load column selection
            if(isset($request->select)){
                $datas->onlySelect($request->select);
            }

            // Load relation
            if(isset($request->relation)){
                $datas->withRelation($request->relation);
            }

            // Response
            if(($request->type == 'datatable')){
                // Return response as datatable
                $datas = $datas->useDatatable()->all();
            } else {
                // Return response as plain query
                $datas = $datas->all();
            }

            // Return response
            return $datas;
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Create
    public function create(Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new PersonalNoteCreateRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return GeneralHelper::jsonResponse([
                    'status'    => 422,
                    'errors'    => $validator->errors(),
                ]);
            }

            // Create permission
            $datas = $this->repositoryInterface->create([
                'users_id'  => $this->uid,
                'is_public' => (bool) $request->is_public,
                'title'     => $request->title,
                'content'   => $request->content,
            ]);

            // User sync to sharing personal note
            $this->repositoryInterface->toUser($request->user_sync)->syncToUserShares($request->id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Personal notes created successfully.',
            ]);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Read
    public function read(Request $request){
        try{
            // Get permission data
            $datas = $this->repositoryInterface;
            
            // Load column selection
            if(isset($request->select)){
                $datas->onlySelect($request->select);
            }

            // Default relation variable is null
            $load_relation = [];

            // If relation is manually loaded
            if(isset($request->relation)){
                // Load relation as array to be parsed later
                $load_relation = [$request->relation];
            }

            // Load "belongsToManyShares" relation alongside with additional relation
            $datas->withRelation(['belongsToUser', 'belongsToManyShares', 'hasManyComments']);
            
            // Continue variable
            $datas = $datas->find($request->id);

            // Return not found response
            if(is_null($datas)){
                return GeneralHelper::jsonResponse([
                    'status' => 404,
                ]);
            }

            // Return forbidden response
            if(GeneralHelper::isNotesVisible($datas, $this->uid) == false){
                return GeneralHelper::jsonResponse([
                    'status' => 403,
                ]);
            }

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
            ]);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Update
    public function update(Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new PersonalNoteCreateRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return GeneralHelper::jsonResponse([
                    'status'    => 422,
                    'errors'    => $validator->errors(),
                ]);
            }

            // Update permission
            $datas = $this->repositoryInterface->update($request->id, [
                'is_public' => (bool) $request->is_public,
                'title'     => $request->title,
                'content'   => $request->content,
            ]);

            // User sync to sharing personal note
            $this->repositoryInterface->toUser($request->user_sync)->syncToUserShares($request->id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Personal notes updated successfully.',
            ]);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Delete
    public function delete(Request $request){
        try{
            // Delete permission data
            $delete = $this->repositoryInterface->delete($request->id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => 'Personal notes deleted successfully.',
            ]);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Comment
    public function comment(Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new PersonalNoteCommentRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return GeneralHelper::jsonResponse([
                    'status'    => 422,
                    'errors'    => $validator->errors(),
                ]);
            }

            // Post comment
            $datas = $this->repositoryInterface->postComment($request->id, [
                'users_id'          => $this->uid,
                'personal_notes_id' => $request->id,
                'comment'           => $request->comment,
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Personal notes commented successfully.',
            ]);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }
}
