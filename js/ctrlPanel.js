// Common.js import on uiTop
//Vars
/// Data
const elemNameNode = document.querySelector("#data-name");
const elemName = elemNameNode ? elemNameNode.value : "";

/// Main folders
const fold_min_height = 40;
const fold_max_height = 350;
let foldH = fold_max_height;
/// Build control
const buildButtons = [];
/// Input lists
const inp_class_whitelist = ['inp-numb1', 'inp-numbSub1'];
const inp_lsKey_black = ['_'];

class buttonBuild {
	constructor(id, maxClicks) {
		this.id = id;
		this.maxClicks = maxClicks;
		this.activated = (maxClicks > 0) ? true : false;
	}
	use(n=1) {
		for (i=1; i<=n; i++) {
			if (this.maxClicks > 1) {
				this.maxClicks -= 1;
			} else if (this.maxClicks == 1) {
				this.maxClicks -= 1;
				this.activated = false;
			} else {
				this.activated = false;
			}
		}
		return this.activated;
	}


}
/// Item folders
const itemButtons = [];

function clicks(e) {
	/**
	* Function: Main listener for clicks.
	*/
	// Check if correct
	// console.log("GAME - Click registered");
	if (e.target !== e.currentTarget) {
		const etarget = e.target;
		// Main folders button
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
		}
		// Plus/minus buttons
		else if (e.target.classList.contains("btn-incr")) {
			bbid = e.target.id;
			console.log("GAME - Building button pressed; id="+bbid);
			/// List of object
			if (!buildButtons.includes(bbid)) {
				buildButtons.push(bbid);
			}
			/// Count clicks, and min/max them. There will be still a check in php.

		}
		// Item detail folders button
		else if (e.target.classList.contains("btn-fold-item")) {
			biid = e.target.id;
			console.log("GAME - Item sub-folder button pressed; id="+biid);
			/// Check for complementary folder
			const folderItem = document.querySelector("#targetOf-" + biid);
			let folderItemVisible = getStyle(folderItem, "display") !== "none";
			if (folderItemVisible) {
				folderItem.style.display = "none";
			} else {
				folderItem.style.display = "inline";
			}
		}
		
		// Other buttons
		else {
			console.log("GAME - Unmapped button pressed: " + etarget + "; id: " + etarget.id);
		}
	}
	// Stop useless travels
	e.stopPropagation;
}

function changes_listen(e) {
	/**
	 * Function from main listener of inputs changes. Allow saving of inputs without sending form
	 * Access - Number input with .valueAsNumber
	 * Store - localStorage; shape: (elemName) ELEM -> (infos) [{id, value}, {id, value},...];
	 * Store - Attention: localStorage can only store Strings :D, so need to JSON each time
	 */
	if (e.target !== e.currentTarget) {
		// Vars
		const etarget = e.target;
		const inid = etarget.id;
		const input_building_name = etarget.building;
		/// Getting element name
		//// Elem definition on top
		/// Modify it if already exists
		let infos = {};
		if (localStorage.hasOwnProperty(elemName)) {
			infos = JSON.parse(localStorage.getItem(elemName));
		}
		console.log("GAME - Input changed: " + etarget + "; id:" + inid + "; building:" + input_building_name);
		// White list
		if (one_common(etarget.classList, inp_class_whitelist)) {
			if (etarget.type == 'number') {
				infos[inid] = etarget.valueAsNumber;
			} else {
				console.log("GAME - Input changed: Unmapped");
			}
		}
		if (infos) {
			//alert("ElemName: " + elemName + "; Infos: " + infos)
			localStorage.setItem(elemName, JSON.stringify(infos));
		}

		// Update turn form
		const input_user_inputs = document.querySelector("#input-turn-hidden-userInputs");
		const local_storage_string = stringify_local_storage();
		input_user_inputs.value = local_storage_string;

	}
	// Stop useless travels
	e.stopPropagation;
}

function changes_apply(e) {
	const len_ls = localStorage.length;
	//// Elem definition on top
	const infos = JSON.parse(localStorage.getItem(elemName));
	/// Get element values
	if (infos) {
		for (const [id, value] of Object.entries(infos)) {
			let elem_i = document.querySelector("#" + id);
			elem_i ? elem_i.value = value : elem_i.value = 0;
		}
	}
}


// Page loaded
var ui_game = document.querySelector("#ctnr-ui-game");
/// If there is game panel
if (ui_game) {
	changes_apply(ui_game);
	ui_game.addEventListener("click", clicks, false);
	ui_game.addEventListener("change", changes_listen, false);
	console.log("CtrlPanel - Game controls loaded");
/// Security
} else {
	console.log("CtrlPanel - No game!");
}