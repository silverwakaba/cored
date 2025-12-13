<?php

namespace App\View\Components\Adminlte;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardComponent extends Component{
    public string $tag;
    public string $id;
    public string $method;
    public string $enctype;
    public string $title;
    public string $button;
    public string $sitekeyCaptcha;
    public bool $asForm;
    public bool $upsert;
    public bool $withCaptcha;

    /**
     * Create a new component instance.
     */
    public function __construct(bool $asForm = false, bool $upsert = false, bool $withCaptcha = false, string $id = '', string $method = 'POST', string $enctype = 'application/x-www-form-urlencoded', string $title = '', string $button = ''){
        $this->asForm = $asForm;
        $this->upsert = $upsert;
        $this->withCaptcha = $withCaptcha;
        $this->sitekeyCaptcha = $withCaptcha ? env('HCAPTCHA_SITEKEY') : '10000000-ffff-ffff-ffff-000000000001';
        $this->tag = $asForm ? 'form' : 'div';
        $this->id = $id ? $id : md5(now());
        $this->method = in_array($method, ['GET', 'POST']) ? strtoupper($method) : 'POST';
        $this->enctype = $enctype;
        $this->title = $title;
        $this->button = $button ? $button : "Submit";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.card-component');
    }
}




