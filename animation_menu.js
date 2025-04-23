document.addEventListener("DOMContentLoaded", function() {
    const container = document.querySelector("body");
	function createStars() {
	  for (let i = 0; i < 1000; i++) {
		// Increase the number of stars to 1000
		const star = document.createElement("div");
		star.className = "star";
		star.style.width = ".2px";
		star.style.height = ".2px";
		star.style.top = Math.random() * 100 + "%";
		star.style.left = Math.random() * 100 + "%";
		container.appendChild(star);
	  }
	}
	createStars();
	
	
	
	
	// test
	
	
	
	
	
	const draggable = document.getElementById('draggable');

    // Variables pour suivre la position de la souris
    let offsetX, offsetY;
    let isDragging = false;

    // Fonction pour commencer le déplacement
    function dragStart(e) {
        isDragging = true;
        offsetX = e.clientX - draggable.getBoundingClientRect().left;
        offsetY = e.clientY - draggable.getBoundingClientRect().top;
        // Ajouter des écouteurs pour le déplacement et la fin du déplacement
        document.addEventListener('mousemove', dragMove);
        document.addEventListener('mouseup', dragEnd);
    }

    // Fonction pour déplacer l'élément
    function dragMove(e) {
        if (isDragging) {
            draggable.style.left = e.clientX - offsetX + 'px';
            draggable.style.top = e.clientY - offsetY + 'px';
        }
    }

    // Fonction pour arrêter le déplacement
    function dragEnd() {
        isDragging = false;
        // Supprimer les écouteurs pour le déplacement et la fin du déplacement
        document.removeEventListener('mousemove', dragMove);
        document.removeEventListener('mouseup', dragEnd);
    }

    // Ajouter un écouteur pour commencer le déplacement
    draggable.addEventListener('mousedown', dragStart);
});