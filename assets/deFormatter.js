export { deFormatter };

let deFormatter = new Intl.NumberFormat('de-DE', {
    style: 'currency',
    currency: 'EUR',
});
