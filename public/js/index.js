
    /* ── PARTICLES ── */
    const cv=document.getElementById('c');
    const cx=cv.getContext('2d');
    let W,H,pts=[];

    function resize(){W=cv.width=window.innerWidth;H=cv.height=window.innerHeight;}
    resize();
    window.addEventListener('resize',resize);

    const SHAPES=['key','star','dot','diamond','tri'];
    // ألوان الجزيئات: ذهبية وخضراء
    const COLS=[
        'rgba(201,168,76,',
        'rgba(245,216,122,',
        'rgba(76,175,80,',
        'rgba(46,92,46,',
    ];

    function rnd(a,b){return a+Math.random()*(b-a);}

    function newPt(){
        return{
            x:rnd(0,W),y:rnd(H*.05,H*.9),
            vx:rnd(-.25,.25),vy:rnd(-.5,-.1),
            s:rnd(4,13),
            op:0,maxOp:rnd(.07,.18),
            life:0,maxLife:rnd(220,450),
            shape:SHAPES[Math.floor(Math.random()*SHAPES.length)],
            col:COLS[Math.floor(Math.random()*COLS.length)],
        };
    }

    for(let i=0;i<32;i++){const p=newPt();p.life=Math.floor(Math.random()*p.maxLife);pts.push(p);}

    function draw(p){
    cx.save();cx.translate(p.x,p.y);
    const a=p.op;
    cx.fillStyle=p.col+a+')';
    cx.strokeStyle=p.col+a+')';
    cx.lineWidth=1.3;

    if(p.shape==='key'){
        cx.beginPath();cx.arc(0,0,p.s*.5,0,Math.PI*2);cx.stroke();
        cx.beginPath();cx.moveTo(p.s*.5,0);cx.lineTo(p.s*1.5,0);cx.stroke();
        cx.beginPath();cx.moveTo(p.s*1.1,0);cx.lineTo(p.s*1.1,p.s*.45);cx.stroke();
        cx.beginPath();cx.moveTo(p.s*1.5,0);cx.lineTo(p.s*1.5,p.s*.45);cx.stroke();
    }else if(p.shape==='star'){
        cx.beginPath();
        for(let i=0;i<5;i++){
        const a=Math.PI/2+i*Math.PI*2/5,b=a+Math.PI/5,r=p.s,ri=p.s*.38;
        i===0?cx.moveTo(Math.cos(a)*r,-Math.sin(a)*r):cx.lineTo(Math.cos(a)*r,-Math.sin(a)*r);
        cx.lineTo(Math.cos(b)*ri,-Math.sin(b)*ri);
        }
        cx.closePath();cx.fill();
    }else if(p.shape==='dot'){
        cx.beginPath();cx.arc(0,0,p.s*.38,0,Math.PI*2);cx.fill();
    }else if(p.shape==='diamond'){
        const h=p.s*.75;
        cx.beginPath();cx.moveTo(0,-h);cx.lineTo(h*.6,0);cx.lineTo(0,h);cx.lineTo(-h*.6,0);cx.closePath();cx.stroke();
    }else if(p.shape==='tri'){
        cx.beginPath();cx.moveTo(0,-p.s*.7);cx.lineTo(p.s*.6,p.s*.5);cx.lineTo(-p.s*.6,p.s*.5);cx.closePath();cx.stroke();
    }
    cx.restore();
    }

    (function tick(){
    cx.clearRect(0,0,W,H);
    for(const p of pts){
        p.life++;
        const h=p.maxLife/2;
        p.op=p.life<h?(p.life/h)*p.maxOp:((p.maxLife-p.life)/h)*p.maxOp;
        p.x+=p.vx;p.y+=p.vy;
        draw(p);
        if(p.life>=p.maxLife)Object.assign(p,newPt());
    }
    requestAnimationFrame(tick);
    })();

    /* ── PROGRESS BAR ── */
    const fill = document.getElementById('barFill');
    const DURATION = 2000;
    const START_DELAY = 1000;
    let startTime = null;

function animateBar(ts){
    if(!startTime) startTime = ts;
    const pct = Math.min((ts - startTime) / DURATION * 100, 100);
    fill.style.width = pct + '%';
    if(pct < 100) requestAnimationFrame(animateBar);
    else setTimeout(()=>{ window.location.href=splashScreenUrl; }, 200);
}

setTimeout(()=>{ requestAnimationFrame(animateBar); }, START_DELAY);
