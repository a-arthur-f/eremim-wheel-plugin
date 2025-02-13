const wheelContainer = document.querySelector('.erw-wheel');
const wheel = document.querySelector('.erw-wheel__wheel');

const id = wheelContainer.dataset.id;
const zoneSize = 90;
let currentDeg = 0;

wheel.addEventListener('click', () => {
  currentDeg = Math.random() * 361;
  wheel.style.transform = `rotate(${5000 + currentDeg}deg)`;
  wheel.style.animation = 'blur 8s';
  wheel.style.pointerEvents = 'none';
});

wheel.addEventListener('transitionend', () => {
  wheel.style.animation = 'none';
});
