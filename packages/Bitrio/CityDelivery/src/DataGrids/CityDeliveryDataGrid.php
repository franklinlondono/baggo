<?php

namespace Bitrio\CityDelivery\DataGrids;

use Webkul\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class CityDeliveryDataGrid extends DataGrid
{
    protected $index = 'id';
    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        // Alias 'cd' para City Delivery
        return DB::table('city_deliveries') 
            ->select(
                'id', 
                'name', 
                'municipal_code',
                'delivery_cost',
                'is_active'
            );

        // $this->setQueryBuilder($query);
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('citydelivery::app.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('citydelivery::app.datagrid.city-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
        
        $this->addColumn([
            'index'      => 'municipal_code',
            'label'      => trans('citydelivery::app.datagrid.municipal_code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'delivery_cost',
            'label'      => trans('citydelivery::app.datagrid.cost'),
            'type'       => 'decimal', // Muestra formato de moneda
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'is_active',
            'label'      => trans('citydelivery::app.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            // 'closure'    => true,
            'closure' => function ($row) {
                if ($row->is_active == 1) {
                    return '<span class="label-active">'.trans('citydelivery::app.datagrid.active').'</span>';
                } else {
                    return '<span class="label-canceled">'.trans('citydelivery::app.datagrid.inactive').'</span>';
                }
            }
        ]);
    }

    public function prepareActions()
    {
        // 1. Acción de Edición
        $this->addAction([
            'title' => trans('ui::app.datagrid.edit'),
            'method' => 'GET', 
            'url' => function ($row) { // Usamos 'url' con un closure
                return route('admin.citydelivery.edit', $row->id);
            },
            'icon' => 'icon-edit', // Icono de edición
            'acl'   => 'citydelivery.edit' // Requiere permiso de edición
        ]);
        
        // // 2. Acción de Eliminación (Requiere el método DELETE o un POST simulado)
        // $this->addAction([
        //     'title' => trans('ui::app.datagrid.delete'),
        //     'method' => 'DELETE', // Bagisto soporta DELETE en DataGrids
        //     'url' => function ($row) { // Usamos 'url' con un closure
        //         return route('admin.citydelivery.destroy', $row->id);
        //     },
        //     'icon' => 'icon-delete', // Icono de eliminación
        //     'acl'   => 'citydelivery.delete', // Requiere permiso de eliminación
        //     'confirm_text' => trans('citydelivery::app.datagrid.confirm-delete-message'),
        //     'confirm_button_text' => trans('ui::app.datagrid.delete'), // Reutiliza el botón de UI
        // ]);
        
        // Si quisieras una acción masiva para activar/desactivar, usarías prepareMassActions()
    }
}