$(function () {
  let toastTimer;

  $('.add-cart').on('click', function () {
    const cart = Cart.load(), id = $(this).data('id');
    cart[id] = (cart[id] || 0) + 1;
    Cart.save(cart);

    const name = $(this).closest('.card').find('h3').text();
    $('#toast').text(name + ' adicionado 🧁').addClass('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => $('#toast').removeClass('show'), 1800);
  });
});
