const createIdButton = document.querySelector('#gen-id');

createIdButton && createIdButton.addEventListener('click', () => {
  const idInput = document.querySelector('input[name=erw_id]');

  const now = Date.now();
  const idString = `erw-${now}`;

  const id = btoa(idString);

  idInput.value = id;
});
