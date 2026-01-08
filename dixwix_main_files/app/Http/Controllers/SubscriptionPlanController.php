<?php

namespace App\Http\Controllers;

use App\Models\Membershipplan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $data['title'] = 'Subscription Plans';
        $data['template'] = 'admin.plan.list';
        $plans = Membershipplan::all();

        return view('with_login_common', compact('data', 'plans'));
    }
  
   public function destroy($id)
    {
        $plan = Membershipplan::findOrFail($id);
        $plan->delete();

        return redirect()->back()->with('success', 'Plan deleted successfully!');
    }
  
   public function create()
    {
        $data['title'] = 'Create Subscription Plan';
        $data['template'] = 'admin.plan.create'; // create form view
        return view('with_login_common', compact('data'));
    }
  
   public function store(Request $request)
{
    $formData = $request->validate([
        'name' => 'required|string|max:50',
       
        'allowed_items' => 'required|numeric|min:0',
        'price' => 'nullable|numeric',
       
        'FixedCategories' => 'nullable|string|max:255',
        'LendBorrowincluded' => 'nullable|boolean',
        'qr' => 'nullable|boolean',
        'reward' => 'nullable|boolean',
        'google' => 'nullable|boolean',
        'notification' => 'nullable|boolean',
    ]);

    Membershipplan::create([
        'name' => $formData['name'],
       
        'allowed_items' => $formData['allowed_items'],
        'price' => $formData['price'] ?? null,
       
        'FixedCategories' => $formData['FixedCategories'] ?? null,
        'LendBorrowincluded' => $formData['LendBorrowincluded'] ?? 0,
        'qr' => $formData['qr'] ?? 0,
        'reward' => $formData['reward'] ?? 0,
        'google' => $formData['google'] ?? 0,
        'notification' => $formData['notification'] ?? 0,
    ]);

    return redirect('subscription-plans')->with('success', 'New plan created successfully!');
}


    public function edit($id)
    {
        $plan = Membershipplan::findOrFail($id);
        $data['title'] = 'Edit Subscription Plan';
        $data['template'] = 'admin.plan.edit';
        return view('with_login_common', compact('data', 'plan'));
    }

    public function update(Request $request, $id)
{
    $plan = Membershipplan::findOrFail($id);

    // Validate the request
    $formData = $request->validate([
        'name' => 'required|string|max:50',
       
        'allowed_items' => 'required|numeric|min:0',
        'price' => 'nullable|numeric',
       

        'FixedCategories' => 'nullable|string|max:255',
        'LendBorrowincluded' => 'nullable|boolean',
        'qr' => 'nullable|boolean',
        'reward' => 'nullable|boolean',
        'google' => 'nullable|boolean',
        'notification' => 'nullable|boolean',
    ]);

    // Assign validated data
    $plan->name = $formData['name'];
   
    $plan->allowed_items = $formData['allowed_items'];
    $plan->price = $formData['price'] ?? null;
   
    $plan->FixedCategories = $formData['FixedCategories'] ?? null;
    $plan->LendBorrowincluded = $formData['LendBorrowincluded'] ?? 0;
    $plan->qr = $formData['qr'] ?? 0;
    $plan->reward = $formData['reward'] ?? 0;
    $plan->google = $formData['google'] ?? 0;
    $plan->notification = $formData['notification'] ?? 0;

    // Save the plan
    $plan->save();

    return redirect()->back()->with('success', 'Plan updated successfully!');
}

}
