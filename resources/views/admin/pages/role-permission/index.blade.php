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
                        Admin Management
                    </h3>

                    <a href="{{ route('admin.role-permissions.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Add New</span>
                    </a>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-800 mt-3"></div>
                    <div class="overflow-hidden  bg-white dark:bg-white/[0.03] mb-3">
                        <div class="max-w-full overflow-x-auto">
                            <table class="min-w-full" id="roleDatatable">
                                <!-- table header start -->
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    SL
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Role Name
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Role Code
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Users Count
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Deletable
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Created At
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Actions
                                                </p>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <!-- table header end -->
                                <!-- table body start -->
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @forelse($roles as $key => $role)
                                    <tr>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $key + $roles->firstItem() }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-800 text-theme-sm font-medium dark:text-white/90">
                                                    {{ $role->name }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    <code class="px-2 py-1 text-xs bg-gray-100 rounded dark:bg-gray-800">{{ $role->code }}</code>
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                                    {{ $role->users_count ?? 0 }} users
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                @if($role->deletable)
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500">
                                                        Yes
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500">
                                                        No
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $role->created_at ? date('d M, Y', strtotime($role->created_at)) : 'N/A' }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.role-permissions.edit', $role->id) }}"
                                                    class="inline-flex items-center justify-center p-2 text-gray-500 transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-400">
                                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                                </a>

                                                @if($role->deletable && $role->users_count == 0)
                                                <button type="button"
                                                    onclick="confirmDelete('{{ $role->id }}', '{{ $role->name }}')"
                                                    class="inline-flex items-center justify-center p-2 text-gray-500 transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-400">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-5 py-12 text-center sm:px-6">
                                            <div class="flex flex-col items-center justify-center">
                                                <i data-lucide="shield" class="w-12 h-12 text-gray-400 dark:text-gray-600"></i>
                                                <p class="mt-3 text-gray-500 dark:text-gray-400">No roles found</p>
                                                <a href="{{ route('admin.role-permissions.create') }}"
                                                    class="mt-3 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                                    <span>Create First Role</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($roles->hasPages())
                            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800 sm:px-6">
                                {{ $roles->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>
     $(document).ready(function () {
        $('#roleDatatable').DataTable({
            processing: true,
            responsive: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            columnDefs: [
                { orderable: false, targets: [6] } // Disable sorting for Image & Actions
            ]
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    function confirmDelete(id, name) {
        Swal.fire({
            title: '<span class="text-red-600">Delete Role?</span>',
            html: `
                <div class="text-left">
                    <p class="mb-2">You are about to delete role: <strong class="text-brand-600">${name}</strong></p>
                    <p class="text-sm text-gray-500">This action cannot be undone. All permissions associated with this role will be removed.</p>
                    ${name.toLowerCase().includes('admin') ? '<p class="mt-2 text-sm text-red-500">⚠️ Warning: This is an administrative role!</p>' : ''}
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete it!',
            cancelButtonText: '<i class="fas fa-times"></i> Cancel',
            reverseButtons: true,
            backdrop: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            preConfirm: async () => {
                try {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we delete the role.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('admin.role-permissions.index') }}/${id}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();

                    return true;
                } catch (error) {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleted!',
                    html: `Role <strong class="text-brand-600">${name}</strong> has been deleted successfully.`,
                    icon: 'success',
                    timer: 2500,
                    showConfirmButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Optional: Reload the page or redirect
                    window.location.reload();
                });
            }
        });
    }
</script>
@endpush
