<?php

namespace App\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProcessingStateComponent extends Component{
    public string $type;
    public string $reset;
    public string $submit;
    public string $overlay;

    /**
     * Create a new component instance.
     */
    public function __construct(string $type){
        $this->type = $type;
        $this->reset = 'buttonReset' . ucfirst($type);
        $this->submit = 'buttonSubmit' . ucfirst($type);
        $this->overlay = 'overlay-' . $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.processing-state-component');
    }
}
