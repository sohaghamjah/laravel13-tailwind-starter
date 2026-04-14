<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BreadcrumbHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StaticHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(): View
    {
         // Set breadcrumbs
        BreadcrumbHelper::set([
            (object) ['label' => 'HRM', 'url' => null],
            (object) ['label' => 'Admin Management', 'url' => null]
        ]);

        $admins = Admin::with('role')->paginate(25);
        $roles = Role::select('id', 'name')->get()->keyBy('id');

        return view('admin.pages.admins.index', compact('admins', 'roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        BreadcrumbHelper::set([
            (object) ['label' => 'HRM', 'url' => null],
            (object) ['label' => 'Admin Management', 'url' => route('admin.hrm.admins.index')],
            (object) ['label' => 'Admin Create', 'url' => null],
        ]);
        $roles = Role::select('id', 'name')->get();
        return view('admin.pages.admins.form', compact('roles'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(string $id): View
    {
        BreadcrumbHelper::set([
            (object) ['label' => 'HRM', 'url' => null],
            (object) ['label' => 'Admin Management', 'url' => route('admin.hrm.admins.index')],
            (object) ['label' => 'Admin Edit', 'url' => null],
        ]);

        $admin = Admin::find($id);
        if(!$admin){
            abort(404, 'Admin not found');
        }
        $roles = Role::select('id', 'name')->get();
        return view('admin.pages.admins.form', compact('roles','admin'));
    }

    /**
     * Role store
     *
     * @method POST
     * @param @return Illuminate\Http\Request $request
     * @return Illuminate\Http\Request Response
     */
    public function store(Request $request)
    {
        if($request->target){
            // come from edit request
            $email_validation = 'required|email|unique:admins,email,'.$request->target;
            $phone_validation = 'required|min:9|unique:admins,phone,'.$request->target;
            $password_validation = 'nullable';
            $confirm_password_validation = 'nullable';
            $role_validation = 'nullable';
        }else{
            // come from create request
            $email_validation = 'required|email|unique:admins,email';
            $phone_validation = 'required|min:9|unique:admins,phone';
            $password_validation = 'required|confirmed|min:6';
            $confirm_password_validation = 'required';
            $role_validation = 'required';
        }

        $validator = Validator::make($request->all(), [
            'first_name'            => 'required|max:60|string',
            'last_name'             => 'required|max:60|string',
            'email'                 =>  $email_validation,
            'phone'                 => $phone_validation,
            'password'              => $password_validation,
            'password_confirmation' => $confirm_password_validation,
            'role'                  => $role_validation,
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();


        // Handle image upload
        if ($request->hasFile('image')) {
            // Use uploadImage method instead of uploadAvatar
            $result = ImageHelper::uploadImage(
                    $request->file('image'),
                    filesPath('admins'), // path
                    null,  // width
                    null, // height (auto)
                    85    // quality
                );

            if ($result['success']) {
                $validated['image'] = $result['filename']; // Store just the filename
            }
        }


        // if come from create form
        if(!$request->target){
            $validated['password'] = Hash::make($validated['password']);
            $validated['role_id'] = $validated['role'];
            $validated['status'] = true;
        }else{
            unset($validated['password']);
        }

        $validated['user_name'] = StaticHelper::generateUsername($validated['first_name'], $validated['last_name'], "admins");


        try {
            Admin::updateOrCreate(['id' => $request->target], $validated);
            return redirect()->route('admin.hrm.admins.index')->with(['success' => 'Admin Created Successfull']);
        } catch (\Exception $e) {
            return back()->with(['error' => SOMETHING_WRONG]);
        }

    }


    /**
     * Update admin status via AJAX
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, string $id)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value'
                ], 422);
            }

            // Find admin
            $admin = Admin::find($id);

            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found'
                ], 404);
            }

            // Prevent admin from disabling their own account
            if ($admin->id == auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot change your own status'
                ], 403);
            }

            // Update status
            $admin->status = $request->status;
            $admin->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status' => $admin->status
            ]);

        } catch (\Exception $e) {
            \Log::error('Admin status update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status. Please try again.'
            ], 500);
        }
    }
}
