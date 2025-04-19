
// Code that control the control panels, mainly their resizablility

var ui_game = document.querySelector("#ctnr-ui-game");
ui_game.addEventListener("click", cp_clicks, false);

//Vars

const fold_min_height = 40;
const fold_max_height = 350;
let foldH = fold_max_height;

function cp_clicks(e) {
	/**
	* Function: Main listener for clicks.
	*/
	// Check if correct
	console.log("GAME - Click registered");
	if (e.target !== e.currentTarget) {
		//Buttons
		if (e.target.classList.contains("btn-fold")) {
			console.log("GAME - Folder button pressed");
			
			///Fold/Unfold
			var ui_temp_fold = document.querySelector("#main-" + e.target.id);
			//// Get actual size
			let foldH = parseInt(window.getComputedStyle(ui_temp_fold).getPropertyValue("height"));
			//// If height == min, set to max, and vice versa
			foldH <= fold_min_height ? foldH = fold_max_height : foldH = fold_min_height;
			ui_temp_fold.style.height = foldH.toString() + "px";
			console.log("DEBUG - Fold height: " + ui_temp_fold.style.height);
			
		} else if (e.target.classList.contains("btn-incr")) {
			console.log("GAME - Building button pressed");
				
			
		} else {
			console.log("GAME - Unmapped button pressed");
		}
	}
	
	e.stopPropagation;
}