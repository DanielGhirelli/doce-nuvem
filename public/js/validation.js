// Mensagens de validação do navegador em português (o servidor valida de novo).
document.addEventListener('invalid', function (e) {
  const el = e.target, v = el.validity;
  const msg =
    v.valueMissing ? (el.type === 'radio' ? 'Escolha uma opção.' : 'Preencha este campo.') :
    v.tooShort ? `Use pelo menos ${el.minLength} caracteres (agora tem ${el.value.length}).` :
    v.patternMismatch ? (el.dataset.msg || 'Formato inválido.') :
    '';
  if (msg) el.setCustomValidity(msg);
}, true);

// Limpa as mensagens ao editar; no form inteiro para cobrir grupos de radio.
document.addEventListener('input', function (e) {
  $(e.target.form).find('input').each(function () { this.setCustomValidity(''); });
}, true);

$(function () {
  $('form.validate').on('submit', function (e) {
    const $p = $(this).find('[name=password2]');
    if ($p.length && $p.val() !== $(this).find('[name=password]').val()) {
      e.preventDefault();
      $p[0].setCustomValidity('As senhas não conferem.');
      $p[0].reportValidity();
    }
  });
});
