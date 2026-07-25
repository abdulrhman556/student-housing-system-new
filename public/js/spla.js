
window.addEventListener('scroll',()=>{
  document.getElementById('nav').style.background =
    window.scrollY>10?'rgba(11,22,40,.98)':'rgba(11,22,40,.92)';
});

// counter animation
const counters=document.querySelectorAll('[data-count]');
const obs=new IntersectionObserver(entries=>{
  entries.forEach(e=>{
    if(!e.isIntersecting)return;
    const el=e.target,target=+el.dataset.count,suffix=el.dataset.suffix||'';
    let cur=0;const step=target/55;
    const t=setInterval(()=>{
      cur=Math.min(cur+step,target);
      el.textContent=Math.floor(cur).toLocaleString('ar-EG')+suffix;
      if(cur>=target)clearInterval(t);
    },22);
    obs.unobserve(el);
  });
},{threshold:.3});
counters.forEach(c=>obs.observe(c));
