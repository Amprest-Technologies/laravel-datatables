<?php

namespace Amprest\LaravelDatatables\View\Components;

use Illuminate\View\Component;

class DatatablesScripts extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return <<<'blade'
            <script defer type="text/javascript" src="{{ route('datatables.app.js') }}"></script>
            @stack('datatables-config')
        blade;
    }
}
