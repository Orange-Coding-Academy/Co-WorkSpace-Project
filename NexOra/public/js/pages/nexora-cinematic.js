/* NexOra cinematic landing — hero film (GSAP ScrollTrigger) + page interactions.
   Requires window.gsap and window.ScrollTrigger for the film. Without them the page
   falls back to the static final composition (same fallback as reduced motion / phones). */
(function () {
  'use strict';

  var html = document.documentElement;
  var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  var finePointer = matchMedia('(hover: hover) and (pointer: fine)').matches;
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };


/* ---------- Header + mobile menu ---------- */
var header = $('#nxHeader');
var burger = $('#nxBurger');
var menu = $('#nxMenu');
var menuOpen = false;

function setMenu(open) {
  if (!header || !burger || !menu) return;

  menuOpen = open;

  burger.setAttribute('aria-expanded', String(open));
  burger.setAttribute(
    'aria-label',
    open ? 'Close menu' : 'Open menu'
  );

  menu.classList.toggle('is-open', open);
  header.classList.toggle('menu-open', open);

  document.body.style.overflow = open ? 'hidden' : '';
}

if (burger && menu && header) {
  burger.addEventListener('click', function () {
    setMenu(!menuOpen);
  });

  $$('a', menu).forEach(function (a) {
    a.addEventListener('click', function () {
      setMenu(false);
    });
  });

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;

    if (menuOpen) {
      setMenu(false);
      burger.focus();
    }

    $$('.nx-account details[open]').forEach(function (d) {
      d.removeAttribute('open');
    });
  });

  var desktopQuery = matchMedia('(min-width: 1024px)');

  if (desktopQuery.addEventListener) {
    desktopQuery.addEventListener('change', function (m) {
      if (m.matches && menuOpen) {
        setMenu(false);
      }
    });
  } else if (desktopQuery.addListener) {
    desktopQuery.addListener(function (m) {
      if (m.matches && menuOpen) {
        setMenu(false);
      }
    });
  }
}

document.addEventListener('click', function (e) {
  $$('.nx-account details[open]').forEach(function (d) {
    if (!d.contains(e.target)) {
      d.removeAttribute('open');
    }
  });
});

function updateHeader() {
  if (!header || header.classList.contains('is-forced')) return;

  header.classList.toggle(
    'is-solid',
    window.scrollY > 40
  );
}


  /* ---------- Word-split headlines ---------- */
  $$('[data-split]').forEach(function (el) {
    var words = el.textContent.trim().split(/\s+/);
    el.setAttribute('aria-label', words.join(' '));
    el.innerHTML = words.map(function (w, i) {
      var s = document.createElement('span'); s.textContent = w;
      return '<span class="w" aria-hidden="true" style="--i:' + i + '"><span>' + s.innerHTML + '</span></span>';
    }).join(' ');
  });

  /* ---------- Reveal on view ---------- */
  var revealEls = $$('[data-split], .nx-mask');
  if ('IntersectionObserver' in window && !reduce) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
      });
    }, { rootMargin: '0px 0px -12% 0px', threshold: 0.05 });
    revealEls.forEach(function (el) { io.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('is-in'); });
  }

  /* ---------- Parallax (light, in-view only) ---------- */
  var par = $$('[data-speed]'), ticking = false;
  function frame() {
    ticking = false;
    updateHeader();
    if (reduce) return;
    var vh = window.innerHeight;
    par.forEach(function (el) {
      var r = el.getBoundingClientRect();
      if (r.bottom < -200 || r.top > vh + 200) return;
      var c = (r.top + r.height / 2 - vh / 2);
      el.style.setProperty('--py', (c * parseFloat(el.dataset.speed) * -1).toFixed(1));
    });
  }
  function onScroll() { if (!ticking) { ticking = true; requestAnimationFrame(frame); } }
  addEventListener('scroll', onScroll, { passive: true });
  addEventListener('resize', onScroll, { passive: true });
  frame();

  /* ---------- Magnetic buttons ---------- */
  if (finePointer && !reduce) {
    $$('[data-magnet]').forEach(function (b) {
      b.addEventListener('mousemove', function (e) {
        var r = b.getBoundingClientRect();
        b.style.setProperty('--bx', ((e.clientX - r.left - r.width / 2) * 0.22).toFixed(1) + 'px');
        b.style.setProperty('--by', ((e.clientY - r.top - r.height / 2) * 0.3).toFixed(1) + 'px');
      });
      b.addEventListener('mouseleave', function () { b.style.setProperty('--bx', '0px'); b.style.setProperty('--by', '0px'); });
    });
  }

  /* ---------- Amenity spotlight ---------- */
  $$('.nx-am').forEach(function (c) {
    c.addEventListener('mousemove', function (e) {
      var r = c.getBoundingClientRect();
      c.style.setProperty('--mx', (e.clientX - r.left) + 'px');
      c.style.setProperty('--my', (e.clientY - r.top) + 'px');
    });
  });

  /* ---------- Personas ---------- */

