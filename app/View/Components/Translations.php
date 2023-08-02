<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;

class Translations extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $locale = app()->getLocale();
        $translations = [];
        if(File::exists(base_path("lang/$locale.json"))){
            $translations = json_decode(\File::get(base_path("lang/$locale.json")), true);
        }

        return view('components.translations', [
            'translations' => $translations,
        ]);
    }
}
