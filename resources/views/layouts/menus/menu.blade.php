{{-- Dashboard --}}
<li class="pc-item {{ request()->routeIs('home') ? 'active' : '' }}">
    <a href="{{ route('home') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-dashboard"></i>
        </span>
        <span class="pc-mtext">{{ __('messages.dashboard') }}</span>
    </a>
</li>

@if(auth()->user()->isAdmin())
{{-- Medical Data Section (Admin Only) --}}
<li class="pc-item pc-caption">
    <label>{{ __('messages.medical_data') }}</label>
</li>

<li class="pc-item {{ request()->routeIs('diseases.*') ? 'active' : '' }}">
    <a href="{{ route('diseases.index') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-virus"></i>
        </span>
        <span class="pc-mtext">{{ __('messages.diseases') }}</span>
    </a>
</li>

<li class="pc-item {{ request()->routeIs('symptoms.*') ? 'active' : '' }}">
    <a href="{{ route('symptoms.index') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-stethoscope"></i>
        </span>
        <span class="pc-mtext">{{ __('messages.symptoms') }}</span>
    </a>
</li>

<li class="pc-item {{ request()->routeIs('regions.*') ? 'active' : '' }}">
    <a href="{{ route('regions.index') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-map-pin"></i>
        </span>
        <span class="pc-mtext">{{ __('messages.regions') }}</span>
    </a>
</li>
@endif

{{-- Cases Section (Available for all users) --}}
<li class="pc-item pc-caption">
    <label>{{ __('messages.cases') }}</label>
</li>

<li class="pc-item {{ request()->routeIs('cases.*') ? 'active' : '' }}">
    <a href="{{ route('cases.index') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-clipboard-list"></i>
        </span>
        <span class="pc-mtext">{{ __('messages.my_cases') }}</span>
    </a>
</li>

@if(auth()->user()->isAdmin())
{{-- User Management Section (Admin Only) --}}
<li class="pc-item pc-caption">
    <label>{{ __('messages.user_management') }}</label>
</li>

<li class="pc-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
    <a href="{{ route('users.index') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-users"></i>
        </span>
        <span class="pc-mtext">{{ __('messages.users') }}</span>
    </a>
</li>
@endif
