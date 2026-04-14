@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto max-w-(--breakpoint-2xl)">
        <!-- Breadcrumb Start -->
        <div>
            @include('admin.partials.breadcrumb')
        </div>
        <!-- Breadcrumb End -->

        <div class="space-y-3 sm:space-y-3">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between px-5 py-2 sm:px-3 sm:py-3 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ isset($role) ? 'Edit Role' : 'Create New Role' }}
                    </h3>

                    <a href="{{ route('admin.role-permissions.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Back to Roles</span>
                    </a>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-800">
                    <form action="{{ route('admin.role-permissions.store') }}" method="POST" class="p-5 sm:p-6">
                        @csrf

                        @if(isset($role))
                            <input type="hidden" name="role_id" value="{{ $role->id }}">
                        @endif

                        <!-- Role Basic Information -->
                        <div class="mb-8">
                            <h4 class="mb-4 text-sm font-semibold text-gray-700 uppercase dark:text-gray-300">
                                Role Information
                            </h4>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Role Name <span class="text-error-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $role->name ?? '') }}"
                                        placeholder="e.g., Super Admin, Content Manager, Editor"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('name') border-error-500 @enderror">
                                    @error('name')
                                        <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Role Code <span class="text-error-500">*</span>
                                    </label>
                                    <input type="text" name="code" id="code" value="{{ old('code', $role->code ?? '') }}"
                                        placeholder="e.g., super_admin, content_manager"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('code') border-error-500 @enderror">
                                    @error('code')
                                        <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                    @else
                                        <p class="mt-1 text-xs text-gray-400">Unique identifier used in code logic</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-5">
                                <label class="flex items-center cursor-pointer gap-3">
                                    <input type="checkbox" name="deletable" id="deletable" value="1"
                                        {{ old('deletable', isset($role) ? $role->deletable : '1') == '1' ? 'checked' : '' }}
                                        class="w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        Role can be deleted (if no users are assigned)
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Permissions Section - Grouped by Module -->
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                            <div class="flex items-center justify-between mb-5">
                                <h4 class="text-sm font-semibold text-gray-700 uppercase dark:text-gray-300">
                                    Permissions Assignment
                                </h4>
                                <button type="button" id="selectAllPermissions"
                                    class="text-sm text-brand-600 hover:text-brand-700 dark:text-brand-400">
                                    Select All
                                </button>
                            </div>

                            @php
                                $groupedPermissions = $permissions->groupBy('group_name');
                                // Get old permissions or role permissions for edit
                                $selectedPermissions = old('permissions', $rolePermissions ?? []);
                            @endphp

                            @if($groupedPermissions->count())
                                <div class="space-y-6">
                                    @foreach($groupedPermissions as $groupName => $permissionGroup)
                                        <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800">
                                            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-800">
                                                <div class="flex items-center justify-between">
                                                    <h5 class="text-sm font-semibold text-gray-800 dark:text-white/90 capitalize">
                                                        {{ $groupName ?: 'General' }}
                                                    </h5>
                                                    <button type="button"
                                                        class="select-group text-xs text-brand-600 hover:text-brand-700 dark:text-brand-400"
                                                        data-group="{{ $groupName }}">
                                                        Select All
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="p-5">
                                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                                    @foreach($permissionGroup as $permission)
                                                        <label class="flex items-center gap-3 cursor-pointer group">
                                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                                class="permission-checkbox w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700"
                                                                data-group-name="{{ $permission->group_name }}"
                                                                {{ in_array($permission->name, $selectedPermissions) ? 'checked' : '' }}>
                                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                                {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-12 text-center rounded-xl bg-gray-50 dark:bg-gray-900/30">
                                    <i data-lucide="shield" class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600"></i>
                                    <p class="mt-3 text-gray-500 dark:text-gray-400">No permissions available. Please run permission seeder first.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-100 dark:border-gray-800">
                            <a href="{{ route('admin.role-permissions.index') }}"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition bg-brand-500 rounded-lg shadow-theme-xs hover:bg-brand-600">
                                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                {{ isset($role) ? 'Update Role' : 'Create Role' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Select All functionality for all permissions
        const selectAllBtn = document.getElementById('selectAllPermissions');
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                const allCheckboxes = document.querySelectorAll('.permission-checkbox');
                const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                allCheckboxes.forEach(checkbox => {
                    checkbox.checked = !allChecked;
                });
                updateSelectAllButtonText();
            });
        }

        // Select All per group
        document.querySelectorAll('.select-group').forEach(btn => {
            btn.addEventListener('click', function() {
                const groupName = this.getAttribute('data-group');
                const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group-name="${groupName}"]`);
                const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
                groupCheckboxes.forEach(checkbox => {
                    checkbox.checked = !allChecked;
                });
                updateSelectAllButtonText();
            });
        });

        // Update "Select All" button text based on all checkboxes state
        function updateSelectAllButtonText() {
            const allCheckboxes = document.querySelectorAll('.permission-checkbox');
            const allChecked = allCheckboxes.length > 0 && Array.from(allCheckboxes).every(cb => cb.checked);
            const selectAllBtn = document.getElementById('selectAllPermissions');
            if (selectAllBtn) {
                selectAllBtn.textContent = allChecked ? 'Deselect All' : 'Select All';
            }
        }

        // Individual checkbox change updates the global select all button
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAllButtonText);
        });

        // Initial call to set button text
        updateSelectAllButtonText();

        // Auto-generate code from name on keyup (only for create form, not for edit)
        const nameInput = document.getElementById('name');
        const codeInput = document.getElementById('code');
        const roleId = document.querySelector('input[name="role_id"]');

        if (nameInput && codeInput && !roleId) {
            // Function to generate code from name
            function generateCodeFromName() {
                let nameValue = nameInput.value;
                if (nameValue) {
                    let generatedCode = nameValue
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '_')  // Replace special chars with underscore
                        .replace(/^_|_$/g, '')        // Remove leading/trailing underscores
                        .substring(0, 50);            // Limit to 50 characters

                    codeInput.value = generatedCode;
                } else {
                    codeInput.value = '';
                }
            }

            // Generate on keyup
            nameInput.addEventListener('keyup', function() {
                generateCodeFromName();
            });

            // Also generate on change (for paste events)
            nameInput.addEventListener('change', function() {
                generateCodeFromName();
            });

            // Generate initial code if name has value
            if (nameInput.value) {
                generateCodeFromName();
            }
        }
    });
</script>
@endpush
