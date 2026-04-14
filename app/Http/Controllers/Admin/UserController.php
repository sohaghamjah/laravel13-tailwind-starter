<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BreadcrumbHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StaticHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\Permissions\UserPermission;
use App\Models\Admin\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private UserPermission $permission
    )
    {
        $this->permission = $permission;
    }

    /**
     * Display a listing of roles.
     */
    public function index(): View
    {
        $this->permission->checkAuthResponse($this->permission->canViewUser());

         // Set breadcrumbs
        BreadcrumbHelper::set([
            (object) ['label' => 'HRM', 'url' => null],
            (object) ['label' => 'User Management', 'url' => null]
        ]);

        $users = User::paginate(25);

        return view('admin.pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        $this->permission->checkAuthResponse($this->permission->canCreateUser());

        BreadcrumbHelper::set([
            (object) ['label' => 'HRM', 'url' => null],
            (object) ['label' => 'User Management', 'url' => route('admin.hrm.users.index')],
            (object) ['label' => 'User Create', 'url' => null],
        ]);
        $roles = Role::select('id', 'name')->get();
        return view('admin.pages.users.form', compact('roles'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(string $id): View
    {
        $this->permission->checkAuthResponse($this->permission->canUpdateUser());

        BreadcrumbHelper::set([
            (object) ['label' => 'HRM', 'url' => null],
            (object) ['label' => 'User Management', 'url' => route('admin.hrm.users.index')],
            (object) ['label' => 'User Edit', 'url' => null],
        ]);

        $user = User::find($id);
        if(!$user){
            abort(404, 'User not found');
        }
        return view('admin.pages.users.form', compact('user'));
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
        $this->permission->checkAuthResponse($this->permission->canCreateUser());

        if($request->target){
            // come from edit request
            $email_validation = 'required|email|unique:users,email,'.$request->target;
            $phone_validation = 'required|min:9|unique:users,mobile,'.$request->target;
            $password_validation = 'nullable';
            $confirm_password_validation = 'nullable';
        }else{
            // come from create request
            $email_validation = 'required|email|unique:users,email';
            $phone_validation = 'required|min:9|unique:users,mobile';
            $password_validation = 'required|confirmed|min:6';
            $confirm_password_validation = 'required';
        }

        $validator = Validator::make($request->all(), [
            'first_name'            => 'required|max:60|string',
            'last_name'             => 'required|max:60|string',
            'email'                 =>  $email_validation,
            'mobile'                 => $phone_validation,
            'password'              => $password_validation,
            'password_confirmation' => $confirm_password_validation,
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();


        // Handle image upload
        if ($request->hasFile('image')) {
            if($request->target){
                // Use uploadImage method instead of uploadAvatar
                $result = ImageHelper::updateImage(
                    $request->file('image'),
                    filesPath('users').'/'.$request->old_image,
                    filesPath('users'), // path
                    null,  // width
                    null, // height (auto)
                    85    // quality
                );
            }else{
                // Use uploadImage method instead of uploadAvatar
                $result = ImageHelper::uploadImage(
                    $request->file('image'),
                    filesPath('users'), // path
                    null,  // width
                    null, // height (auto)
                    85    // quality
                );
            }


            if ($result['success']) {
                $validated['image'] = $result['filename']; // Store just the filename
            }
        }


        // if come from create form
        if(!$request->target){
            $validated['password'] = Hash::make($validated['password']);
            $validated['status'] = true;
        }else{
            unset($validated['password']);
        }

        $validated['username'] = StaticHelper::generateUsername($validated['first_name'], $validated['last_name'], "users", 'username');


        try {
            User::updateOrCreate(['id' => $request->target], $validated);
            return redirect()->route('admin.hrm.users.index')->with(['success' => 'User Created Successfull']);
        } catch (\Exception $e) {
            return back()->with(['error' => SOMETHING_WRONG]);
        }

    }


    /**
     * Update user status via AJAX
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, string $id)
    {
        $this->permission->checkAuthResponse($this->permission->canUpdateUser());

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
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Update status
            $user->status = $request->status;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status' => $user->status
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
