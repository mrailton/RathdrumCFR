<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class AppLayout extends Component
{
    public function render(): View
    {
        return view('layouts.app');
    }
}
