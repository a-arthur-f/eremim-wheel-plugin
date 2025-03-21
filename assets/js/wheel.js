const wheelContainer = document.querySelector('.erw-wheel');
const wheel = document.querySelector('.erw-wheel__wheel');
const wheelImg = document.querySelector('.erw-wheel__wheel-img');
const closeButton = document.querySelector('.erw-wheel__close');
const startMessage = document.querySelector('#erw-wheel-start-message');
const startButton = document.querySelector('#erw-wheel-start-button');
const endMessage = document.querySelector('#erw-wheel-end-message');
const endButton = document.querySelector('#erw-wheel-end-button');

const id = wheelContainer.dataset.id;
const minAmount = parseFloat(wheelContainer.dataset.minAmount);

if(!localStorage.getItem(id) && !sessionStorage.getItem(id)) {
  wheelContainer.style.display = 'block';
}

closeButton.addEventListener('click', () => {
  sessionStorage.setItem(id, 'true');
  wheelContainer.remove();
});

startButton.addEventListener('click', () => {
  startMessage.style.display = 'none';
  wheel.style.display = 'block';
});

endButton.addEventListener('click', () => {
  localStorage.setItem(id, 'true');
  location.reload();
});

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

wheelImg.addEventListener('click', () => {
  currentDeg = degs[Math.floor(Math.random() * 4)];
  wheelImg.style.transform = `rotate(${9000 + currentDeg}deg)`;
  wheelImg.style.animation = 'blur 8s';
  wheelImg.style.pointerEvents = 'none';
});

wheelImg.addEventListener('transitionend', async () => {
  const prizeId = Math.ceil(currentDeg / 90) - 1;
  wheel.style.animation = 'none';
  const res = await fetch(ajax_obj.ajax_url + '?action=erw_prize', {
    method: 'POST',
    body: JSON.stringify({
      prize: {
        type: prizes[prizeId].type,
        value: prizes[prizeId].value
      },
      minAmount
    })
  }); 

  if(!res.ok) {
    return console.log('Falha na requisição da roleta.');
  }

  localStorage.setItem(id, 'true');

  setTimeout(() => {
    wheel.remove();
    endMessage.style.display = 'flex';
  }, 3000)
});
