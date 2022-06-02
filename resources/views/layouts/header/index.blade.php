<div class="app-header header-shadow {{Auth::user()->companies[0]->theme}}">
    @include('layouts.header.logo')
    <div class="app-header__content">
        <div class="app-header-right">
            @include('layouts.header.header-right')
        </div>
    </div>
</div>