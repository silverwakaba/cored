<?php

namespace App\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacades;
use Illuminate\View\Component;

class ContentWrapperComponent extends Component{
    public $title;
    public $previous;

    /**
     * Create a new component instance.
     */
    public function __construct($title = null, $previous = null){
        $this->title = $title ? $title : ViewFacades::getSection('title');
        $this->previous = $previous ? $previous : null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.content-wrapper-component');
    }
}