/* ---------- Personas ---------- */
var personas = $$('.nx-persona');
var stack = $$('.nx-ways__stack img');

function openPersona(index) {
  personas.forEach(function (persona, i) {
    var active = i === index;

    persona.classList.toggle('is-open', active);

    var button = $('button', persona);

    if (button) {
      button.setAttribute('aria-expanded', String(active));
    }
  });

  stack.forEach(function (img, i) {
    img.classList.toggle('is-on', i === index);
  });
}

personas.forEach(function (persona, index) {
  var button = $('button', persona);

  if (!button) return;

  button.addEventListener('click', function () {
    openPersona(index);
  });

  if (finePointer) {
    button.addEventListener('mouseenter', function () {
      openPersona(index);
    });
  }
});

/* Keep the first persona visible on initial load */
if (personas.length) {
  openPersona(0);
}

  /* ---------- Hero film ---------- */
  var cards = $$('.nx-card'), orbit = { a: 0.6 };
  function placeCards(a) {
    cards.forEach(function (c, i) {
      var t = a + i * (Math.PI * 2 / cards.length), d = (Math.sin(t) + 1) / 2;
      var x = 1130 + Math.cos(t) * 290, y = 300 + Math.sin(t) * 55, s = 0.78 + 0.22 * d;
      c.setAttribute('transform', 'translate(' + x.toFixed(1) + ' ' + y.toFixed(1) + ') scale(' + s.toFixed(3) + ')');
      c.style.opacity = (0.55 + 0.45 * d).toFixed(2);
    });
  }
  placeCards(orbit.a);

  function staticHero() {
    html.classList.add('nx-static');
    // On narrow screens crop the scene to the working desk.
    if (window.innerWidth < 768) $('#scene').setAttribute('viewBox', '760 90 760 810');
  }

  function initFilm() {
    var g = window.gsap, q = function (s) { return $(s); }, qa = function (s) { return g.utils.toArray(s); };
    g.registerPlugin(window.ScrollTrigger);

    var walker = q('#walker'), head = q('#head'), legL = q('#legL'), legR = q('#legR'), armB = q('#armB');
    var caps = qa('#caps li'), final = q('#final'), ctas = q('.nx-final__cta'), bar = q('#heroBar');
    var finalKids = qa('#final > *');

    /* initial state = the empty street at dusk */
    g.set([legL, legR, armB], { transformOrigin: '50% 0%' });
    g.set(head, { transformOrigin: '50% 100%' });
    g.set(walker, { x: 140, transformOrigin: '50% 100%' });
    g.set(['#doorGrp', '#sconces', '#doorLight', '#spill'], { opacity: 0 });
    g.set('#doorLeaf', { transformOrigin: '0% 50%' });
    g.set('#cam', { svgOrigin: '1250 500' });
    g.set('#room', { svgOrigin: '1130 520', scale: 1.22 });
    g.set('#worldOut', { opacity: 1 });
    g.set('#worldIn', { opacity: 0 });
    g.set('#dim', { opacity: 0.88 });
    g.set('.nx-slat', { transformOrigin: '50% 0%', scaleY: 0 });
    g.set(['#win', '#beam'], { opacity: 0 });
    g.set('.nx-desk', { x: function (i) { return i % 2 ? 1300 : -1300; }, opacity: 0 });
    g.set('.nx-chair', { x: function (i) { return i % 2 ? -260 : 260; }, opacity: 0 });
    g.set('.nx-pend', { y: -340 });
    g.set(['.nx-pglow', '.nx-pcone'], { opacity: 0 });
    g.set('.nx-pot', { opacity: 0, y: 30 });
    g.set('.nx-leaf', { transformOrigin: '50% 100%', scale: 0 });
    g.set('.nx-book', { y: -300, opacity: 0, rotation: function (i) { return (i % 2 ? 1 : -1) * 18; }, transformOrigin: '50% 50%' });
    g.set('.nx-paper', {
      x: function (i) { return (i % 2 ? 1 : -1) * (160 + i * 40); },
      y: function (i) { return -220 - i * 30; },
      rotation: function (i) { return (i % 2 ? 1 : -1) * (40 + i * 12); },
      opacity: 0, transformOrigin: '50% 50%'
    });
    g.set('.nx-lap', { transformOrigin: '50% 100%', scaleY: 0.04 });
    g.set('.nx-cup', { y: -50, opacity: 0 });
    g.set('.nx-steam', { opacity: 0 });
    g.set('.nx-sitter', { opacity: 0, y: 40 });
    g.set('#ideaGlow', { opacity: 0 });
    g.set('#bulb', { opacity: 0, scale: 0.3, svgOrigin: '1130 430' });
    g.set('.nx-ray', { opacity: 0 });
    g.set('.nx-pt', { opacity: 0, scale: 0, y: 60, transformOrigin: '50% 50%' });
    g.set('.nx-conn, .nx-sketch', { strokeDasharray: 1, strokeDashoffset: 1 });
    g.set('.nx-node', { scale: 0, transformOrigin: '50% 50%' });
    g.set('.nx-cardin', { scale: 0, opacity: 0, transformOrigin: '50% 50%' });
    g.set('#scrim', { opacity: 0 });
    g.set(final, { opacity: 1 });
    g.set(finalKids, { opacity: 0, y: 44 });
    g.set(caps, { opacity: 0, y: 16 });

    function walk() {
      var ph = g.getProperty(walker, 'x') / 24, s = Math.sin(ph);
      g.set(legL, { rotation: s * 22 });
      g.set(legR, { rotation: -s * 22 });
      g.set(armB, { rotation: -s * 16 });
      g.set(walker, { y: -Math.abs(s) * 5 });
    }

    var tl = g.timeline({
      defaults: { ease: 'none' },
      scrollTrigger: {
        trigger: '#heroPin', start: 'top top',
        end: function () { return '+=' + Math.round(window.innerHeight * 6.5); },
        scrub: 1, pin: true, anticipatePin: 1, invalidateOnRefresh: true,
        onUpdate: function (self) {
          bar.style.transform = 'scaleX(' + self.progress.toFixed(4) + ')';
          ctas.style.pointerEvents = self.progress > 0.9 ? 'auto' : 'none';
        }
      }
    });

    /* Scene 1 — searching */
    tl.to(caps[0], { opacity: 1, y: 0, duration: 0.8 }, 0.3)
      .to('#cue', { opacity: 0, duration: 0.8 }, 0.4)
      .to(head, { scaleX: -1, duration: 0.5, ease: 'power2.inOut' }, 0.9)
      .to(head, { scaleX: 1, duration: 0.5, ease: 'power2.inOut' }, 2.0)
      .to(head, { scaleX: -1, duration: 0.5, ease: 'power2.inOut' }, 3.1)
      .to(head, { scaleX: 1, duration: 0.5, ease: 'power2.inOut' }, 4.4)
      .to(walker, { x: 520, duration: 3.4, onUpdate: walk }, 0.8)
      .to(walker, { x: 1070, duration: 2.2, onUpdate: walk }, 4.2)
      .to('#cam', { scale: 1.12, duration: 4.4 }, 2)
      .to(caps[0], { opacity: 0, y: -12, duration: 0.7 }, 3.9)

      /* Scene 2 — the door */
      .to('#sconces', { opacity: 1, duration: 1.4 }, 3.4)
      .to('#doorGrp', { opacity: 1, duration: 1.4 }, 3.6)
      .to(caps[1], { opacity: 1, y: 0, duration: 0.7 }, 4.4)
      .to(caps[1], { opacity: 0, y: -12, duration: 0.6 }, 6.0)
      .to('#cam', { scale: 1.5, duration: 1.4, ease: 'power2.inOut' }, 6.4)
      .to('#doorLeaf', { scaleX: 0.1, duration: 1.3, ease: 'power2.inOut' }, 6.5)
      .to('#doorLight', { opacity: 1, duration: 1 }, 6.6)
      .to('#spill', { opacity: 1, duration: 1.2 }, 6.7)
      .to(walker, { x: 1236, duration: 1.1, onUpdate: walk }, 7.8)
      .to(walker, { opacity: 0, duration: 0.5 }, 8.4)
      .to('#cam', { scale: 12, duration: 2.4, ease: 'power3.in' }, 8.3)
      .to('#worldOut', { opacity: 0, duration: 1, ease: 'power1.inOut' }, 9.7)
      .to('#worldIn', { opacity: 1, duration: 1.1, ease: 'power1.inOut' }, 9.7)

      /* Scene 3 — the workspace comes to life */
      .to('#room', { scale: 1.1, duration: 10 }, 10.2)
      .to(caps[2], { opacity: 1, y: 0, duration: 0.7 }, 10.8)
      .to(caps[2], { opacity: 0, y: -12, duration: 0.6 }, 15.4)
      .to('.nx-slat', { scaleY: 1, duration: 1.6, stagger: 0.1, ease: 'power2.out' }, 10.4)
      .to('#win', { opacity: 1, duration: 2 }, 11.4)
      .to('#beam', { opacity: 0.16, duration: 2.2 }, 12.6)
      .to('.nx-desk', { x: 0, opacity: 1, duration: 2.2, stagger: 0.35, ease: 'power3.out' }, 11)
      .to('.nx-chair', { x: 0, opacity: 1, duration: 1.6, stagger: 0.3, ease: 'power2.out' }, 12.6)
      .to('.nx-pend', { y: 0, duration: 1.6, stagger: 0.2, ease: 'back.out(1.1)' }, 12.2)
      .to('.nx-pot', { opacity: 1, y: 0, duration: 0.8, stagger: 0.2 }, 13)
      .to('.nx-leaf', { scale: 1, duration: 1.2, stagger: 0.07, ease: 'back.out(1.7)' }, 13.4)
      .to('#dim', { opacity: 0.3, duration: 2.4 }, 13.6)
      .to('.nx-pglow', { opacity: 0.75, duration: 1, stagger: 0.2 }, 13.9)
      .to('.nx-pcone', { opacity: 1, duration: 1, stagger: 0.2 }, 13.9)
      .to('.nx-book', { y: 0, opacity: 1, rotation: 0, duration: 1.1, stagger: 0.2, ease: 'power2.out' }, 13.4)
      .to('.nx-paper', { x: 0, y: 0, rotation: 0, opacity: 1, duration: 2, stagger: 0.12, ease: 'power2.out' }, 13.8)
      .to('.nx-lap', { scaleY: 1, duration: 1, stagger: 0.2, ease: 'power2.out' }, 14.8)
      .to('.nx-cup', { y: 0, opacity: 1, duration: 0.8, stagger: 0.15 }, 15.2)
      .to('.nx-steam', { opacity: 0.7, duration: 1 }, 16)
      .to('.nx-sitter', { opacity: 1, y: 0, duration: 1.4, ease: 'power2.out' }, 15.4)

      /* Scene 4 — ideas come to life */
      .to(caps[3], { opacity: 1, y: 0, duration: 0.7 }, 16.8)
      .to(caps[3], { opacity: 0, y: -12, duration: 0.6 }, 20.4)
      .to('#ideaGlow', { opacity: 0.28, duration: 2 }, 16.8)
      .to('#bulb', { opacity: 1, scale: 1, duration: 0.9, ease: 'back.out(2)' }, 17)
      .to('.nx-ray', { opacity: 1, duration: 0.6, stagger: 0.05 }, 17.4)
      .to('.nx-pt', {
        opacity: 0.85, scale: 1, duration: 3, stagger: 0.08, ease: 'power2.out',
        x: function (i) { return Math.cos(i * 1.3) * 14; },
        y: function (i) { return -12 - (i % 5) * 7; }
      }, 16.8)
      .to('.nx-conn', { strokeDashoffset: 0, duration: 1, stagger: 0.3 }, 17.4)
      .to('.nx-node', { scale: 1, duration: 0.5, stagger: 0.2, ease: 'back.out(2)' }, 17.5)
      .to('.nx-cardin', { scale: 1, opacity: 1, duration: 0.8, stagger: 0.3, ease: 'back.out(1.6)' }, 17.8)
      .to('.nx-sketch', { strokeDashoffset: 0, duration: 1.4, stagger: 0.25 }, 18.2)
      .to(orbit, { a: orbit.a + Math.PI * 2.2, duration: 8, onUpdate: function () { placeCards(orbit.a); } }, 17)

      /* Scene 5 — final composition */
      .to('#room', { scale: 1, duration: 3.6, ease: 'power2.inOut' }, 20.4)
      .to('#scrim', { opacity: 1, duration: 1.4 }, 20.4)
      .to(finalKids, { y: 0, opacity: 1, duration: 1.4, stagger: 0.25, ease: 'power2.out' }, 21.2)
      .to({}, { duration: 1.2 }, 24);
  }

  var wantsFilm = !html.classList.contains('nx-static');
  if (wantsFilm && window.gsap && window.ScrollTrigger) {
    initFilm();
  } else {
    staticHero();
  }
})();
