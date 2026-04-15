@props([
'title' => config('app.name', 'Laravel'), // Titulo por defecto
'breadcrumbs' => [], // Array vacio por defecto
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{--Font Awesome CSS--}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/2cf911a520.js" crossorigin="anonymous"></script>

    <!--SweetAlert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{--WireUI--}}
    <wireui:scripts />

    <!-- Styles -->
</head>

<body class="font-sans antialiased bg-gray-50">


    @include('layouts.includes.admin.navigation')

    @include('layouts.includes.admin.sidebar')

    <div class="p-4 sm:ml-64 mt-14">
        <div class="mt-14 flex justify-between items-center w-full">
            @include('layouts.includes.admin.breadcrumb')
            @isset($actions)
            <div>
                {{ $actions }}
            </div>
            @endisset
        </div>
        {{ $slot }}
    </div>

    @stack('modals')

    {{--mostar sweetalert--}}
    @if (session('swal'))
    <script id="swal-data" type="application/json">
        {!! json_encode(session('swal')) !!}
    </script>
    <script>
        const swalData = document.getElementById('swal-data').textContent;
        Swal.fire(JSON.parse(swalData));
    </script>
    @endif

        @yield('content')
        <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
        <script>
            //Busca todos los elementos de una clase en especifico
            forms = document.querySelectorAll('.delete-form');
            forms.forEach(form => {
                //Revisa cualquier accion de envio
                form.addEventListener('submit', function(e) {
                    //Preeviene el envio del formulario
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, ¡bórralo!',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
</body>

</html>