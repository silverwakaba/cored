<?php

namespace App\Http\Controllers\Core\API\Core\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\MenuRepositoryInterface;

// Model
use App\Models\Menu;

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
