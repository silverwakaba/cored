<?php

namespace App\Core\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class BoxComponent extends Component{
    public $colors;
    public $icon;
    public $title;
    public $content;
    public $link;

    /**
     * Create a new component instance.
     */
    public function __construct($title, $colors = null, $icon = null, $content = null, $link = null){
        $this->colors = $colors ? $colors : 'bg-secondary';
        $this->icon = $icon;
        
        $this->title = $title;
        $this->content = $content ? $content : Str::of("Manage $title.");
        
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.box-component');
    }
}

