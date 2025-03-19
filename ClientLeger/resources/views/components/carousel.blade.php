<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Carousel extends Component
{
    public $bestSellers;

    public function __construct($bestSellers)
    {
        $this->bestSellers = $bestSellers;
    }
   

    public function render()
    {
        return view('components.carousel');
    }
}


