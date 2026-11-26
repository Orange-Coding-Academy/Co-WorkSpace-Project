<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ManagerMembershipPlanController extends Controller
{
    // List plans belonging to the logged-in manager.
  public function index()
{
    $plans = MembershipPlan::with('workspace')
        ->whereHas('workspace', function ($query) {
            $query->where('manager_id', Auth::id());
        })
        ->withCount('memberships')
        ->latest()
        ->paginate(5);

    $workspaces = Workspace::where('manager_id', Auth::id())->get();

    return view('Manager.MembershipPlans.index', compact('plans', 'workspaces'));
}

    // Show create form.
    public function create()
    {
        $workspaces = Workspace::where('manager_id', Auth::id())
            ->get();

        return view('Manager.MembershipPlans.create', compact('workspaces'));
    }

    // Save a new plan.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'workspace_id' => [
                'required',
                Rule::exists('workspaces', 'id')
                    ->where('manager_id', Auth::id()),
            ],
            'plan_name' => ['required', 'string', 'max:255'],
            'duration_type' => [
                'required',
                Rule::in(['weekly', 'monthly', 'yearly', 'custom']),
            ],
            'duration_days' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        MembershipPlan::create($validated);

        return redirect()
            ->route('manager.membership-plans.index')
            ->with('success', 'Membership plan created successfully.');
    }

    // Show plan details.
    public function show($id)
    {
        $plan = $this->ownedPlan($id)
            ->load('workspace')
            ->loadCount('memberships');

        return view('Manager.MembershipPlans.show', compact('plan'));
    }

    // Show edit form.
    public function edit($id)
    {
        $plan = $this->ownedPlan($id);

        $workspaces = Workspace::where('manager_id', Auth::id())
            ->get();

        return view('Manager.MembershipPlans.edit', compact('plan', 'workspaces'));
    }

    // Update a plan.
    public function update(Request $request, $id)
    {
        $plan = $this->ownedPlan($id);

        $validated = $request->validate([
            'workspace_id' => [
                'required',
                Rule::exists('workspaces', 'id')
                    ->where('manager_id', Auth::id()),
            ],
            'plan_name' => ['required', 'string', 'max:255'],
            'duration_type' => [
                'required',
                Rule::in(['weekly', 'monthly', 'yearly', 'custom']),
            ],
            'duration_days' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $plan->update($validated);

        return redirect()
            ->route('manager.membership-plans.index')
            ->with('success', 'Membership plan updated successfully.');
    }

    // Activate or deactivate a plan.
    public function updateStatus(Request $request, $id)
    {
        $plan = $this->ownedPlan($id);

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $plan->update([
            'is_active' => $validated['is_active'],
        ]);

        return back()->with('success', 'Membership plan status updated.');
    }

    // Show subscribers for a plan.
    public function subscribers($id)
    {
        $plan = $this->ownedPlan($id);

        $subscribers = Membership::with('user')
            ->where('plan_id', $plan->id)
            ->latest()
            ->paginate(10);

        return view(
            'Manager.MembershipPlans.subscribers',
            compact('plan', 'subscribers')
        );
    }

    // Only return plans belonging to the logged-in manager.
    private function ownedPlan($id)
    {
        return MembershipPlan::whereHas('workspace', function ($query) {
                $query->where('manager_id', Auth::id());
            })
            ->findOrFail($id);
    }
}
