<?php

namespace App\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DatatableComponent extends Component{
    public $id;
    public $method;
    public $debounce; // (in ms)
    public $tableUrl;
    public $deleteUrl;
    public $upsert;
    public $editable;
    public $filterable;
    public $reloadable;
    public $searchable;
    public $selectable;
    public $selectMode; // 'single' or 'multiple'
    public $bulkActions; // Array of action configs: [['text' => 'Delete', 'icon' => 'fa-trash', 'action' => 'delete', 'url' => 'route'], ...]

    /**
     * Create a new component instance.
     */
    public function __construct($id, $tableUrl, $debounce = 1500, $deleteUrl = null, $upsert = false, $editable = true, $filterable = false, $reloadable = false, $searchable = true, $method = 'GET', $selectable = false, $selectMode = 'multiple', $bulkActions = []){
        $this->id = $id;
        $this->method = $method;
        $this->debounce = $debounce;
        $this->tableUrl = $tableUrl;
        $this->deleteUrl = $deleteUrl;
        $this->upsert = (bool) $upsert;
        $this->editable = (bool) $editable;
        $this->filterable = (bool) $filterable;
        $this->reloadable = (bool) $reloadable;
        $this->searchable = (bool) $searchable;
        $this->selectable = (bool) $selectable;
        $this->selectMode = in_array($selectMode, ['single', 'multiple']) ? $selectMode : 'multiple';
        $this->bulkActions = is_array($bulkActions) ? $bulkActions : [];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.datatable-component');
    }
}
