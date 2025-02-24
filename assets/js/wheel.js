const wheelContainer = document.querySelector('.erw-wheel');
const wheel = document.querySelector('.erw-wheel__wheel');
const closeButton = document.querySelector('.erw-wheel__close');

closeButton.addEventListener('click', () => {
  wheelContainer.remove()
});

const id = wheelContainer.dataset.id;
const zoneSize = 90;
let currentDeg = 0;
const degs = [45, 135, 225, 315];

const prizes = [
  {
    type: wheelContainer.dataset['prize-1'].split('-')[0],
    value: wheelContainer.dataset['prize-1'].split('-')[1]
  },
  {
    type: wheelContainer.dataset['prize-2'].split('-')[0],
    value: wheelContainer.dataset['prize-2'].split('-')[1]
  },
  {
    type: wheelContainer.dataset['prize-3'].split('-')[0],
    value: wheelContainer.dataset['prize-3'].split('-')[1]
  },
  {
    type: wheelContainer.dataset['prize-4'].split('-')[0],
    value: wheelContainer.dataset['prize-4'].split('-')[1]
  }
]

wheel.addEventListener('click', () => {
  currentDeg = degs[Math.floor(Math.random() * 4)];
  wheel.style.transform = `rotate(${9000 + currentDeg}deg)`;
  wheel.style.animation = 'blur 8s';
  wheel.style.pointerEvents = 'none';
});

wheel.addEventListener('transitionend', async () => {
  const prizeId = Math.ceil(currentDeg / 90) - 1;
  wheel.style.animation = 'none';
  const res = await fetch(ajax_obj.ajax_url + '?action=erw_prize', {
    method: 'POST',
    body: JSON.stringify({
      prize: {
        type: prizes[prizeId].type,
        value: prizes[prizeId].value
      }
    })
  }); 

  if(!res.ok) {
    console.log('Falha na requisição da roleta.');
  }
});
