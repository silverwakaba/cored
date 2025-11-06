<?php

namespace App\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormComponent extends Component{
    public $id;
    public $asModal;
    public $isReset;
    public $redirect;

    /**
     * Create a new component instance.
     */
    public function __construct($id, $asModal = false, $isReset = true, $redirect = null){
        $this->id = $id;
        $this->asModal = $asModal;
        $this->isReset = $isReset;
        $this->redirect = $redirect;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.form-component');
    }
}
