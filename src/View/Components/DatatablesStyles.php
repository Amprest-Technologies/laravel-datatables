<?php

namespace Amprest\LaravelDatatables\View\Components;

use Illuminate\View\Component;

class DatatablesStyles extends Component
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
            <link href="{{ route(name: 'datatables.app.css', absolute: false) }}">
        blade;
    }
}
