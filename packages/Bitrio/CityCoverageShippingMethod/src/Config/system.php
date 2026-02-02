<?php

return [
    [
        'key'    => 'sales.carriers.citycoverageshippingmethod',
        'name'   => 'Envío a Domicilio - Zonas de Cobertura',
        'info' => 'Este método proporciona una tarifa dinámica que corresponde al valor configurado para la ciudad de entrega seleccionada. Solo aplica a las ciudades con cobertura establecida.',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'Título del Método de Envío',
                'info'          => 'El nombre del método de envío que se mostrará al cliente (Ej: Envío Local Estándar).',
                'type'          => 'text',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true
            ], [
                'name'          => 'description',
                'title'         => 'Descripción Interna',
                'info'         => 'Información detallada sobre este método de envío para uso administrativo.',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false
            ], [
                'name'          => 'default_rate',
                'title'         => 'Tarifa por Defecto (Tarifa base)',
                'info'         => 'Una tarifa fija o predeterminada. **Nota:** Si la lógica de ciudades falla, se utilizará este valor.',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1|numeric',
                'channel_based' => true,
                'locale_based'  => false
            ], [
                'name'          => 'base_amount',
                'title'         => 'Monto Mínimo del Pedido',
                'info'         => 'El monto mínimo que debe tener el pedido para que este método de envío esté disponible.',
                'type'          => 'text',
                'channel_based' => true,
                'locale_based'  => false
            ], [
                'name'    => 'type',
                'title'   => 'Tipo de Cálculo',
                'info'   => 'Define cómo se aplica la tarifa: por unidad o por pedido.',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'Por Unidad',
                        'value' => 'per_unit',
                    ], [
                        'title' => 'Por Pedido',
                        'value' => 'per_order',
                    ],
                ],
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'Estado',
                'info'          => 'Define si este método de envío está activo y visible en la tienda.',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false
            ]
        ]
    ]
];

