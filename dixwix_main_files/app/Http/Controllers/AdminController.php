<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Type;
use App\Models\Group;
use App\Models\Setting;
use App\Models\Grouptype;
use App\Models\Groupmember;
use App\Models\Commission;
use App\Models\Point;
use Illuminate\Http\Request;
use App\Models\GroupUserInvited;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Notifications\GeneralNotification;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Book;
use App\Services\StripeService;
use Stripe\Customer;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function Dashboard(Request $request)
    {
        $data['title'] = 'Dashboard';
        $data['template'] = 'admin.dashboard';
        $groups = Group::get();
        $data['groups'] = $groups;
        return view('with_login_common', compact('data'));
    }

    public function homePgae(Request $request){

        $data['books'] = Book::with(['user'])
        ->where('status', 1)
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        // dd($data['books']);
        $data['title'] = 'Home Page';
        $data['template'] = 'admin.settings.home_page';
        // $data['script_file'] = 'add_item';
        // dd($data['books']->total());
        return view('with_login_common', compact('data'));
    }

    public function toggleStatus($id)
    {
        $book = Book::findOrFail($id);
        $book->is_on_desktop = $book->is_on_desktop == 1 ? 0 : 1;
        $book->save();

        return back()->with('success', 'Book status updated.');
    }

    public function AddCategory(Request $request, $id = null)
    {
        $retdata = [];
        $retdata["mode"] = "add";
        if (isset($id)) {
            $type = Type::find($id);
            $retdata["type"] = $type;
            $retdata["mode"] = "edit";
            $retdata["type_id"] = $id;
        }
        return $this->ReturnToCategoryAddPage($retdata);
    }

    public function addUser($id = null)
    {
        $retdata = [];
        $roles = Role::all();
        $data['title'] = 'Add User';
        $data['template'] = 'admin.users.add';
        $data['script_file'] = 'admin_user_script';
        return view('with_login_common', compact('data', 'retdata', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $formData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',
            'biodata' => 'nullable|string',
            'address' => 'nullable|string',
            'state' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:10',
            'password' => 'required|string|min:6',
            'profile_pic' => 'nullable|image|mimes:jpg,jpeg,png',
            // 'roles' => 'required|array',
            'roles' => 'sometimes|required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        if (auth()->user()->id === 1 && !$request->has('roles')) {
            throw ValidationException::withMessages(['roles' => 'The roles field is required.']);
        }

        if ($request->profile_pic) {
            $formData['profile_pic'] = $request->file('profile_pic')->store('profile_pictures','public');
        }

        $formData['email_verified_at'] = now();
        $formData['group_type'] = 1;
        unset($formData['roles']);

        $user = User::create($formData);
        if (auth()->user()->id == 1) {
            $user->syncRoles($request->roles);
        } else {
            $user->syncRoles('user');
        }

        $groupData = [
            "title" => "Dix New Group",
            "description" => "Please change title and description",
            "group_picture" => 'media/group-dummy.jpg',
            "created_by" => $user->id,
            "default" => true,
            "group_type" => 1,
            "status" => 0,
        ];

        $group = $user->createdgroups()->create($groupData);

        $userNotification = [
            'title' => 'Account created',
            'type' => 'account_created',
            'subject' => 'Dixwix Account Created',
            'message' => "Your account is created on dixwix by admin. Your credentials is below \n Email: {$user->email} \n Password: {$request->password}",
            'user_id' => auth()->user()->id,
            'url' => route('login'),
            'action' => 'Login',
        ];

        $groupNotification = [
            'only_email' => true,
            'title' => 'Group created',
            'type' => 'group_created',
            'subject' => 'New Group Created',
            'message' => 'A group created for you',
            'user_id' => auth()->user()->id,
            'url' => route('show-group', ['id' => $group->id]),
            'action' => 'View Group',
        ];

        $user->notify(new GeneralNotification($groupNotification));
        $user->notify(new GeneralNotification($userNotification));

        return redirect()->back()->with('success', 'User added successfully!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $data['title'] = 'Edit User';
        $data['template'] = 'admin.users.edit';
        $data['script_file'] = 'admin_user_script';
        return view('with_login_common', compact('data', 'user', 'roles'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $formData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => "required|email|unique:users,email,{$user->id}",
            'biodata' => 'nullable|string',
            'address' => 'nullable|string',
            'state' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:10',
            'password' => 'nullable|string|min:6',
            'profile_pic' => 'nullable|image|mimes:jpg,jpeg,png',
            'roles' => 'sometimes|required|array',
            // 'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        if (auth()->user()->id === 1 && !$request->has('roles')) {
            throw ValidationException::withMessages(['roles' => 'The roles field is required.']);
        }

        if (!$request->password) {
            unset($formData['password']);
        }

        if ($request->profile_pic) {

            if ($user->profile_pic && Storage::exists($user->profile_pic)) {
                Storage::delete($user->profile_pic);
            }
            $formData['profile_pic'] = $request->file('profile_pic')->store('profile_pictures','public');
        }

        unset($formData['roles']);

        $user->update($formData);
        if ($user->id != 1) {
            if (auth()->user()->id == 1) {
                $user->syncRoles($request->roles);
            } else {
                $user->syncRoles('user');
            }
        }

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function allUser__s(StripeService $stripeService)
    {
        // $data = [
        //     'title' => 'All Users',
        //     'template' => 'admin.users.list',
        // ];

        // $users = User::with([
        //     'roles',
        //     'membership',
        //     'membership.plan:id,name',
        //     'createdgroups:created_by,id,title',
        //     'createdgroups.groupmembers' => function ($query) {
        //         $query->where('member_role', 'admin')->where('activated', true);
        //     },
        //     'createdgroups.groupmembers.member:id,name'
        // ])->get();

        // foreach ($users as $user) {
       
        //     if ($user->stripe_customer_id) {
        //         $stripeBalance = $stripeService->getCustomerBalance($user->stripe_customer_id);

        //         $user->stripe_balance = $stripeBalance['balance'] ?? null;
        //         $user->stripe_cash_balance = $stripeBalance['cash_balance'] ?? null;
        //     } else {
        //         $user->stripe_balance = null;
        //         $user->stripe_cash_balance = null;
        //     }
        // }

        $data = [
            'title' => 'All Users',
            'template' => 'admin.users.list',
        ];

        // Step 1: Use paginate(10)
        $users = User::with([
            'roles',
            'membership',
            'membership.plan:id,name',
            'createdgroups:created_by,id,title',
            'createdgroups.groupmembers' => function ($query) {
                $query->where('member_role', 'admin')->where('activated', true);
            },
            'createdgroups.groupmembers.member:id,name'
        ])->get();
        // ->paginate(10);
        
        // ->paginate(10); // ✅ 10 users per page

        // Step 2: Loop to add Stripe balances
        // foreach ($users as $key => $user) {
            // if($user->email != 'rajadayo1@gmail.com'){
            //     continue;
            // }
            // if ($user->stripe_customer_id) {
            //     $stripeBalance = $stripeService->getCustomerBalance($user->stripe_customer_id);
            //     // dd($stripeBalance);
            //     $user->stripe_balance = $stripeBalance['balance'] ?? null;
            //     // $user->stripe_cash_balance = $stripeBalance['cash_balance'] ?? null;
            // } else {
            //     $user->stripe_balance = null;
            //     // $user->stripe_cash_balance = null;
            // }
            // dd($user);

            //    $stripeBalance = $stripeService->getCustomerBalance($user->stripe_customer_id);
            //     $user->stripe_balance = $stripeBalance['balance'] ?? null;
            //     $user->stripe_cash_balance = $stripeBalance['cash_balance'] ?? null;

            //     $cashBalance = $stripeBalance['Cash_balance']['usd'] ?? 0;
                // $formattedCashBalance = number_format($user->reward_balance / 100, 2);

            // //     // Update metadata in Stripe
            //     if (!empty($user->stripe_customer_id) && is_string($user->stripe_customer_id)) {
            //         Customer::update($user->stripe_customer_id, [
            //             'metadata' => [
            //                 'cash_balance' => 'USD '.$formattedCashBalance,
            //             ],
            //         ]);
            //     }
            // $customerId = (string) $user->stripe_customer_id;

            // if (Str::startsWith($customerId, 'cus_')) {
            //     Customer::update($customerId, [
            //         'metadata' => [
            //             'cash_balance' => 'USD ' . $formattedCashBalance,
            //         ],
            //     ]);
            // } else {
            //     echo 'Invalid Stripe customer ID format: ' . $customerId;
            // }
                
        // }
        // dd($users);
        return view('with_login_common', compact('data', 'users'));
    }

    public function usersPage()
    {
        $data = [
            'title' => 'All Users',
            'template' => 'admin.users.list',
        ];
        return view('with_login_common', compact('data'));
    }

    public function allUsers(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with([
                'roles',
                'membership.plan:id,name',
                'createdgroups:created_by,id,title',
                'createdgroups.groupmembers' => function ($query) {
                    $query->where('member_role', 'admin')->where('activated', true);
                },
                'createdgroups.groupmembers.member:id,name'
            ])->select('users.*'); // Important for DataTables
            return DataTables::of($users)
                ->addColumn('checkbox', function ($user) {
                    return '<input type="checkbox" class="row-checkbox" data-id="'.$user->id.'">';
                })
                ->filterColumn('name', fn($query, $keyword) => $query->where('users.name', 'like', "%{$keyword}%"))
                ->filterColumn('email', fn($query, $keyword) => $query->where('users.email', 'like', "%{$keyword}%"))
                ->addColumn('last_update', fn($user) => optional($user->updated_at)->format('Y-m-d H:i'))
                ->addColumn('dixwix_points', fn($user) => $user->reward_balance ?? 0)
                ->addColumn('stripe_balance', fn($user) => $user->reward_balance/100 ?? '-')
                ->addColumn('phone', fn($user) => $user->phone ?? '-')
                ->addColumn('created_groups', fn($user) => $user->createdgroups->pluck('title')->join(', '))
                ->addColumn('joined_groups', function ($user) {
                    return $user->usergroups
                        ->filter(fn($gm) => $gm->activated && $gm->group) // Only activated group members
                        ->pluck('group.title')
                        ->filter() // Remove nulls
                        ->join(', ') ?: '-';
                })
                ->addColumn('date_added', fn($user) => optional($user->created_at)->format('Y-m-d'))
                ->addColumn('roles', fn($user) => $user->roles->pluck('name')->join(', '))
                ->addColumn('action', function ($user) {
                    return '<button class="btn btn-danger btn-sm delete-user-btn" data-id="'.$user->id.'" data-name="'.e($user->name).'">Delete</button>';
                })
                ->rawColumns(['checkbox', 'action']) // Add checkbox to rawColumns
                ->make(true);
        }

        $data = [
            'title' => 'All Users',
            'template' => 'admin.users.list',
        ];
        return view('with_login_common', compact('data'));
    }

    public function deleteUser(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);

            if ($user->id == auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can delete your own account successfully',
                ]);
            }
            if ($user->id == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Super admin account cannot be deleted successfully',
                ]);
            }

            GroupUserInvited::where("email", $user->email)->delete();
            Groupmember::where('member_id', $user->id)->delete();

            $user->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteUserMultiple(Request $request)
    {
        try {
            $ids = $request->ids;

            if (!is_array($ids) || empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No users selected for deletion.',
                ]);
            }

            $authId = auth()->id();
            $filteredIds = collect($ids)->filter(function ($id) use ($authId) {
                return $id != $authId && $id != 1;
            })->values();

            if ($filteredIds->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete yourself or the super admin.',
                ]);
            }

            $users = User::whereIn('id', $filteredIds)->get();

            foreach ($users as $user) {
                GroupUserInvited::where("email", $user->email)->delete();
                Groupmember::where('member_id', $user->id)->delete();
                $user->forceDelete();
            }

            return response()->json([
                'success' => true,
                'message' => count($filteredIds) . ' user(s) deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete users: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function StoreCategory(Request $request)
    {
        $retdata = [];
        $data = $request->type;
        $mode = $request->mode;
        $type_id = $request->type_id;
        $retdata['mode'] = $mode;
        $retdata['type_id'] = $type_id;
        $type = null;
        $model = new Type();

        if ($mode == "add") {
            $data["created_at"] = date("Y-m-d H:i:s");
            $data["created_by"] = Auth::user()->id;
            $type = $model->add($data);
        } else if ($mode == "edit") {
            $type = $model->change($data, $type_id);
        }

        if (!is_object($type)) {
            if ($mode == "edit") {$errors = \App\Message\Error::get('type.change');} else { $errors = \App\Message\Error::get('type.add');}
            if (count($errors) == 0) {
                if ($mode == "edit") {$errors = \App\Message\Error::get('type.change');} else { $errors = \App\Message\Error::get('type.add');}
            }
        }
        if (isset($errors) && count($errors) > 0) {
            $message = returnErrorMsg($errors);
            $retdata['errs'] = $errors;
            $retdata['type'] = $data;
            $retdata['err_message'] = $message;
            // $this->flashError($retdata['err_message']);
            return $this->ReturnToCategoryAddPage($retdata);
        }
       
        $retdata["success"] = "Category " . ucfirst($mode) . "ed successfully";
        return redirect()->route('dashboard')->with('success', $retdata['success']);
    }

    public function deleteCategory(Request $request)
    {
        try {
            $type = Type::findOrFail($request->input('id'));

            $type->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function ReturnToCategoryAddPage($retdata = array())
    {
        $data['title'] = ucfirst($retdata["mode"]) . ' Category';
        $data['template'] = 'admin.category.add';
        $data['script_file'] = 'add_item';
        $data['group_types'] = Grouptype::get();
        return view('with_login_common', compact('data', 'retdata'));
    }

    public function ViewAllCategories(Request $request)
    {
        $data['title'] = 'View All Categories';
        $data['template'] = 'admin.category.list';
        $data['types'] = Type::with("grouptype")->get();
        $data['group_types'] = Grouptype::get();
        return view('with_login_common', compact('data'));
    }
  
  
    public function AddCommission(Request $request, $id = null)
    {
        $retdata = [];
        $retdata["mode"] = "add";
        if (isset($id)) {
            $type = Commission::find($id);
            $retdata["type"] = $type;
            $retdata["mode"] = "edit";
            $retdata["type_id"] = $id;
        }
        return $this->ReturnToCommissionAddPage($retdata);
    }
  
    public function ReturnToCommissionAddPage($retdata = array())
    {
        $data['title'] = ucfirst($retdata["mode"]) . ' Commission';
        $data['template'] = 'admin.site_commission.add';
        $data['script_file'] = 'add_item';
      $commission = Commission::first();
        return view('with_login_common', compact('data', 'retdata','commission'));
    }
  
    public function StoreCommission(Request $request){
      
      $request->validate([
            'commission' => 'required',
        ]);
      
       
        $update_date = date("Y-m-d H:i:s");
        Commission::updateOrCreate(
        ['id' => 1], // condition → target the first record
        ['commission' => $request->commission],
        ["updated_at" => $update_date]
    );

         
         // return back()->with('success', 'Your review has been saved successfully!');
      return redirect()
    ->route('add-commission')
    ->with('success', 'Your commission has been Saved/Updated successfully!');
    }
  
  
     public function CommissionHistory()
     {
       $data = [
            'title' => 'Site Commission History',
            'template' => 'admin.site_commission.site_commission_history',
        ];
        $earn_points = Point::where("user_id", Auth::user()->id)
            // ->whereNull('package_id')
            // ->where('type','credit')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('with_login_common', compact('data','earn_points'));
     }
     
    public function StoreCommission1(Request $request)
    { 
        $retdata = [];
        $data['commission'] = $request->commission;
        $data = $request->type;
        $mode = $request->mode;
        $type_id = $request->type_id;
        $retdata['mode'] = $mode;
        $retdata['type_id'] = $type_id;
        $type = null;
        $model = new Commission();
        $model->commission = $request->commission;
        
        $validated = $request->validate([
        'commission' => 'required',     
        ]);
      echo 'asdasdasd';die;
        if ($mode == "add") {
            $model["created_at"] = date("Y-m-d H:i:s");
              if (!$validated) { 
            Error::trigger('commission.add', $this->getErrors());
            return false;
            }else{
                echo "saving";die;
                $model->save();
              }
             
        } else if ($mode == "edit") {
            $type = $model->change($data, $type_id);
        }

        if (!is_object($type)) {
            if ($mode == "edit") {$errors = \App\Message\Error::get('type.change');} else { $errors = \App\Message\Error::get('type.add');}
            if (count($errors) == 0) {
                if ($mode == "edit") {$errors = \App\Message\Error::get('type.change');} else { $errors = \App\Message\Error::get('type.add');}
            }
        }
        if (isset($errors) && count($errors) > 0) {
            $message = returnErrorMsg($errors);
            $retdata['errs'] = $errors;
            $retdata['type'] = $data;
            $retdata['err_message'] = $message;
            // $this->flashError($retdata['err_message']);
            return $this->ReturnToCommissionAddPage($retdata);
        }
       
        $retdata["success"] = "Commission " . ucfirst($mode) . "ed successfully";
        return redirect()->route('dashboard')->with('success', $retdata['success']);
    }


    public function AddSettings(Request $request, $id = null)
    {
        $mode = $id ? "edit" : "add";
        $setting = $id ? Setting::findOrFail($id) : null;
        $setting_id = $id;

        $data = [
            'title' => ucfirst($mode) . ' Settings',
            'template' => 'admin.settings.add',
            'script_file' => 'add_item',
        ];

        return view('with_login_common', compact('data', 'mode', 'setting_id', 'setting'));
    }

    public function StoreSettings(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mode' => 'required|in:add,edit',
            'setting_id' => 'nullable|required_if:mode,edit|exists:settings,id',
        ]);
        $mode = $validated['mode'];
        $setting_id = $validated['setting_id'] ?? null;

        $setting = $setting_id ? Setting::find($setting_id) : null;

        $valueRule = 'required';
        if ($setting) {
            if ($setting->type == Setting::TYPE_BOOLEAN) {
                $valueRule .= '|in:0,1';
            } elseif ($setting->type == Setting::TYPE_NUMBER) {
                $valueRule .= '|numeric';
            } elseif ($setting->type == Setting::TYPE_STRING) {
                $valueRule .= '|string';
            }
        } else {
            $valueRule .= '|string';
        }

        $request->validate([
            'value' => $valueRule,
        ]);
        $data = [
            'name' => Str::snake($validated['name']),
            'value' => $request->value,
            'type' => $setting_id?$setting->type:$request->type
        ];

        try {
            if ($mode === "add") {
                Setting::create($data);
            } elseif ($mode === "edit" && $setting_id) {
                $setting->update($data);
            }

            return redirect()
                ->route('view-all-settings')
                ->with('success', "Setting " . ucfirst($mode) . "ed successfully.");
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()
                ->back()
                ->withErrors(['error' => 'An error occurred while processing your request.'])
                ->withInput();
        }
    }

    public function ViewAllSettings(Request $request)
    {
        $data['title'] = 'View All Settings';
        $data['template'] = 'admin.settings.list';
        $data['settings'] = Setting::get();
        return view('with_login_common', compact('data'));
    }

    public function groupDeleteDays(Request $request)
    {
        $data['title'] = 'Group Delete Days';
        $data['template'] = 'admin.settings.group_delete_days';
        $data['script_file'] = 'add_item';

        // Ensure the setting exists (default: 90)
        $setting = Setting::where('name', 'group_delete_days')->first();
        if (!$setting) {
            $setting = Setting::create([
                'name' => 'group_delete_days',
                'value' => '90',
                'type' => Setting::TYPE_NUMBER,
            ]);
        } elseif ((int) $setting->type !== Setting::TYPE_NUMBER) {
            $setting->update(['type' => Setting::TYPE_NUMBER]);
        }

        $deleteDays = is_numeric($setting->value) ? (int) $setting->value : 90;

        return view('with_login_common', compact('data', 'deleteDays', 'setting'));
    }

    public function updateGroupDeleteDays(Request $request)
    {
        $validated = $request->validate([
            'group_delete_days' => 'required|integer|min:0|max:3650',
        ]);

        Setting::updateOrCreate(
            ['name' => 'group_delete_days'],
            ['value' => (string) $validated['group_delete_days'], 'type' => Setting::TYPE_NUMBER]
        );

        return redirect()
            ->route('settings.group-delete')
            ->with('success', 'Group delete days updated successfully.');
    }

    public function runBackup()
    {
        try {
            // Run the backup command
            Artisan::call('backup:run --only-db');
            $output = Artisan::output();

            // Log the output for debugging
            \Log::info('Backup Output: ' . $output);

            return response()->json([
                'status' => 'success',
                'message' => 'Backup completed successfully.',
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            \Log::error('Backup Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Backup failed: ' . $e->getMessage(),
            ], 500);
        }
    }

}
