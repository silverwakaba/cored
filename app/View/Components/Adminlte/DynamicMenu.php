<?php

namespace App\View\Components\Adminlte;

// Repository interface
use App\Contracts\ApiRepositoryInterface;

// Internal
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Http;

class DynamicMenu extends Component{
    protected $apiRepository;
    public $menuItems;

    /**
     * Create a new component instance.
     */
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
        $this->menuItems = $this->apiRepository->withToken()->get('be.core.menu.index')->json();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string{
        return view('components.adminlte.dynamic-menu');
    }
}
