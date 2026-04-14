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
                        {{ isset($admin) ? 'Edit Admin' : 'Create New Admin' }}
                    </h3>

                    <a href="{{ route('admin.hrm.admins.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Back to Admins</span>
                    </a>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-800">
                    <form action="{{ route('admin.hrm.admins.store') }}" method="POST" class="p-5 sm:p-6" enctype="multipart/form-data">
                        @csrf

                        @if (isset($admin))
                            <input type="hidden" name="target" value="{{ $admin->id }}">
                        @endif

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- First Name -->
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" required />
                                <x-text-input
                                    type="text"
                                    name="first_name"
                                    id="first_name"
                                    :value="old('first_name', $admin->first_name ?? '')"
                                    placeholder="Enter first name" />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>

                            <!-- Last Name -->
                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" required />
                                <x-text-input
                                    type="text"
                                    name="last_name"
                                    id="last_name"
                                    :value="old('last_name', $admin->last_name ?? '')"
                                    placeholder="Enter last name" />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" required />
                                <x-text-input
                                    type="email"
                                    name="email"
                                    id="email"
                                    :value="old('email', $admin->email ?? '')"
                                    placeholder="info@example.com" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-input-label for="phone" :value="__('Phone Number')" />
                                <x-text-input
                                    type="tel"
                                    name="phone"
                                    id="phone"
                                    :value="old('phone', $admin->phone ?? '')"
                                    placeholder="+880 1234 567890" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- Role Dropdown -->
                            <div>
                                <x-input-label for="role" :value="__('Role')" required />
                                <x-select-input
                                    name="role"
                                    id="role"
                                    :options="$roles"
                                    :selected="$currentRoleId ?? ''"
                                    placeholder="Select Role"
                                />
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div>
                                <x-input-label for="password" :value="__('Password')" :required="!isset($admin)" />
                                <x-password-input
                                    name="password"
                                    id="password"
                                    placeholder="{{ isset($admin) ? 'Leave blank to keep current password' : 'Enter password' }}" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-password-input
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    placeholder="Confirm password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <!-- Profile Image -->
                            <div>
                                <x-input-label for="image" :value="__('Profile Image')" />
                                <input
                                    type="file"
                                    name="image"
                                    class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400"
                                />
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                @if(isset($admin) && $admin->image)
                                    <div class="mt-2">
                                        <img src="{{  Storage::url(filesPath('admins') . '/' . $admin->image) }}" alt="Current Image" class="w-16 h-16 rounded-lg object-cover">
                                        <p class="text-xs text-gray-500 mt-1">Current image</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-100 dark:border-gray-800">
                            <x-cancle-action
                                :cancelRoute="route('admin.hrm.admins.index')"
                            />
                           <x-submit-button
                                :text="isset($admin) ? 'Update Admin' : 'Create Admin'"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
