
//Vars
/// Fold
const fold_min_height = 40;
const fold_max_height = 350;
let foldH = fold_max_height;
/// Build control
const buildButtons = [];

function cp_clicks(e) {
	/**
	* Function: Main listener for clicks.
	*/
	// Check if correct
	console.log("GAME - Click registered");
	if (e.target !== e.currentTarget) {
		//Buttons
		if (e.target.classList.contains("btn-fold")) {
			bfid = e.target.id;
			console.log("GAME - Folder button pressed; id="+bfid);
			
			///Fold/Unfold
			var ui_temp_fold = document.querySelector("#main-" + bfid);
			//// Get actual size
			let foldH = parseInt(window.getComputedStyle(ui_temp_fold).getPropertyValue("height"));
			//// If height == min, set to max, and vice versa
			foldH <= fold_min_height ? foldH = fold_max_height : foldH = fold_min_height;
			ui_temp_fold.style.height = foldH.toString() + "px";
			console.log("DEBUG - Fold height: " + ui_temp_fold.style.height);
			
		} else if (e.target.classList.contains("btn-incr")) {
			bbid = e.target.id;
			console.log("GAME - Building button pressed; id="+bbid);
			
			/// Count clicks, and min/max them. There will be still a check in php.
			if (buildButtons.includes(bbid)) {
				buildButtons.push(bbid);
			}
			
		} else {
			console.log("GAME - Unmapped button pressed");
		}
	}
	
	e.stopPropagation;
}

// Code that control the control panels, mainly their resizablility


var ui_game = document.querySelector("#ctnr-ui-game");
ui_game.addEventListener("click", cp_clicks, false);