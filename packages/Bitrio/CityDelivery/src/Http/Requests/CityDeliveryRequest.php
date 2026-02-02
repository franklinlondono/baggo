<?php

namespace Bitrio\CityDelivery\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityDeliveryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Asegura que solo los usuarios con permiso puedan realizar esta acción
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // country_state_id: Debe ser un ID de estado válido y es requerido
            'country_state_id' => ['required', 'integer', 'exists:country_states,id'], 
            
            // municipal_code: Código del municipio, requerido y con longitud máxima
            'municipal_code'   => ['nullable', 'string', 'max:255'], 
            
            // delivery_cost: Debe ser numérico (permite decimales) y es requerido
            'delivery_cost'    => ['required', 'numeric', 'min:0'], 
            
            // is_active: Puede ser un booleano (0 o 1). Se hace nullable para el switch de Blade.
            'is_active'        => ['nullable', 'boolean'], 
        ];
    }
    
    /**
     * Prepare the data for validation.
     * * @return void
     */
    protected function prepareForValidation(): void
    {
        // El switch de Bagisto solo envía el valor si está activo. Si está ausente, lo establecemos a 0.
        $this->merge([
            'is_active' => $this->input('is_active') ? 1 : 0,
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'country_state_id.exists' => 'El Estado/Departamento seleccionado no es válido.',
            // Puedes agregar mensajes personalizados para otras reglas si lo deseas.
        ];
    }
}