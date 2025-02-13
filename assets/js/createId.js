const createIdButton = document.querySelector('#gen-id');

createIdButton && createIdButton.addEventListener('click', () => {
  const idInput = document.querySelector('input[name=erw_id]');

  const now = Date.now();
  const idString = `erw-${now}`;

  const encoder = new TextEncoder();
  const id = encoder.encode(idString).toBase64();

  idInput.value = id;
});
