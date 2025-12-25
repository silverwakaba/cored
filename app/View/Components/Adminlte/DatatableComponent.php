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
    public $editUrl;
    public $deleteUrl;
    public $editable;
    public $filterable;
    public $searchable;

    /**
     * Create a new component instance.
     */
    public function __construct($id, $tableUrl, $debounce = 1500, $editUrl = true, $deleteUrl = null, $editable = true, $filterable = false, $searchable = true, $method = 'GET'){
        $this->id = $id;
        $this->method = $method;
        $this->debounce = $debounce;
        $this->tableUrl = $tableUrl;
        $this->editUrl = $editUrl;
        $this->deleteUrl = $deleteUrl;
        $this->editable = $editable;
        $this->filterable = $filterable;
        $this->searchable = (bool) $searchable;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.datatable-component');
    }
}
