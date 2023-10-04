// Gere várias gotas de chuva dinamicamente
const sky = document.querySelector('.sky');
const numRaindrops = 100;

for (let i = 0; i < numRaindrops; i++) {
    createRaindrop();
}

function createRaindrop() {
    const raindrop = document.createElement('div');
    raindrop.classList.add('rain');

    const randomX = Math.random() * 100;
    const randomDelay = Math.random() * 5 + 1;

    raindrop.style.left = `${randomX}%`;
    raindrop.style.animationDuration = `${randomDelay}s, 1s`;
    
    sky.appendChild(raindrop);

    raindrop.addEventListener('animationiteration', () => {
        raindrop.style.left = `${Math.random() * 100}%`;
    });

    setTimeout(() => {
        raindrop.remove();
        createRaindrop();
    }, randomDelay * 1000);
}