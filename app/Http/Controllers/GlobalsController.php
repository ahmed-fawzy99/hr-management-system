<?php

namespace App\Http\Controllers;

use App\Models\Globals;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;

class GlobalsController extends Controller
{
    public function index()
    {
        return Inertia::render('Globals/Globals', [
            'globals' => Globals::first()
        ]);
    }

    public function edit()
    {
        return Inertia::render('Globals/GlobalsEdit', [
            'globals' => Globals::first()
        ]);
    }

    public function update(Request $request, ValidationServices $validationServices)
    {
        $res = $validationServices->validateOrgDetailsUpdateData($request);
        Globals::first()->update($res);

        // Update Laravel Timezone
        $configPath = config_path('app.php');
        $config = file_get_contents($configPath);
        $config = preg_replace("/('timezone' => ').*?(')/", "$1$res[timezone]$2", $config);
        file_put_contents($configPath, $config);
        date_default_timezone_set($res['timezone']);

        // Clear Cache
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return to_route('globals.index', ['reload' => true]);
    }
}
