<?php

namespace App\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DatatableComponent extends Component{
    public $id;
    public $method;
    public $tableUrl;

    /**
     * Create a new component instance.
     */
    public function __construct($id, $tableUrl, $method = 'GET'){
        $this->id = $id;
        $this->method = $method;
        $this->tableUrl = $tableUrl;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.datatable-component');
    }
}
