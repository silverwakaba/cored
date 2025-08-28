<?php

namespace App\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableComponent extends Component{
    public $id;

    /**
     * Create a new component instance.
     */
    public function __construct($id = null){
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.table-component');
    }
}
