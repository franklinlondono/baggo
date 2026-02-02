<?php

return [
    [
        'key'    => 'city_coverages', // Clave del permiso principal (debe coincidir con la clave del menú si es de nivel superior)
        'name'   => 'Ciudades de Cobertura',
        'route'  => 'admin.citydelivery.index',
        'sort'   => 5,
        'icon'   => 'icon-shipping-icon',
    ], [
        'key'    => 'city_coverages.create', // Permiso para CREAR
        'name'   => 'Crear',
        'route'  => 'admin.citydelivery.create',
        'sort'   => 1,
        'parent' => 'city_coverages',
    ], [
        'key'    => 'city_coverages.edit', // Permiso para EDITAR
        'name'   => 'Editar',
        'route'  => 'admin.citydelivery.edit',
        'sort'   => 2,
        'parent' => 'city_coverages',
    ], [
        'key'    => 'city_coverages.delete', // Permiso para ELIMINAR
        'name'   => 'Eliminar',
        'route'  => 'admin.citydelivery.delete', // O 'admin.citydelivery.destroy'
        'sort'   => 3,
        'parent' => 'city_coverages',
    ]
];