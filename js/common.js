// File for common JS function not in vanilla

function getStyle(elem, styleProperty) {
    
	if (elem.currentStyle)
		var elemStyle = elem.currentStyle[styleProperty];
	else if (window.getComputedStyle)
		var elemStyle = document.defaultView.getComputedStyle(elem, null).getPropertyValue(styleProperty);
	return elemStyle;
}

function randec(min, max, dec=0) {
    min = Math.ceil(min * (10 ** dec));
    max = Math.floor(max * (10 ** dec));
    return Math.floor(Math.random() * (max-min) + min) / (10 ** dec);
}

function randint(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max-min) + min);
}

function roundr(n, dec) {
    return Math.round(n * (10 ** dec)) / (10 ** dec);
}

function one_common(arr1, arr2) {
    /**
     * Function: Find if 2 arrays have at least one value in common
     */
    let found = false;
    arr1.forEach(i => {
        arr2.forEach(j => {
            if (i == j) found = true;
        }); 
    });

    return found;
}

function dom_ln(e) {
    e.appendChild(document.createElement('br'))
}

/* COMMUNICATION via DOM*/
function data_local_storage(eId) {
    const ls_key_black = ['_'];
    const jsLocalStorage = document.querySelector("#" + eId);
    let jsLocalStorageTxt = "";
    if (localStorage.length !== 0) {
        const len_ls = localStorage.length;
        for (i = 0; i < len_ls; i++) {
            const ls_key = localStorage.key(i);
            const ls_val = localStorage.getItem(ls_key)
            if (!ls_key_black.includes(ls_key)) {
                //jsLocalStorageTxt += ls_key + "=[" + ls_val + "]<br>\n";
                //jsLocalStorage.innerHTML = jsLocalStorageTxt;
                //// DOM
                const ls_line = document.createElement('info');
                ls_line.id = "data-ls-" + ls_key;
                ls_line.key = ls_key;
                ls_line.value = ls_val;
                ls_line.innerHTML = ls_val;
                jsLocalStorage.appendChild(ls_line);
                dom_ln(jsLocalStorage);
                //const testLs = document.querySelector("#" + ls_line.id);
                //alert("LS_line: " + testLs.id + ", value=" + testLs.value + ", key=" + testLs.key);
            }
        }
    } else {
        jsLocalStorageTxt = "Empty local storage!";
        localStorage.setItem("_", "Local Js storage: mainly saving player's inputs");
        jsLocalStorage.innerHTML = jsLocalStorageTxt;
    }
}
