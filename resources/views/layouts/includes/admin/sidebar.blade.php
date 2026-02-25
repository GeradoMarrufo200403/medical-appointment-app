@php
    //Arreglo de iconos
    $links = [
        [
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge',
            'href' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],
        [
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge',
            'href' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],
        
        
    ];
@endphp    
       
       <aside id="top-bar-sidebar" class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-neutral-primary-soft border-e border-default">
            <a href="https://flowbite.com/" class="flex items-center ps-2.5 mb-5">
                <img src="https://flowbite.com/docs/images/logo.svg" class="h-6 me-3" alt="Flowbite Logo" />
                <span class="self-center text-lg text-heading font-semibold whitespace-nowrap">Flowbite</span>
            </a>
            <ul class="space-y-2 font-medium">
                @foreach ($links as $link)
                <li>
                    <a href="{{ $link['href'] }}" 
                        class="flex items-center px-2 py-1.5 text-body rounded-base hover:bg-gray-100 hover:text-fg-brand group {{ $link['active'] ? 'bg-gray-100' : '' }}">
                        <span class="w-6 h-6 inline-flex justify-center items-center text-gray-500">
                            <i class="{{ $link['icon'] }}"></i>
                        </span>  
                        
                        <span class="ms-3">{{ $link['name'] }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        </aside>