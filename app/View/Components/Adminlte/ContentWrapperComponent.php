<?php

namespace App\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacades;
use Illuminate\View\Component;

class ContentWrapperComponent extends Component{
    public int $col;
    public string $title;
    public string $breadcrumb;
    public mixed $previous;

    /**
     * Create a new component instance.
     */
    public function __construct(int $col = 12, mixed $title = '', string $breadcrumb = '', string $previous = ''){
        $this->col = $breadcrumb ? 6 : $col;
        $this->title = $title ? $title : ViewFacades::getSection('title');
        $this->breadcrumb = $breadcrumb;
        $this->previous = $previous;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.content-wrapper-component');
    }
}
