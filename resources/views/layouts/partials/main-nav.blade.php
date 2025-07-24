<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="#" class="logo-dark">
            <img src="/images/lrtj.png" class="champ-sm" style="width:50px" alt="logo sm">
        </a>

        <a href="#" class="logo-light">
            <img src="/images/lrtj-putih.png" class="champ-sm" style="width:50px" alt="logo sm">
        </a>
    </div>

    <!-- Menu Toggle Button (sm-hover) -->
    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone" class="button-sm-hover-icon"></iconify-icon>
    </button>

    <div class="scrollbar" data-simplebar>
    @php
        if (auth()->check()) {
            $roles = auth()->user()->role; // Role user yang login 
            $menus = \App\Models\General\Menu::where('is_active', 1)
                ->whereNull('parent_id')
                ->with('children')
                ->orderBy('order')
                ->get();

            // Filter menu berdasarkan role
            $filteredMenus = $menus->filter(function ($menu) use ($roles) {
                $roleIds = is_string($menu->role_id) ? json_decode($menu->role_id, true) : $menu->role_id;
                if (!is_array($roleIds)) return false;
                return in_array($roles, $roleIds);
            });
        } else {
            $filteredMenus = collect();
        }
    @endphp

    @if($filteredMenus->isNotEmpty())
    <ul class="navbar-nav" id="navbar-nav">
        @foreach($filteredMenus as $menu)
            @if($menu->children->isEmpty())
            <li class="nav-item active danger">
                <a class="nav-link" href="{{ $menu->url ? route($menu->url) : '#' }}">
                    <span class="nav-icon">
                        <iconify-icon icon="{{ $menu->icon }}"></iconify-icon>
                    </span>
                    <span class="nav-text">{{ $menu->title }}</span>
                </a>
            </li>
            @endif

            @if($menu->children->isNotEmpty())
            <li class="nav-item">
                <a class="nav-link menu-arrow danger" href="#sidebar{{ $menu->id }}" data-bs-toggle="collapse" role="button"
                aria-expanded="false" aria-controls="sidebar{{ $menu->id }}">
                    <span class="nav-icon">
                        <iconify-icon icon="{{ $menu->icon }}" width="24" height="24"></iconify-icon>
                    </span>
                    <span class="nav-text">{{ $menu->title }}</span>
                </a>
                <div class="collapse" id="sidebar{{ $menu->id }}">
                    <ul class="nav sub-navbar-nav">
                    @foreach($menu->children as $child)
                        @if($child->is_active === 1)
                            @php
                                // Jika role_id adalah array, tidak perlu decoding
                                $childRoleIds = is_array($child->role_id) ? $child->role_id : json_decode($child->role_id, true);
                            @endphp
                            @if(is_array($childRoleIds) && in_array($roles, $childRoleIds))
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="{{ route($child->url) }}">{{ $child->title }}</a>
                                </li>
                            @endif
                        @endif
                    @endforeach
                    </ul>
                </div>
            </li>
            @endif
        @endforeach
    </ul>
    @endif
</div>

</div>
