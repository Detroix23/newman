function randint(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

document.addEventListener("DOMContentLoaded", function() {
    const container = document.querySelector("body");
    const fondEtoile = document.querySelector("#ctnr-ss");
	const planetSelect = document.getElementById("planetSelect");

    // Variables pour le zoom et le déplacement
    let scale = 1;
    let isDragging = false;
    let startX, startY;
    let scrollLeftStart, scrollTopStart;
    let selectedPlanet = null;
    let isPanning = false;
    let panStartX, panStartY;
    let panOffsetX = 0, panOffsetY = 0;

    function randomSS() {
        const nbPlanet = window.nbPlanet || 3;
        const planets = [
            "planet0.png", "planet1.png", "planet2.png", "planet4.png",
			"planet5.png", "planet6.png", "planet7.png", "planet8.png", 
			"planet9.png", "planet10.png", "planet11.png", "planet12.png", 
			"planet13.png", "planet14.png",
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
        sun.style.animation = 'spin 15s linear infinite';

        var randomSun = randint(1, 3);
        if (randomSun === 1) {
            sun.style.background = "url('./img/solarSystem/sun0.png') no-repeat center center";
            sun.style.boxShadow = "0 0 3em rgb(255, 128, 0)";
        } else if (randomSun === 2) {
            sun.style.background = "url('./img/solarSystem/sun1.png') no-repeat center center";
            sun.style.boxShadow = "0 0 3em rgb(0, 0, 255)";
        } else {
            sun.style.background = "url('./img/solarSystem/sun2.png') no-repeat center center";
            sun.style.boxShadow = "0 0 3em rgb(255, 0, 0)";
        }
        sun.style.backgroundSize = "cover";
        sun.style.top = "30%"
        sun.style.left = "45%"

        sun.addEventListener('mouseover', function() {
            if (selectedPlanet !== sun) {
                if (selectedPlanet) {
                    selectedPlanet.style.boxShadow = "none";
                }
                sun.style.boxShadow = "0 0 1em rgba(0, 255, 255, 1)";
                selectedPlanet = sun;
            }
        });

        sun.addEventListener('click', function(event) {
            event.stopPropagation(); // Empêche la désélection
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && selectedPlanet) {
                window.location.href = aparencePlanet; // Remplace par le lien réel de la planète
            }
        })

        if (fondEtoile) {
            fondEtoile.appendChild(sun);
        }
        var Iorbit = 30;
        var Torbit = randint(15, 20);

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
            planet.style.background = "url('./img/solarSystem/" + aparencePlanet + "') no-repeat center center";
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
			
            // Ajouter un identifiant unique à chaque planète
            const planetId = 'planet-' + i;
            planet.id = planetId;

            // Stocker la planète dans le tableau
            planets.push({
                id: planetId,
                element: planet,
                name: "Planète " + (i + 1)
            });

            // Ajouter une option select
            const option = document.createElement('option');
            option.value = planetId;
            option.textContent = "Planète " + (i + 1);
            planetSelect.appendChild(option);

            planet.addEventListener('mouseover', function() {
                if (selectedPlanet !== planet) {
                    if (selectedPlanet) {
                        selectedPlanet.style.boxShadow = "none";
						if (randomSun === 1) {
							sun.style.boxShadow = "0 0 3em rgb(255, 128, 0)";
						} else if (randomSun === 2) {
		
							sun.style.boxShadow = "0 0 3em rgb(0, 0, 255)";
						} else {
							sun.style.boxShadow = "0 0 3em rgb(255, 0, 0)";
						}
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
                    window.location.href = "planet-" + i; // Remplace par le lien réel de la planète
                }
            })

            planetContainer.appendChild(planet);
            if (fondEtoile) {
                fondEtoile.appendChild(planetContainer);
            }
        }
    }

    randomSS();

    // Fonction pour le zoom avec la molette de la souris
    function handleWheel(event) {
        event.preventDefault();
        const delta = event.deltaY || event.detail;
        const rect = fondEtoile.getBoundingClientRect();
        const mouseX = event.clientX - rect.left;
        const mouseY = event.clientY - rect.top;

        // Calculer le facteur de zoom
        const zoomFactor = delta > 0 ? 0.9 : 1.1;
        const newScale = scale * zoomFactor;

        // Limiter le zoom
        if (newScale < 0.1 || newScale > 5) return;

        // Calculer la position relative de la souris dans le conteneur
        const relativeX = (mouseX - panOffsetX) / scale;
        const relativeY = (mouseY - panOffsetY) / scale;

        // Calculer la nouvelle position pour centrer le zoom sur la souris
        panOffsetX = mouseX - relativeX * newScale;
        panOffsetY = mouseY - relativeY * newScale;

        // Appliquer le zoom et la nouvelle position
        scale = newScale;
        fondEtoile.style.transform = `scale(${scale}) translate(${panOffsetX}px, ${panOffsetY}px)`;
    }

    // Fonction pour le déplacement par glisser-déposer
    function handleMouseDown(event) {
        if (event.button !== 0) return; // Seulement le bouton gauche de la souris
        isPanning = true;
        panStartX = event.clientX;
        panStartY = event.clientY;
        fondEtoile.style.cursor = 'grabbing';
        event.preventDefault(); // Empêcher la sélection de texte
    }

    function handleMouseMove(event) {
        if (!isPanning) return;
        const dx = event.clientX - panStartX;
        const dy = event.clientY - panStartY;

        panOffsetX += dx;
        panOffsetY += dy;

        fondEtoile.style.transform = `scale(${scale}) translate(${panOffsetX}px, ${panOffsetY}px)`;

        panStartX = event.clientX;
        panStartY = event.clientY;
    }

    function handleMouseUp() {
        isPanning = false;
        fondEtoile.style.cursor = 'grab';
    }

    // Fonction pour vérifier si toutes les planètes sont visibles
    function areAllPlanetsVisible() {
        const planetContainers = document.querySelectorAll('.planet-container');
        const fondEtoileRect = fondEtoile.getBoundingClientRect();

        for (let i = 0; i < planetContainers.length; i++) {
            const planetRect = planetContainers[i].getBoundingClientRect();
            if (
                planetRect.right < fondEtoileRect.left ||
                planetRect.left > fondEtoileRect.right ||
                planetRect.bottom < fondEtoileRect.top ||
                planetRect.top > fondEtoileRect.bottom
            ) {
                return false;
            }
        }
        return true;
    }

    // Ajouter les écouteurs d'événements
    fondEtoile.addEventListener('wheel', handleWheel, { passive: false });
    fondEtoile.addEventListener('mousedown', handleMouseDown);
    document.addEventListener('mousemove', handleMouseMove);
    document.addEventListener('mouseup', handleMouseUp);
    document.addEventListener('mouseleave', handleMouseUp);

    // Empêcher le comportement par défaut du glisser-déposer
    fondEtoile.addEventListener('dragstart', function(event) {
        event.preventDefault();
    });

    // Ajouter un curseur "grab" pour indiquer que l'élément est déplaçable
    fondEtoile.style.cursor = 'grab';
    // fondEtoile.style.overflow = 'hidden';
    fondEtoile.style.transformOrigin = '0 0';

    // Vérifier si toutes les planètes sont visibles et activer/désactiver le déplacement
    setInterval(function() {
        if (!areAllPlanetsVisible()) {
            fondEtoile.style.cursor = 'grab';
        } else {
            fondEtoile.style.cursor = 'default';
        }
    }, 100);
});
