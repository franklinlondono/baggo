<x-shop::layouts>

    <x-slot:title>
        Pago con Wompi
    </x-slot>

    <div class="main">
        <h1 class="text-lg font-bold mb-4">Finalizar compra con Wompi</h1>

        <p>Total a pagar: 
            <strong>
                ${{ number_format($cart->grand_total, 0, ',', '.') }}
            </strong>
        </p>

        <button 
            id="wompi-button"
            class="btn btn-primary mt-4">
            Pagar ahora con Wompi
        </button>
    </div>

    <!-- Script del Widget de Wompi -->
    <script src="https://checkout.wompi.co/widget.js"></script>
    <script>
        document.getElementById('wompi-button').addEventListener('click', function () {
            var checkout = new WidgetCheckout({
                currency: '{{ $cart->base_currency_code }}', // Moneda del pedido
                amountInCents: {{ intval($cart->grand_total * 100) }}, // Total en centavos
                reference: '{{ $cart->id }}', // ID único del pedido
                publicKey: '{{ $publicKey }}', // Llave pública de Wompi
                redirectUrl: '{{ route('wompi.callback') }}', // callback en tu app
            });

            checkout.open(function (result) {
                debugger;
                var transaction = result.transaction;

                if (transaction) {
                    console.log('Estado:', transaction.status);
                    console.log('ID:', transaction.id);

                    // Notificar al backend la transacción
                    fetch("{{ route('wompi.callback') }}?id=" + transaction.id)
                        .then(res => res.json())
                        .then(data => console.log("Respuesta backend:", data))
                        .catch(err => console.error("Error:", err));
                }
            });
        });
    </script>
</x-shop::layouts>
