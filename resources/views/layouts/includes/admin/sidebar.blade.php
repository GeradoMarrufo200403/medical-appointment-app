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
'header' => 'Gestión',
],
[
'name' => 'Roles y permisos',
'icon' => 'fa-solid fa-shield-halved',
'href' => route('admin.roles.index'),
'active' => request()->routeIs('admin.roles.*'),
],
[
'name' => 'Usuarios',
'icon' => 'fa-solid fa-users',
'href' => route('admin.users.index'),
'active' => request()->routeIs('admin.users.*'),
],
[
'name' => 'Pacientes',
'icon' => 'fa-solid fa-users',
'href' => route('admin.patients.index'),
'active' => request()->routeIs('admin.patients.*'),
],
];
@endphp

<aside id="top-bar-sidebar" class="fixed top-0 left-0 z-40 w-64 h-full pt-20 transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-neutral-primary-soft border-e border-default">
        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
            <li>
                {{--Revisa si existe definido una llave llamada 'header'--}}
                @isset(($link['header']))
                <div class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase">
                    {{ $link['header'] }}
                </div>
                {{--Si no existe, usa la etiqueta como estaba definida--}}
                @else
                <a href="{{ $link['href'] }}" class="flex items-center w-full p-2 text-base text-gray-900"
                    class="flex items-center p-2  py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group {{ $link['active'] ? 'bg-neutral-secondary text-fg-brand' : 'text-gray-500' }}">
                    <span class="w-6 h-6 inline-flex items-center justify-center  text-gray-500">
                        <i class="{{ $link['icon'] }}"></i> </span>
                    <span class="ms-3">{{ $link['name'] }}</span>
                </a>
                @endisset
            </li>
            @endforeach
        </ul>
    </div>
</aside>