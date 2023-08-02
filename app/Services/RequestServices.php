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

    public function renderIndexPage(): \Inertia\Response
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
    public function renderShowPage($id): \Inertia\Response
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

}
