<?php

namespace App\Services;


use App\Mail\RequestStatusUpdated;
use Arr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class RequestServices
{
    public function createRequest($req, $request): \Illuminate\Http\RedirectResponse
    {
        // Check if the employee requested a leave request that is before present day, which is not allowed.
        $startDate = Carbon::createFromFormat('Y-m-d', $req['date'][0]);
        /* Check if the employee requested a leave request that is before present day, which is not allowed.
        Used !isAfter instead of isBefore because the latter rejects same day requests, which I don't intend to prevent */

        if ($req['type'] == 'leave' && $startDate->isBefore(Carbon::now()) && !$startDate->isSameDay(Carbon::now()))
            return back()->withErrors(['past_leave' => 'You can\'t make a leave request before today.']);

        $req['start_date'] = $req['date'][0];
        $req['end_date'] = $req['date'][1];
        $req = Arr::except($req, ['date']);
        $req['employee_id'] = $request->user()->id;

        \App\Models\Request::create($req);

        return to_route('requests.index');
    }
    public function updateRequest($request, $id)
    {
        $req = $request->validate([
            'status' => ['required', 'integer', 'in:1,2'],
            'admin_response' => ['nullable', 'string'],
        ]);
        $empReq = \App\Models\Request::findOrFail($id);
        $empReq->update($req);

        // Send Email to Employee informing them of the status update.
        Mail::to($empReq->employee->email)->send(new RequestStatusUpdated($empReq));
    }


}
