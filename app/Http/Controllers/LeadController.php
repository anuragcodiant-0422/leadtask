<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        return view('leads.index', [
            'leads' => $query->paginate(3)->withQueryString(),
            'search' => $request->string('search')->toString(),
            'status' => $request->string('status')->toString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:leads,email',
            'phone' => 'required|string|max:20|unique:leads,phone',
            'company_name' => 'required|string|max:255',
            'source' => 'required|string|max:255',
        ]);
        $leadData = $request->except('_token');


        $lead = Lead::create($leadData);

        return redirect()->back()->with('success', 'Lead created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'change_status' => 'required|in:new,contacted,qualified,won,lost',
        ]);

        $currentStatus = $lead->change_status;
        $newStatus = $validated['change_status'];
        $allowedTransitions = [
            'new' => ['contacted', 'lost'],
            'contacted' => ['qualified', 'lost'],
            'qualified' => ['won', 'lost'],
            'won' => [],
            'lost' => [],
        ];

        if ($newStatus !== $currentStatus && ! in_array($newStatus, $allowedTransitions[$currentStatus], true)) {
            return response()->json([
                'message' => 'This lead status transition is not allowed.',
            ], 422);
        }

        $lead->update(['change_status' => $newStatus]);

        return response()->json([
            'message' => 'Lead status updated successfully.',
            'status' => $lead->change_status,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->back()->with('success', 'Lead deleted successfully.');
    }
}
