
function randint(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

document.addEventListener("DOMContentLoaded", function() {
    const container = document.querySelector("body");
    const fondEtoile = document.querySelector(".fond-etoile");

    function createStars() {
        for (let i = 0; i < 1000; i++) {
            const star = document.createElement("div");
            star.className = "star";
            star.style.width = ".2px";
            star.style.height = ".2px";
            star.style.backgroundColor = "white";
            star.style.position = "absolute";
            star.style.top = Math.random() * 100 + "%";
            star.style.left = Math.random() * 100 + "%";
            container.appendChild(star);
        }
    }

    function randomSS() {
        var nbPlanet = randint(3, 9);
        const planets = [
            "planet0.png", "planet1.png", "planet2.png", "planet3.png",
            "planet4.png", "planet5.png", "planet6.png", "planet7.png",
            "planet8.png", "planet9.png", "planet10.png", "planet11.png",
        ];

        // Créer et styliser le soleil
        const sun = document.createElement('div');
        sun.className = 'sun';
        sun.style.position = 'absolute';
        sun.style.top = '50%';
        sun.style.left = '50%';
        sun.style.transform = 'translate(-50%, -50%)';
        sun.style.width = '15em';
        sun.style.height = '15em';
        sun.style.borderRadius = '50%';

        var randomSun = randint(1, 3);
        if (randomSun === 1) {
            sun.style.background = "url('images/sun0.png') no-repeat center center";
            sun.style.boxShadow = "0 0 3em rgb(255, 128, 0)";
        } else if (randomSun === 2) {
            sun.style.background = "url('images/sun1.png') no-repeat center center";
            sun.style.boxShadow = "0 0 3em rgb(0, 0, 255)";
        } else {
            sun.style.background = "url('images/sun2.png') no-repeat center center";
            sun.style.boxShadow = "0 0 3em rgb(255, 0, 0)";
        }
        sun.style.backgroundSize = "cover";

        if (fondEtoile) {
            fondEtoile.appendChild(sun);
        }
		var Iorbit = 30;
		var Torbit = randint(15, 20);
		let selectedPlanet = null;
        for (let i = 0; i < nbPlanet; i++) {
            const planetContainer = document.createElement('div');
            planetContainer.className = 'planet-container';
            planetContainer.style.position = 'absolute';
            planetContainer.style.top = '50%';
            planetContainer.style.left = '50%';
            planetContainer.style.transform = 'translate(-50%, -50%)';

            var Porbit = randint(2, 5);
            planetContainer.style.width = Porbit + "em";
            planetContainer.style.height = Porbit + "em";

            const planet = document.createElement('img');
            planet.className = 'planet';
            planet.style.position = 'absolute';
            planet.style.top = '0';
            planet.style.left = '0';
            planet.style.width = '100%';
            planet.style.height = '100%';
            planet.style.borderRadius = '50%';

            var aparencePlanet = planets[randint(0, planets.length - 1)];
            planet.style.background = "url('images/" + aparencePlanet + "') no-repeat center center";
            planet.style.backgroundSize = "cover";

            var Vorbit = randint(12, 50);
            var initialAngle = (i * 360) / nbPlanet;

            planetContainer.style.setProperty('--orbit-distance', Torbit + 'em');
            planetContainer.style.setProperty('--orbit-duration', Vorbit + 's');
            planet.style.setProperty('--spin-duration', randint(5, 15) +'s');
            planetContainer.style.setProperty('--initial-angle', initialAngle + 'deg');
			Torbit = Torbit + 10;
			planetContainer.style.borderColor = "white transparent transparent transparent";
			planetContainer.style.zIndex = 1;
			
			planet.addEventListener('mouseover', function() {
                if (selectedPlanet !== planet) {
                    if (selectedPlanet) {
                        selectedPlanet.style.boxShadow = "none";
                    }
                    planet.style.boxShadow = "0 0 1em rgba(0, 255, 255, 1)";
                    selectedPlanet = planet;
                }
            });

            planet.addEventListener('click', function(event) {
                event.stopPropagation(); // Empêche la désélection
            });

			        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && selectedPlanet) {
                window.location.href = "#"; // Remplace par le lien réel de la planète
            }
        })
			
            planetContainer.appendChild(planet);
            if (fondEtoile) {
                fondEtoile.appendChild(planetContainer);
            }
        }
    }

    createStars();
    randomSS();


const draggable = document.getElementById('draggable');
    let offsetX, offsetY;
    let isDragging = false;

    function dragStart(e) {
        isDragging = true;
        offsetX = e.clientX - draggable.getBoundingClientRect().left;
        offsetY = e.clientY - draggable.getBoundingClientRect().top;
        document.addEventListener('mousemove', dragMove);
        document.addEventListener('mouseup', dragEnd);
    }

    function dragMove(e) {
        if (isDragging) {
            draggable.style.left = e.clientX - offsetX + 'px';
            draggable.style.top = e.clientY - offsetY + 'px';
        }
    }

    function dragEnd() {
        isDragging = false;
        document.removeEventListener('mousemove', dragMove);
        document.removeEventListener('mouseup', dragEnd);
    }

    if (draggable) {
        draggable.addEventListener('mousedown', dragStart);
    }
});