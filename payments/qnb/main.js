function start_payment(res) {
    sessionStorage.clear()
    Checkout.configure({
        session: {
            id: res.session_id
        }
    })

    Checkout.showPaymentPage();
}