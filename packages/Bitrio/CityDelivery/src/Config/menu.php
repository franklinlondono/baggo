<?php

return [
    [
        'key'        => 'sales', // Clave que identifica la sección existente de 'Ventas'
        'name'       => 'Ventas',
        'route'      => 'admin.sales.orders.index',
        'sort'       => 2,
        'icon' => 'shipping-icon',
    ], [
        'key'        => 'sales.carriers.city_coverages', // Nueva sub-clave: 'sales' (padre) y 'city_coverages' (hijo)
        'name'       => 'Zonas de Cobertura',
        'route'      => 'admin.citydelivery.index', // Ruta que definiste para tu grilla
        'sort'       => 5, // Asegura que aparezca debajo de Órdenes, Facturas, etc.
        'icon' => 'shipping-icon', // Puedes usar cualquier icono CSS de Bagisto (shipping, settings, etc.)
    ]
];