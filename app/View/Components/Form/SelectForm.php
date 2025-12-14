<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectForm extends Component{
    public string $name;
    public string $text;
    public bool $hidden;
    public bool $required;
    public bool $multiple;


    /**
     * Create a new component instance.
     */
    public function __construct(string $name, string $text = '', bool $hidden = false, bool $required = false, bool $multiple = false){
        $this->name = $name;
        $this->text = $text;
        $this->hidden = $hidden;
        $this->required = $required;
        $this->multiple = $multiple;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.form.select-form');
    }
}
