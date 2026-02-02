<?php

namespace Bitrio\CityDelivery\Http\Controllers\Admin;

use Bitrio\CityDelivery\DataGrids\CityDeliveryDataGrid;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Bitrio\CityDelivery\Http\Requests\CityDeliveryRequest; // Asumiendo que crearás un Request
use Bitrio\CityDelivery\Models\CityDelivery;
use Bitrio\CityDelivery\Repositories\CityDeliveryRepository; // Asumiendo que tienes un Repositorio
use Webkul\Core\Repositories\CountryStateRepository;

class CityDeliveryController extends Controller
{
    protected $cityDeliveryRepository;

    public function __construct(CityDeliveryRepository $cityDeliveryRepository)
    {
        $this->cityDeliveryRepository = $cityDeliveryRepository; 
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
        if (request()->ajax()) {
            return datagrid(CityDeliveryDataGrid::class)->process();
        }

        return view('citydelivery::admin.index');
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('citydelivery::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CityDeliveryRequest $request)
    {
        $data = $request->validated();
        $state = app(CountryStateRepository::class)->find($data['country_state_id']);
        if (! $state) {
            session()->flash('error', 'Error al crear la ciudad: ciudad no encontrada');
            return redirect()->route('admin.citydelivery.index');
        }
        $data['name'] = $state->default_name;
        $data['is_active'] = $data['is_active'] ?? 0;
        $data['municipal_code'] = $data['municipal_code'] ?? '0000';
        $exists = CityDelivery::query()
            ->where('country_state_id', $data['country_state_id'])
            ->exists();

        if ($exists) {
            session()->flash('error', 'Error al crear la ciudad: Ya existe una ciudad de cobertura con el mismo Estado/Departamento y Código de Municipio.');
            return redirect()->back()->withInput(); 
        }
        try {
            $this->cityDeliveryRepository->create($data);
            session()->flash('success', 'Ciudad de cobertura creada exitosamente.');
            return redirect()->route('admin.citydelivery.index');
           
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la ciudad: ' . $e->getMessage());
            return redirect()->route('admin.citydelivery.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $cityDelivery = $this->cityDeliveryRepository->findOrFail($id);
        return view('citydelivery::admin.edit', compact('cityDelivery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CityDeliveryRequest $request, int $id)
    {
        $data = $request->validated();
        
        $state = app(CountryStateRepository::class)->find($data['country_state_id']);

        if (! $state) {
            return new JsonResponse([
                'message' => 'Error: Estado/Departamento no encontrado.',
            ], 400);
        }

        $data['name'] = $state->default_name;
        $data['is_active'] = $data['is_active'] ?? 0;
        $data['municipal_code'] = $data['municipal_code'];

        try {
            // Usamos el repositorio para actualizar el registro por su ID
            $this->cityDeliveryRepository->update($data, $id);
            
            session()->flash('success', 'Ciudad de cobertura actualizada exitosamente.');
            return redirect()->route('admin.citydelivery.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar la ciudad: ' . $e->getMessage());
            return redirect()->route('admin.citydelivery.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        if (! bouncer()->hasPermission('citydelivery.delete')) {
            session()->flash('error', 'No tienes permisos para eliminar.');
            return redirect()->route('admin.citydelivery.index');
        }

        try {
            $this->cityDeliveryRepository->delete($id);
            session()->flash('success', 'Ciudad de cobertura eliminada exitosamente.');
            return redirect()->route('admin.citydelivery.index');
        } catch (\Exception $e) {
            
            session()->flash('error', 'Error al eliminar la ciudad: ' . $e->getMessage());
            return redirect()->route('admin.citydelivery.index');
        }
    }
}
