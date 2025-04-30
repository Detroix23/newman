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