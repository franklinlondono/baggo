<x-admin::layouts>

    <x-slot:title>
        {{ __('citydelivery::app.city-coverage.title') }}
    </x-slot>

    <div class="content">
        <div class="page-action">
            {{-- Botón de Agregar (solo si el usuario tiene permiso) --}}
          
            <div class="flex items-center gap-x-2.5">
                 @if (bouncer()->hasPermission('citydelivery.create'))
                <a href="{{ route('admin.citydelivery.create') }}" >
                    <div class="primary-button">
                    {{ __('citydelivery::app.city-coverage.add-btn-title') }}
                    </div>
                </a>
            @endif
            </div>
        </div>
        <div class="page-content">
            {{-- Renderiza la DataGrid usando el componente Blade de Bagisto V2 --}}
         
        {{-- CORRECCIÓN AQUÍ: Pasar la clase como una cadena de texto --}}
            <x-admin::datagrid :src="route('admin.citydelivery.index')" />
    
        </div>
    </div>

</x-admin::layouts>