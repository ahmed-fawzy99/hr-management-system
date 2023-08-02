<?php

namespace App\Http\Middleware;

use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? $request->user()->only('id', 'name', 'email')
                        + ["roles"=>$request->user()->getRoleNames()] : null,
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'ui' => [
                'empCount'=> Employee::count(),
                // Admin sees pending requests count in the sidebar, while employees see only updated requests count.
                'reqCount'=> $request->user() ? ( isAdmin() ? \App\Models\Request::where('status', 0)->count() :
                                        \App\Models\Request::where('employee_id', auth()->user()->id)
                                            ->where('status', '!=', 0)->where('is_seen', false)->count()) : null,
            ],
            'session' => [
                'update_in_progress' => session('update_in_progress'),
            ],
            'locale'=> config('app.locale'),
            'timezone'=> config('app.timezone'),
        ]);
    }
}
