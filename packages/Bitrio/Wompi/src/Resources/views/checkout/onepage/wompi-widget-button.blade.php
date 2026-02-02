@if (
    request()->routeIs('shop.checkout.onepage.index')
    && (bool) core()->getConfigData('sales.payment_methods.wompi.active')
)
    @php
        $publicKey = core()->getConfigData('sales.payment_methods.wompi.public_key');
        $cart = cart()->getCart();
        $redirectUrl = route('wompi.callback');
        // dd($redirectUrl);
    @endphp

    @pushOnce('scripts')
        <script type="text/x-template" id="v-wompi-button-template">
            <form>
                <div ref="wompiContainer"></div>
            </form>
        </script>

        <script type="module">
             if (!window.WompiButtonMounted) {
                app.component('v-wompi-button', {
                    template: '<div ref="wompiContainer"></div>',
                    data() {
                        return {
                            publicKey: "{{ $publicKey }}",
                            currency: null,
                            amountInCents: null,
                            reference: null,
                            signature: null,
                            redirectUrl: "{{ $redirectUrl}}",
                        };
                    },
                    mounted() {
                        this.renderWompi();
                        window.WompiButtonMounted = true;
                    },
                    methods: {
                        renderWompi() {
                            this.$axios.get("{{ route('wompi.create-order') }}")
                                .then(response => {
                                    const cart = response.data.cart;
                                    this.currency = cart.currency;
                                    this.amountInCents = cart.amount_in_cents;
                                    this.reference = cart.reference;
                                    this.signature = cart.signature;

                                    const script = document.createElement("script");
                                    script.src = "https://checkout.wompi.co/widget.js";
                                    script.setAttribute("data-render", "button");
                                    script.setAttribute("data-public-key", this.publicKey);
                                    script.setAttribute("data-currency", this.currency);
                                    script.setAttribute("data-amount-in-cents", this.amountInCents);
                                    script.setAttribute("data-reference", this.reference);
                                    script.setAttribute("data-redirect-url", this.redirectUrl);
                                    script.setAttribute("data-signature:integrity", this.signature);

                                    this.$refs.wompiContainer.innerHTML = "";
                                    this.$refs.wompiContainer.appendChild(script);
                                });
                        }
                    }
                });
            }
        </script>

    @endPushOnce


@endif
