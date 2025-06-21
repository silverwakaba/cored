<?php

namespace App\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardComponent extends Component{
    public string $tag;
    public string $action;
    public string $method;
    public string $enctype;
    public string $title;
    public bool $asForm;

    /**
     * Create a new component instance.
     */
    public function __construct(bool $asForm = false, string $action = '', string $method = 'POST', string $enctype = 'application/x-www-form-urlencoded', string $title = ''){
        $this->asForm = $asForm;
        $this->tag = $asForm ? 'form' : 'div';
        $this->action = $action;
        $this->method = strtoupper($method);
        $this->enctype = $enctype;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.card-component');
    }
}
