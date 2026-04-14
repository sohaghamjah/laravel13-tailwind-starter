<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BreadcrumbHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Permissions\RolePermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RolePermissionController extends Controller
{
    public function __construct(
        private RolePermission $permission
    )
    {
        $this->permission = $permission;
    }

    /**
     * Display a listing of roles.
     */
    public function index(): View
    {
        $this->permission->checkAuthResponse($this->permission->canViewRoles());

        BreadcrumbHelper::set([
            (object) ['label' => 'Role Permission', 'url' => null]
        ]);

        $roles = DB::table('roles')
            ->select('roles.*', DB::raw('COUNT(DISTINCT model_has_roles.model_id) as users_count'))
            ->leftJoin('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->groupBy('roles.id', 'roles.name', 'roles.code', 'roles.guard_name', 'roles.deletable', 'roles.created_at', 'roles.updated_at')
            ->orderBy('roles.created_at', 'desc')
            ->paginate(25);

        return view('admin.pages.role-permission.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
         $this->permission->checkAuthResponse($this->permission->canCreateRole());

        BreadcrumbHelper::set([
            (object) ['label' => 'Role Permission', 'url' => null],
            (object) ['label' => 'Create Role', 'url' => route('admin.role-permissions.index')]
        ]);
        $permissions = DB::table('permissions')->get();
        return view('admin.pages.role-permission.form', compact('permissions'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(string $id): View
    {
         $this->permission->checkAuthResponse($this->permission->canUpdateRole());

        BreadcrumbHelper::set([
            (object) ['label' => 'Role Permission', 'url' => null],
            (object) ['label' => 'Edit Role', 'url' => route('admin.role-permissions.index')]
        ]);

        $role = DB::table('roles')->where('id', $id)->first();
        if (!$role) {
            abort(404, 'Role not found');
        }

        // Get current permissions for this role
        $rolePermissions = DB::table('role_has_permissions')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_has_permissions.role_id', $id)
            ->pluck('permissions.name')
            ->toArray();
        $permissions = DB::table('permissions')->get();

        return view('admin.pages.role-permission.form', compact('permissions', 'role', 'rolePermissions'));
    }

    /**
     * Store or update a role (combined method).
     */
    public function store(Request $request): RedirectResponse
    {
         $this->permission->checkAuthResponse($this->permission->canCreateRole());

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $request->role_id,
            'code' => 'required|string|max:255|unique:roles,code,' . $request->role_id,
            'deletable' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
            'role_id' => 'nullable|exists:roles,id'
        ]);

        try {
            DB::beginTransaction();

            // Prepare role data
            $roleData = [
                'name' => $validated['name'],
                'code' => $validated['code'],
                'guard_name' => 'admin',
                'deletable' => $request->has('deletable') ? 1 : 0,
                'updated_at' => now()
            ];

            if ($request->filled('role_id')) {
                // Update existing role
                DB::table('roles')
                    ->where('id', $request->role_id)
                    ->update($roleData);

                $roleId = $request->role_id;
                $message = 'Role updated successfully!';
            } else {
                // Create new role
                $roleData['created_at'] = now();
                $roleId = DB::table('roles')->insertGetId($roleData);
                $message = 'Role created successfully!';
            }

            // Sync permissions
            DB::table('role_has_permissions')
                ->where('role_id', $roleId)
                ->delete();

            if (!empty($validated['permissions'])) {
                $permissionIds = DB::table('permissions')
                    ->whereIn('name', $validated['permissions'])
                    ->pluck('id')
                    ->toArray();

                $permissionData = [];
                foreach ($permissionIds as $permissionId) {
                    $permissionData[] = [
                        'role_id' => $roleId,
                        'permission_id' => $permissionId
                    ];
                }

                if (!empty($permissionData)) {
                    DB::table('role_has_permissions')->insert($permissionData);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.role-permissions.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to save role: ' . $e->getMessage());
        }
    }
}
