<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CheckboxForm extends Component{
    public string $name;
    public string $text;
    public mixed $value;
    public bool $required;

    /**
     * Create a new component instance.
     */
    public function __construct(string $name, string $text = '', $value = '', bool $required = false){
        $this->name = $name;
        $this->text = $text;
        $this->value = $value;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.form.checkbox-form');
    }
}




