<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputForm extends Component{
    public string $name;
    public string $type;
    public string $text;
    public bool $hidden;
    public bool $required;
    public bool $asFile;
    public bool $asTextarea;


    /**
     * Create a new component instance.
     */
    public function __construct(string $name, string $type, string $text = '', bool $hidden = false, bool $required = false, bool $asFile = false, bool $asTextarea = false){
        $this->name = $name;
        $this->type = $type;
        $this->text = $text;
        $this->hidden = $hidden;
        $this->required = $required;
        $this->asFile = $asFile;
        $this->asTextarea = $asTextarea;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.form.input-form');
    }
}
