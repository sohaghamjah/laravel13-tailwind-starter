@php
    use App\Helpers\BreadcrumbHelper;
    $breadcrumbs = BreadcrumbHelper::get();
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ !empty($breadcrumbs) ? end($breadcrumbs)->label : 'Dashboard' }}
    </h2>

    <nav>
        <ol class="flex items-center gap-1.5">
            <li>
                <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                   href="{{ route('admin.dashboard') }}">
                    Dashboard
                    <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none">
                        <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                              stroke="currentColor" stroke-width="1.2"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </li>

            @foreach($breadcrumbs as $crumb)
                <li class="text-sm {{ ($crumb->active ?? false) ? 'text-gray-800 dark:text-white/90 font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                    @if(isset($crumb->url) && $crumb->url && !($crumb->active ?? false))
                        <a href="{{ $crumb->url }}" class="hover:text-brand-500">
                            {{ $crumb->label }}
                        </a>
                    @else
                        {{ $crumb->label }}
                    @endif

                    @if(!$loop->last)
                        <svg class="inline stroke-current ml-1.5" width="17" height="16" viewBox="0 0 17 16" fill="none">
                            <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                                  stroke="currentColor" stroke-width="1.2"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
</div>
