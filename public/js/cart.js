// Cart lives in localStorage as {cupcakeId: qty}; server recomputes prices on checkout.
const Cart = {
  load() { try { return JSON.parse(localStorage.getItem('cart')) || {}; } catch (e) { return {}; } },
  save(cart) { localStorage.setItem('cart', JSON.stringify(cart)); Cart.updateCount(); },
  updateCount() {
    const n = Object.values(Cart.load()).reduce((a, b) => a + b, 0);
    $('#cart-count').text(n);
  },
};

const money = (v) => 'R$ ' + v.toFixed(2).replace('.', ',');
const esc = (s) => $('<div>').text(s).html();

$(Cart.updateCount);
