<?php

namespace App\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DatatableComponent extends Component{
    public $id;
    public $method;
    public $tableUrl;
    public $editUrl;
    public $deleteUrl;
    public $filterable;

    /**
     * Create a new component instance.
     */
    public function __construct($id, $tableUrl, $editUrl = true, $deleteUrl = null, $filterable = false, $method = 'GET'){
        $this->id = $id;
        $this->method = $method;
        $this->tableUrl = $tableUrl;
        $this->editUrl = $editUrl;
        $this->deleteUrl = $deleteUrl;
        $this->filterable = $filterable;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.datatable-component');
    }
}
