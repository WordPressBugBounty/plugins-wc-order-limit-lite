document.addEventListener('DOMContentLoaded', () => {
    const hideCheckoutButton = () => {
        const errorNotice = document.querySelector('.wc-block-store-notice.is-error');
        const proceedToCheckoutButton = document.querySelector('.wp-block-woocommerce-proceed-to-checkout-block');

        if (proceedToCheckoutButton) {
            if (errorNotice) {
                proceedToCheckoutButton.style.display = 'none'; // Hide the button if an error is present
            } else {
                proceedToCheckoutButton.style.display = ''; // Show the button if no error
            }
        }
    };

    const observeCartChanges = () => {
        // Observe cart updates in the WooCommerce data store
        if (window.wp && window.wp.data) {
            const unsubscribe = window.wp.data.subscribe(() => {
                const cartStore = window.wp.data.select('wc/store/cart');
                if (cartStore && cartStore.getCartData) {
                    hideCheckoutButton();
                }
            });
        }

        // Fallback: Use MutationObserver for DOM updates
        const cartContainer = document.querySelector('.wc-block-cart');
        if (cartContainer) {
            const observer = new MutationObserver(() => {
                hideCheckoutButton();
            });
            observer.observe(cartContainer, { childList: true, subtree: true });
        }
    };

    // Run the logic once on load
    hideCheckoutButton();

    // Observe changes in the cart
    observeCartChanges();
});












