<?php

namespace App\Http\Controllers;

use App\Services\RequestServices;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

/*
 * IMPORTANT NOTE:
 * This controller naming is causing a havoc. Illuminate\Http\Request is the request class that is used
 * to handle all requests in Laravel. While App\Models\Request is the model class that is used to handle
 * Employee leave requests and such. So, if you want to refer to the model class, you have to use the full namespace
 * which is \App\Models\Request.
 */

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RequestServices $requestServices, Request $request)
    {
        return $requestServices->renderIndexPage();
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
    public function store(Request $request, ValidationServices $validationServices, RequestServices $requestServices)
    {
        $req = $validationServices->validateRequestCreationDetails($request);
        return $requestServices->createRequest($req, $request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, RequestServices $requestServices, Request $request)
    {
        return $requestServices->renderShowPage($id);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id){}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, RequestServices $requestServices, ValidationServices $validationServices)
    {
        $requestServices->updateRequest($request, $id);
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
