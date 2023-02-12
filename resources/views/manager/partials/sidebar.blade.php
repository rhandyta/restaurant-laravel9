<div class="sidebar-menu">



    <ul class="menu">

        @forelse (request()->attributes->get('menus') as $labelMenu)
            <li class="sidebar-title">{{ $labelMenu->label_title }}</li>
            @forelse ($labelMenu->menus as $menu)
                @if ($menu->submenus->count() < 1)
                    <li class="sidebar-item {{ request()->path() == substr($menu->path, 1) ? 'active' : null }}">
                        <a href="{{ $menu->path }}" class='sidebar-link'>
                            <i class="bi {{ $menu->icon }}"></i>
                            <span>{{ $menu->label_menu }}</span>
                        </a>
                    </li>
                @endif
                @if ($menu->submenus->count() > 0)
                    <li class="sidebar-item has-sub {{ request()->path() == substr($menu->path, 1) ? 'active' : null }}">
                        <a href="{{ $menu->path }}" class='sidebar-link'>
                            <i class="bi bi-{{ $menu->icon }}"></i>
                            <span>{{ $menu->label_menu }}</span>
                        </a>
                        <ul class="submenu">
                            @forelse ($menu->submenus as $submenu)
                                <li
                                    class="submenu-item {{ request()->path() == substr($submenu->path, 1) ? 'active' : null }}">
                                    <a href="{{ $menu->path . $submenu->path }}">{{ $submenu->label_submenu }}</a>
                                </li>
                            @empty
                                {{-- submenus --}}
                            @endforelse
                        </ul>
                    </li>
                @endif

            @empty
                {{-- menus --}}
            @endforelse
        @empty
            {{-- labelmenus --}}
        @endforelse

        {{-- <li class="sidebar-title">Forms &amp; Tables</li>
      
      <li
          class="sidebar-item  ">
          <a href="form-layout.html" class='sidebar-link'>
              <i class="bi bi-file-earmark-medical-fill"></i>
              <span>Form Layout</span>
          </a>
      </li>
      
      <li
          class="sidebar-item  has-sub">
          <a href="#" class='sidebar-link'>
              <i class="bi bi-journal-check"></i>
              <span>Form Validation</span>
          </a>
          <ul class="submenu ">
              <li class="submenu-item ">
                  <a href="form-validation-parsley.html">Parsley</a>
              </li>
          </ul>
      </li> --}}


    </ul>
</div>
</div>
