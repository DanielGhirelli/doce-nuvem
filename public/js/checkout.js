$(function () {
  const $items = $('#cart-items');
  if (!$items.length) return;
  const cupcakes = $items.data('cupcakes');

  function render() {
    const cart = Cart.load();
    let total = 0, html = '';
    for (const id in cart) {
      const c = cupcakes[id];
      if (!c) { delete cart[id]; continue; }
      total += c.price * cart[id];
      html += `<div class="line" data-id="${id}">
        <img class="line-img" src="img/cupcakes/${esc(c.image)}" alt="" style="background:${esc(c.color)}">
        <div class="line-info"><b>${esc(c.name)}</b><span class="muted">${money(c.price)}</span></div>
        <div class="qty"><button type="button" data-d="-1">−</button><span>${cart[id]}</span><button type="button" data-d="1">+</button></div>
        <b class="line-total">${money(c.price * cart[id])}</b>
      </div>`;
    }
    $items.html('<h3>Itens</h3>' + (html || '<p class="muted empty">Carrinho vazio. <a href="index.php">Escolher cupcakes</a></p>'));
    $('#cart-total').text(money(total));
    $('#cart-input').val(JSON.stringify(cart));
  }
  render();

  $items.on('click', '.qty button', function () {
    const cart = Cart.load(), id = $(this).closest('.line').data('id');
    cart[id] += $(this).data('d');
    if (cart[id] <= 0) delete cart[id];
    Cart.save(cart);
    render();
  });

  $('#checkout-form').on('submit', function (e) {
    if (!Object.keys(Cart.load()).length) { e.preventDefault(); alert('Seu carrinho está vazio.'); }
  });
});
