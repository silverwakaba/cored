<?php

namespace App\Http\Controllers\Core\API\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\MenuRepositoryInterface;

// Event
use App\Events\Core\GeneralEventHandler;

// Helper
use App\Helpers\Core\GeneralHelper;

// Request
use App\Http\Requests\Core\MenuCreateRequest;

// Model
use App\Models\Core\Menu;

// Internal
use Illuminate\Http\Request;

class MenuController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(MenuRepositoryInterface $repositoryInterface){
        $this->repositoryInterface = $repositoryInterface;
    }

    // Index
    public function index(){
        return $this->repositoryInterface->index();
    }

    // List
    public function list(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Get data with hierarchical structure (all menus visible)
            $datas = $this->repositoryInterface->list();

            // Load column selection
            if(isset($request->select)){
                $datas->onlySelect($request->select);
            }

            // Load relation
            if(isset($request->relation)){
                $datas->withRelation($request->relation);
            }

            // Filter by load_type if provided (for loading parent options - headers and parents only)
            // Handle both load_type and load_type[] (array format)
            $loadType = $request->load_type ?? $request->input('load_type[]');
            if($loadType){
                $loadTypes = is_array($loadType) ? $loadType : [$loadType];
                $loadTypes = array_filter($loadTypes, function($val){
                    return !empty($val) && $val !== '';
                });
                if(!empty($loadTypes)){
                    $datas->query->whereIn('type', $loadTypes);
                }
            }

            // Return response
            if($request->type === 'datatable'){
                return $datas->useDatatable()->all();
            } else {
                // Return as JSON response for non-datatable requests
                return GeneralHelper::jsonResponse([
                    'status' => 200,
                    'data' => $datas->all(),
                ]);
            }
        }, ['status' => 409, 'message' => true]);
    }

    // Create
    public function create(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new MenuCreateRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Prepare menu data (map form field names to database column names)
            $menuData = [
                'name'              => $request->name,
                'icon'              => $request->icon,
                'route'             => $request->route,
                'type'              => $request->type,
                'parent_id'         => $request->parent ?? null,
                'is_authenticate'   => $request->authenticate ?? null,
                'is_guest_only'     => $request->guest_only ?? null,
            ];

            // Determine position and reference
            $position = $request->position ?? 'after';
            $referenceId = $request->reference_id ?? null;

            // Create menu
            $datas = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'create')->createMenu($menuData, $position, $referenceId);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Menu created successfully.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Read
    public function read($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Get menu data
            $datas = $this->repositoryInterface;
            
            // Load column selection
            if(isset($request->select)){
                $datas->onlySelect($request->select);
            }

            // Load relation
            if(isset($request->relation)){
                $datas->withRelation($request->relation);
            }
            
            // Continue variable
            $datas = $datas->read($id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Update
    public function update($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new MenuCreateRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Prepare menu data (map form field names to database column names)
            $menuData = [
                'name'              => $request->name,
                'icon'              => $request->icon,
                'route'             => $request->route,
                'type'              => $request->type,
                'parent_id'         => $request->parent ?? null,
                'is_authenticate'   => $request->authenticate ?? null,
                'is_guest_only'     => $request->guest_only ?? null,
            ];

            // Update menu data
            $datas = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'update')->updateMenu($id, $menuData);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'Menu updated successfully.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Update Position
    public function updatePosition($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), [
                'position'      => ['required', 'string', 'in:before,after'],
                'reference_id'  => ['required', 'integer', 'exists:menus,id'],
            ]);

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Update menu position
            $datas = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'update')->updateMenuPosition($id, $request->position, $request->reference_id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'Menu position updated successfully.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Delete
    public function delete($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Delete menu data
            $datas = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'delete')->deleteMenu($id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => 'Menu deleted successfully.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Test
    public function test(){
        /*
         * Testing for Create - All passed
        */

        // // Create header after "4: Apps" - Test Header - ID after test: 9
        // $this->repositoryInterface->createMenu([
        //     'name'              => 'Test Header',
        //     'type'              => 'h',
        //     'parent_id'         => null,
        //     'is_authenticate'   => true,
        // ], 'after', 4);

        // // Create parent for "9: Test Header" - Test Parent - ID after test: 10
        // $this->repositoryInterface->createMenu([
        //     'name'              => 'Test Parent',
        //     'type'              => 'p',
        //     'parent_id'         => 9,
        // ], 'after');

        // // Create child for "10: Test Parent" - Test Child 1 - ID after test: 11
        // $this->repositoryInterface->createMenu([
        //     'name'              => 'Test Child 1',
        //     'type'              => 'c',
        //     'parent_id'         => 10,
        // ], 'after');

        // // Create child for "10: Test Parent" - Test Child 2 - ID after test: 12
        // $this->repositoryInterface->createMenu([
        //     'name'              => 'Test Child 2',
        //     'type'              => 'c',
        //     'parent_id'         => 10,
        // ], 'after', 11);

        // // Create child for "10: Test Parent" - Test for Moving - ID after test: 13
        // $this->repositoryInterface->createMenu([
        //     'name'              => 'Test for Moving',
        //     'type'              => 'c',
        //     'parent_id'         => 10,
        // ], 'after', 12);

        /*
         * Testing for Moving - All passed
        */

        // // Update menu order: 13 before 11
        // $this->repositoryInterface->updateMenuPosition(13, 'before', 11);

        /*
         * Testing for Delete - All passed
        */

        // // Delete menu: 13
        // $this->repositoryInterface->deleteMenu(13);
    }
}
