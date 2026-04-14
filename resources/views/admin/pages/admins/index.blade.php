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

                    <x-action-button
                        icon="plus"
                        variant="primary"
                        label="Add New Admin"
                        href="{{ route('admin.hrm.admins.create') }}"
                    />
                </div>

                <div class="border-t border-gray-100 dark:border-gray-800 mt-3"></div>
                <div class="overflow-hidden  bg-white dark:bg-white/[0.03] mb-3">
                    <div class="max-w-full overflow-x-auto">
                        <table class="min-w-full p-2" id="adminTable">
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
                                                Image
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Name
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Email
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Phone
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Role
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Status
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Last Login
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
                                @forelse($admins as $key => $admin)
                                    <tr>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $key + $admins->firstItem() }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                @if ($admin->image && Storage::disk('public')->exists($admin->image))
                                                    <img src="{{ Storage::url($admin->image) }}"
                                                        alt="{{ $admin->first_name }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <div
                                                        class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800">
                                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                                            {{ strtoupper(substr($admin->first_name, 0, 1)) }}{{ strtoupper(substr($admin->last_name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-800 text-theme-sm font-medium dark:text-white/90">
                                                    {{ $admin->first_name }} {{ $admin->last_name }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $admin->email }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $admin->phone ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <x-badge type="primary">{{ $admin->role->name ?? 'No Role' }}</x-badge>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div x-data="{ switcherToggle: {{ $admin->status ? 'true' : 'false' }} }">
                                                <label class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                                                    <div class="relative">
                                                        <input type="checkbox"
                                                            class="sr-only toggle-status"
                                                            data-admin-id="{{ $admin->id }}"
                                                            :checked="switcherToggle"
                                                            @change="switcherToggle = !switcherToggle; $dispatch('status-changed', { adminId: {{ $admin->id }}, status: !switcherToggle })" />
                                                        <div class="block h-6 w-11 rounded-full"
                                                            :class="switcherToggle ? 'bg-brand-500 dark:bg-brand-500' : 'bg-gray-200 dark:bg-white/10'">
                                                        </div>
                                                        <div :class="switcherToggle ? 'translate-x-full' : 'translate-x-0'"
                                                            class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white duration-300 ease-linear">
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $admin->last_logged_in ? date('d M, Y h:i A', strtotime($admin->last_logged_in)) : 'Never' }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $admin->created_at ? date('d M, Y', strtotime($admin->created_at)) : 'N/A' }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.hrm.admins.edit', $admin->id) }}"
                                                    class="inline-flex items-center justify-center p-2 text-gray-500 transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-400">
                                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                                </a>

                                                @if ($admin->deletable && $admin->id != auth()->id())
                                                    <button type="button"
                                                        onclick="confirmDelete('{{ $admin->id }}', '{{ $admin->first_name }} {{ $admin->last_name }}')"
                                                        class="inline-flex items-center justify-center p-2 text-gray-500 transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-400">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="px-5 py-12 text-center sm:px-6">
                                            <div class="flex flex-col items-center justify-center">
                                                <i data-lucide="users"
                                                    class="w-12 h-12 text-gray-400 dark:text-gray-600"></i>
                                                <p class="mt-3 text-gray-500 dark:text-gray-400">No admins found</p>
                                                <a href="{{ route('admin.hrm.admins.create') }}"
                                                    class="mt-3 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                                    <span>Create First Admin</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($admins->hasPages())
                        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800 sm:px-6">
                            {{ $admins->links() }}
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
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#adminTable').DataTable({
            processing: true,
            responsive: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            columnDefs: [{
                    orderable: false,
                    targets: [1, 9]
                }
            ]
        });

        // Handle status toggle change
        $(document).on('change', '.toggle-status', function() {
            var checkbox = $(this);
            var adminId = checkbox.data('admin-id');
            var newStatus = checkbox.prop('checked') ? 1 : 0;
            var originalStatus = !newStatus;

            // Show loading state
            checkbox.prop('disabled', true);

            // Send AJAX request
            $.ajax({
                url: '/admin/hrm/admins/update-status/' + adminId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        showToast('success', response.message || 'Status updated successfully');
                    } else {
                        // Revert checkbox state on error
                        checkbox.prop('checked', originalStatus);
                        showToast('error', response.message || 'Failed to update status');
                    }
                },
                error: function(xhr) {
                    // Revert checkbox state on error
                    checkbox.prop('checked', originalStatus);

                    var errorMessage = 'An error occurred while updating status';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showToast('error', errorMessage);
                },
                complete: function() {
                    // Re-enable checkbox
                    checkbox.prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush
