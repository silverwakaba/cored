<?php

namespace App\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarNavigationHeaderComponent extends Component{
    public $item;

    /**
     * Create a new component instance.
     */
    public function __construct($item){
        $this->item = $item;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.sidebar-navigation-header-component');
    }
}
