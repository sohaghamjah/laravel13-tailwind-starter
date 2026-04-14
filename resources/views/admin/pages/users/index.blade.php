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
                        User Management
                    </h3>

                    <x-action-button
                        icon="plus"
                        variant="primary"
                        label="Add New User"
                        href="{{ route('admin.hrm.users.create') }}"
                    />
                </div>

                <div class="border-t border-gray-100 dark:border-gray-800 mt-3"></div>
                <div class="overflow-hidden  bg-white dark:bg-white/[0.03] mb-3">
                    <div class="max-w-full overflow-x-auto">
                        <table class="min-w-full p-2" id="userTable">
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
                                @forelse($users as $key => $user)
                                    <tr>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $key + $users->firstItem() }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                @if ($user->image && Storage::disk('public')->exists(filesPath('users')))
                                                    <img src="{{ Storage::url(filesPath('users'). '/'. $user->image) }}"
                                                        alt="{{ $user->first_name }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <div
                                                        class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800">
                                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-800 text-theme-sm font-medium dark:text-white/90">
                                                    {{ $user->first_name }} {{ $user->last_name }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $user->email }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $user->phone ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <x-badge type="primary">{{ $user->role->name ?? 'No Role' }}</x-badge>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div x-data="{ switcherToggle: {{ $user->status ? 'true' : 'false' }} }">
                                                <label class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                                                    <div class="relative">
                                                        <input type="checkbox"
                                                            class="sr-only toggle-status-user"
                                                            data-id="{{ $user->id }}"
                                                            :checked="switcherToggle"
                                                            @change="switcherToggle = !switcherToggle; $dispatch('status-changed', { userId: {{ $user->id }}, status: !switcherToggle })" />
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
                                                    {{ $user->last_logged_in ? date('d M, Y h:i A', strtotime($user->last_logged_in)) : 'Never' }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{ $user->created_at ? date('d M, Y', strtotime($user->created_at)) : 'N/A' }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.hrm.users.edit', $user->id) }}"
                                                    class="inline-flex items-center justify-center p-2 text-gray-500 transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-400">
                                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                                </a>

                                                @if ($user->deletable && $user->id != auth()->id())
                                                    <button type="button"
                                                        onclick="confirmDelete('{{ $user->id }}', '{{ $user->first_name }} {{ $user->last_name }}')"
                                                        class="inline-flex items-center justify-center p-2 text-gray-500 transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-400">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-empty-state
                                    colspan="10"
                                    title="No data found"
                                    description="Get started by creating your first data"
                                    buttonLink="{{ route('admin.hrm.users.create') }}"
                                    buttonText="Create First" />
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($users->hasPages())
                        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800 sm:px-6">
                            {{ $users->links() }}
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
        var table = $('#userTable').DataTable({
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
        $(document).on('change', '.toggle-status-user', function() {
            var checkbox = $(this);
            var userId = checkbox.data('id');
            var newStatus = checkbox.prop('checked') ? 1 : 0;
            var originalStatus = !newStatus;
            console.log(userId);


            // Show loading state
            checkbox.prop('disabled', true);

            // Send AJAX request
            $.ajax({
                url: '/admin/hrm/users/update-status/' + userId,
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
