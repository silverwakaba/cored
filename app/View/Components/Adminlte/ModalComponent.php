<?php

namespace App\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalComponent extends Component{
    public string $tag;
    public string $id;
    public string $method;
    public string $enctype;
    public string $title;
    public string $button;
    public bool $asForm;

    /**
     * Create a new component instance.
     */
    public function __construct(bool $asForm = false, string $id = '', string $method = 'POST', string $enctype = 'application/x-www-form-urlencoded', string $title = '', string $button = ''){
        $this->asForm = $asForm;
        $this->tag = $asForm ? 'form' : 'div';
        $this->id = $id ? $id : md5(now());
        $this->method = in_array($method, ['GET', 'POST']) ? strtoupper($method) : 'POST';
        $this->enctype = $enctype;
        $this->title = $title ? $title : strtoupper('PLACEHOLDERTITLE');
        $this->button = $button ? $button : "Submit";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.modal-component');
    }
}
