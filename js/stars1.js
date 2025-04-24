// Generate fixed blinking stars and shooting stars

function randint(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max-min) + min);
}

/// Fixed blinking stars (cf. "sf")
function sf_gen(e, numb, size=10) {
    //// Boilerplate
    const ctnr = document.querySelector('#' + e);
    const sfs = [];
    const numbr = randint(numb/2, numb*2);
    const anims_duration = [3, 10];
    const base = './img/img-sf';
    //// All stars
    for (i=0; i<numbr; i++) {
        ///// Init
        let sf = new Image(size, size);
        sf.src = base + 1;
        sf.id = 'sf' + i;
        sf.className = 'sf-star';
        ///// Random pos
        sf.style.top = Math.round(Math.random() * 95) + '%';
        sf.style.left = Math.round(Math.random() * 95)+ '%';
        ///// Random animations
        sf.style['animation-duration'] = randint(anims_duration[0], anims_duration[1]) + 's';
        sf.style['animation-delay'] =  randint(0, anims_duration[1]) + 's';
        ///// Render
        sfs['sf'+i] = sf;
        ctnr.appendChild(sf);

    }
    console.log(`SF - Generated: ${numbr} stars`);
    return sfs;
}

//// One time exec
sf_gen('ctnr-stars', 100);

/// Shooting stars (cf. "ss")
class SS {
    base = './img/img-ss';
    constructor(ctnr, list, speed=1.0, lean=0.0, size=50) {
        /// Vars
        this.ctnr = ctnr;
        this.speed = speed;
        this.lean = lean;
        this.size = size;
        this.id = id;
        this.list = list;
        /// DOM
        this.elem = new Image(this.size, this.size);
        this.elem.src = base + 1;
        this.elem.id = 'ss' + id;
        this.elem.className = 'ss-star';
        //// Position
        this.generate_pos();

        //// Render
        this.list.push(this);
        ctnr.appendChild(this.elem);

    }

    generate_pos() {
        /// Choose side
        const randDist = randint(0, 100);
        const randDirection = randint(-70, 70); //// Rand direction in degrees, 0 is facing up
        const side = randint(0, 3); //// Clockwise, starting at top.
        switch (side) {
            case 0:
                self.x = randDist;
                self.y = 100;
                self.direction = randDirection + 180;
            case 1:
                self.x = 100;
                self.y = randDist;
                self.direction = randDirection + 270;
            case 2:
                self.x = randDist;
                self.y = 0;
                self.direction = randDirection;
            case 3:
                self.x = 0;
                self.y = randDist;
                self.direction = randDirection + 90;
        }
    }



}



