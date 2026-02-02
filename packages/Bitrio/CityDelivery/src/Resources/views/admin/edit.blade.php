@php
    $defaultCountryCode = 'CO'; // Asumimos 'CO'
    $states = core()->states($defaultCountryCode); 
@endphp

<x-admin::layouts>
    <x-slot:title>
        {{ __('citydelivery::app.city-coverage.edit-title') }}
    </x-slot>

    {{-- Formulario de EDICIÓN (Apuntando a update con el ID y método PUT) --}}
    <x-admin::form 
        :action="route('admin.citydelivery.update', $cityDelivery->id)" 
        method="PUT"
    >

        <div class="flex items-center justify-between">
            <p class="text-[20px] font-bold text-gray-600 dark:text-white">
                {{ __('citydelivery::app.city-coverage.edit-title') }}
            </p>

            <div class="flex items-center gap-x-2.5">
                <a href="{{ route('admin.citydelivery.index') }}" class="transparent-button hover:bg-gray-200">
                    {{ __('citydelivery::app.city-coverage.back-btn') }}
                </a>
                <button type="submit" class="primary-button">
                    {{ __('citydelivery::app.city-coverage.save-btn') }}
                </button>
            </div>
        </div>

        <div class="mt-30 flex gap-5">
            <div class="flex flex-col gap-5 flex-1">
                <div class="p-4 bg-white rounded shadow dark:bg-gray-900">
                    
                    {{-- ESTADO/DEPARTAMENTO (Precargado con country_state_id) --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            {{ __('citydelivery::app.city-coverage.state') }}
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="country_state_id"
                            :value="old('country_state_id') ?? $cityDelivery->country_state_id" {{-- Precargar --}}
                            rules="required"
                            :label="__('citydelivery::app.city-coverage.state')"
                        >
                            <option value="">{{ __('citydelivery::app.city-coverage.select-state') }}</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}" 
                                    @selected($state->id == ($cityDelivery->country_state_id ?? old('country_state_id')))> {{-- Seleccionar el valor guardado --}}
                                    {{ $state->default_name }}
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="country_state_id" />
                    </x-admin::form.control-group>
                    
                    {{-- CAMPO OCULTO PARA EL NOMBRE --}}
                    <input type="hidden" name="name" id="city_name_hidden" value="{{ $cityDelivery->name }}">
                    
                    {{-- Código municipal --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            {{ __('citydelivery::app.datagrid.municipal_code') }}
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="municipal_code"
                            :value="old('municipal_code') ?? $cityDelivery->municipal_code" {{-- Precargar --}}
                            :label="__('citydelivery::app.datagrid.municipal_code')"
                            :placeholder="__('citydelivery::app.datagrid.municipal_code')"
                        />

                        <x-admin::form.control-group.error control-name="municipal_code" />
                    </x-admin::form.control-group>
                </div>
            </div>

            <div class="flex flex-col gap-5 w-[360px] max-w-full">
                <div class="p-4 bg-white rounded shadow dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-600 dark:text-white">
                        {{ __('citydelivery::app.city-coverage.general') }}
                    </p>

                    {{-- Costo de Envío --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            {{ __('citydelivery::app.datagrid.cost') }}
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="delivery_cost"
                            :value="old('delivery_cost') ?? $cityDelivery->delivery_cost" {{-- Precargar --}}
                            rules="required|decimal"
                            :label="__('citydelivery::app.datagrid.cost')"
                            :placeholder="__('citydelivery::app.datagrid.cost')"
                        />
                        <x-admin::form.control-group.error control-name="delivery_cost" />
                    </x-admin::form.control-group>

                    {{-- Activo/Inactivo (is_active) --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            {{ __('citydelivery::app.datagrid.status') }}
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="switch"
                            name="is_active"
                            :value="1"
                            :label="__('citydelivery::app.datagrid.status')"
                            :checked="old('is_active') ? true : ($cityDelivery->is_active == 1)"
                        />

                        <x-admin::form.control-group.error control-name="is_active" />
                    </x-admin::form.control-group>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>