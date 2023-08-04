<?php

namespace App\Http\Controllers;

use App\Services\RequestServices;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Inertia\Inertia;


// Using \App\Models\Request instead of Request because Request is a class in Illuminate\Http\Request
class RequestController extends Controller
{
    protected RequestServices $requestServices;
    protected ValidationServices $validationServices;
    public function __construct()
    {
        $this->requestServices = new RequestServices;
        $this->validationServices = new ValidationServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = \App\Models\Request::join('employees', 'requests.employee_id', '=', 'employees.id')
            ->select(['requests.id', 'employees.name as employee_name', 'requests.type', 'requests.start_date',
                'requests.end_date', 'requests.status', 'requests.is_seen']);

        if (!isAdmin()) {
            $requests->where('requests.employee_id', auth()->user()->id);
        }
        return Inertia::render('Request/Requests', [
            'requests' => $requests->orderBy('requests.status')
                ->paginate(config('constants.data.pagination_count')),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Request/RequestCreate', [
            'types' => ['complaint', 'payment', 'leave', 'other'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $req = $this->validationServices->validateRequestCreationDetails($request);
        return $this->requestServices->createRequest($req, $request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $request = \App\Models\Request::with('employee')->findOrFail($id);
        authenticateIfNotAdmin(auth()->user()->id, $request->employee_id);

        if (auth()->user()->id == $request->employee_id && $request->status != 'Pending') {
            // Mark the request as seen by the employee if it was approved or rejected.
            // This will be used to display the number of unseen requests in the sidebar of user dashboard.
            $request->update(['is_seen' => true]);
        }
        return Inertia::render('Request/RequestView', [
            'request' => $request,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->requestServices->updateRequest($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        \App\Models\Request::findOrFail($id)->delete();
        return to_route('requests.index');
    }
}
