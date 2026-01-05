<?php

namespace App\Http\Controllers\Core\API\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\ItemRepositoryInterface;

// Event
use App\Events\Core\GeneralEventHandler;

// Helper
use App\Helpers\Core\GeneralHelper;

// Request
use App\Http\Requests\Core\ItemRequest;

// Model
use App\Models\Core\ItemDetail;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(ItemRepositoryInterface $repositoryInterface){
        $this->repositoryInterface = $repositoryInterface;
    }

    // List
    public function list(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Get data
            $datas = $this->repositoryInterface;

            // Sort data
            $datas->sort([
                'name' => 'ASC',
            ]);

            // Load column selection
            if(isset($request->select)){
                $datas->onlySelect($request->select);
            }

            // Load relation
            if(isset($request->relation)){
                $datas->withRelation($request->relation);
            }

            // Apply filters if provided
            $filters = $request->only(array_filter(array_keys($request->all()), function($key){
                return strpos($key, 'filter') === 0;
            }));

            // Run filter as sub-query
            if(!empty($filters)){
                $datas->query->where(function($query) use($filters){
                    foreach($filters as $filterKey => $filterValue){
                        // Status active filters
                        if(in_array($filterKey, ['filter-active'])){
                            // Skip if value is empty string or null, but allow "0" and false
                            if(in_array($filterValue, [null, ''])){
                                continue;
                            }
                            
                            // Convert to boolean if needed
                            if(is_string($filterValue)){
                                // Convert string to boolean
                                $filterValue = filter_var($filterValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                            }
                            
                            // Only apply filter if value is valid boolean, not null after conversion
                            if($filterValue !== null){
                                $query->where('is_active', $filterValue);
                            }
                        }
                    }
                });
            }

            // Return response
            return ($request->type === 'datatable') ? $datas->useDatatable()->all() : $datas->all();
        }, ['status' => 409, 'message' => false]);
    }

    // Create
    public function create(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new ItemRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Create item master and details in transaction
            $datas = DB::transaction(function() use($request){
                // Create item master
                $master = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'create')->create([
                    'name' => $request->name_master,
                    'description' => $request->description_master,
                ]);

                // Create multiple item details
                if($request->has('details') && is_array($request->details)){
                    foreach($request->details as $detailData){
                        ItemDetail::create([
                            'item_masters_id' => $master->id,
                            'name' => $detailData['name'],
                            'description' => $detailData['description'],
                        ]);
                    }
                }

                // Load relation
                $master->load('details');

                return $master;
            });

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Item created successfully.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Read
    public function read($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Get item data
            $datas = $this->repositoryInterface;
            
            // Load column selection
            if(isset($request->select)){
                $datas->onlySelect($request->select);
            }

            // Always load details relation
            $datas->withRelation(['details']);

            // Load additional relation if provided
            if(isset($request->relation)){
                $relations = is_array($request->relation) ? $request->relation : explode(',', $request->relation);
                $relations = array_filter($relations, function($rel) {
                    return $rel !== 'details';
                });
                if(!empty($relations)){
                    $datas->withRelation($relations);
                }
            }
            
            // Continue variable
            $datas = $datas->find($id);

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
            $validated = GeneralHelper::validate($request->all(), (new ItemRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Update item master and details in transaction
            $datas = DB::transaction(function() use($id, $request){
                // Update item master
                $master = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'update')->update($id, [
                    'name' => $request->name_master,
                    'description' => $request->description_master,
                ]);

                // Delete existing details
                ItemDetail::where('item_masters_id', $id)->delete();

                // Create new details
                if($request->has('details') && is_array($request->details)){
                    foreach($request->details as $detailData){
                        ItemDetail::create([
                            'item_masters_id' => $id,
                            'name' => $detailData['name'],
                            'description' => $detailData['description'],
                        ]);
                    }
                }

                // Load relation
                $master->load('details');

                return $master;
            });

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'Item updated successfully.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Delete
    public function delete($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Delete item data (actually toggles activation status)
            $result = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'delete')->activation($id);

            // Get action and data from result
            $action = $result['action'];

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => "Item {$action} successfully.",
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Bulk Delete
    public function bulkDestroy(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Validate input
            $ids = $request->input('ids', []);
            
            // Check if ids is provided and is array
            if(empty($ids) || !is_array($ids)){
                return GeneralHelper::jsonResponse([
                    'status'    => 400,
                    'message'   => 'No data selected.',
                ], 400);
            }

            // Delete item data (actually toggles activation status)
            $result = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'delete')->activation($ids);

            // Get action and data from result
            $action = $result['action'];
            $count = $result['data'];

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => "{$count} item(s) {$action} successfully.",
            ]);
        }, ['status' => 409, 'message' => false]);
    }
}

