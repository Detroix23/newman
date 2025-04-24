// Generate fixed blinking stars and shooting stars

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
        sf.style.top = Math.round(Math.random() * 100) + '%';
        sf.style.left = Math.round(Math.random() * 100)+ '%';
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
sf_gen('ctnr-sf', 100);

/// Shooting stars (cf. "ss")
class SS {
    constructor(ctnr, list, id, speed=5.0, lean=1, size=50, acc=0) {
        /// Var
        this.base = './img/img-ss';
        this.ctnr = document.querySelector("#" + ctnr);
        this.id = id;
        this.ctnr = ctnr;
        this.speed = speed;
        this.lean = lean;
        this.size = size;
        this.direction = 0;
        this.directionVect = [];
        this.list = list;
        this.alive = true;
        this.acc = acc;
        //// Position
        this.generate_pos();
        /// DOM
        this.elem = new Image(this.size, this.size);
        this.elem.src = this.base + 1;
        this.elem.id = 'ss' + this.id;
        this.elem.className = 'ss-star';
        this.elem.setAttribute("style", "transform: rotate(" + this.direction + "deg); bottom: " + this.y + "%; left: " + this.x + "%;");
        //// Code attributes
        this.elem.setAttribute("caracteristics", "speed: " + this.speed + "; lean: " + this.lean + "; accel: " + this.acc);
        //// Render
        this.list['ss' + this.id] = this;
    }

    generate_pos() {
        /// Choose side
        const randDist = randint(10, 90);
        const randDirection = randint(-50, 50); //// Rand direction in degrees, 0 is facing up
        const side = randint(0, 3); //// Clockwise, starting at top.
        
        switch (side) {
            case 0:
                this.x = randDist;
                this.y = 100;
                this.direction = randDirection + 180;
                break;
            case 1:
                this.x = 100;
                this.y = randDist;
                this.direction = randDirection + 270;
                break;
            case 2:
                this.x = randDist;
                this.y = 0;
                this.direction = randDirection;
                break;
            case 3:
                this.x = 0;
                this.y = randDist;
                this.direction = randDirection + 90;
                break;
        }
        
        console.log(`SS - Spawned: direction=${this.direction}, x=${this.x}, y=${this.y}, side=${side}`);
    }

    update() {
        /// Find vectorial direction from degree direction
        //// Degree to Radians conversion (+90 because we start 90degree off and Invert because we go anti-trigo)
        this.directionRad = (90 - this.direction) * (Math.PI / 180);
        //// Direction vector
        this.directionVect[0] = Math.cos(this.directionRad);    ///// X
        this.directionVect[1] = Math.sin(this.directionRad);    ///// Y
        
        // console.log(`SS - direction=${this.direction}, vector=${this.directionVect[0]};${this.directionVect[1]}`);
        
        //// Move
        this.x += this.directionVect[0] * this.speed;
        this.y += this.directionVect[1] * this.speed;
        //// Simplify
        this.x = roundr(this.x, 4);
        this.y = roundr(this.y, 4);
        //// Lean
        this.direction += this.lean;
        this.direction %= 360;
        this.direction = roundr(this.direction, 4);
        //// Accelerate
        this.speed += this.speed * this.acc;

        //// Check if out of bounds
        if (this.x > 110 | this.x < -10 | this.y > 110 | this.y < -10) {
            this.alive = false;
            console.log(`SS - Out of bounds: x=${this.x}; y=${this.y}`);
        }

        //// Apply to DOM
        this.elem.setAttribute("style", "transform: rotate(" + this.direction + "deg); bottom: " + this.y + "%; left: " + this.x + "%;");

        
        
        // console.log(
        //  `SS - Direction: vector=${this.directionVect[0]},${this.directionVect[1]}; angle=${this.direction}deg=${this.directionRad}rad; Pos: x=${this.x}, y=${this.y}`
        // );

        return this.alive;
    }

    perish() {
        delete this.list['ss' + this.id];
    }
}

//// Generation
const sss = [];
const ss_ctnr = document.querySelector('#ctnr-ss');
let id = 0;
let speed = 0.5;
let lean = 0.5;
let size = 35;
let acc = 0.010;
let rate = 0.008;

//// Execution
const fps = 60;
const interSs = setInterval(() => {
    /// New stars
    if (Math.random() < rate) {
        sss.push(new SS('ctnr-ss', sss, id, 
            randec(speed, speed+0.2, 6),
            randec(-lean, lean, 6), 
            randec(size, size*2, 6),
            randec(acc, acc+0.006, 6)
        ));
        ss_ctnr.appendChild(sss[sss.length - 1].elem);
        id += 1;
    }
    /// To be removed
    sssRemove = [];
    /// Go throught all
    sss.forEach(ss => {
        alive = ss.update();
        if (!alive) {
            ss_ctnr.removeChild(ss.elem);
            sssRemove.push(sss.indexOf(ss));
        }
    });
    /// Garbage collector
    sssRemove.forEach(ssi => {
        if (ssi > -1) {
            sss.splice(ssi, 1);
        }
    });


}, 1000/fps);