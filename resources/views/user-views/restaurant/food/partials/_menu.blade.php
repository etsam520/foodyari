<div class="row">
    <div class="accordion-item">

        @foreach ($menu as $item)
            @if ($item != null)
                <h2 class="accordion-header d-flex justify-content-between py-2 border-bottom menu_item"
                    data-menu-target="#menu_{{ $item->id }}" menuId="{{ $item->id }}">
                    <button class="accordion-button collapsed fw-bold fs-6" type="button" data-bs-toggle="collapse"
                        data-bs-target="#menu-collapse-{{ $item->id }}" aria-expanded="false"
                        aria-controls="collapseTwo">
                        <h5>{{ Str::ucfirst($item->name) }}</strong>
                    </button>
                    <div class="fs-6">
                        <h6>{{ $item->foods->count() }}</strong>
                    </div>
                </h2>
                <div id="menu-collapse-{{ $item->id }}" class="accordion-collapse collapse mb-3 ps-3">
                    <div class="accordion-body">
                        @foreach ($item->submenu as $submenu)
                            <div class="submenu_item" data-submenu-id="{{ $submenu->id }}">-
                                <span>{{ Str::ucfirst($submenu->name) }}</span></div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
