
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');
    


        (function() {
            'use strict';
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
            var isSmall = window.matchMedia('(max-width: 767px)');

            /* ---------- Header + mobile menu ---------- */
            var header = document.getElementById('siteHeader');
            var toggle = document.getElementById('menuToggle');
            var mobileNav = document.getElementById('mobileNav');
            var menuOpen = false;

            function setMenu(open) {
                menuOpen = open;
                toggle.setAttribute('aria-expanded', String(open));
                toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
                mobileNav.classList.toggle('is-open', open);
                updateHeader();
            }

            function closeMenu() {
                if (menuOpen) setMenu(false);
            }
            toggle.addEventListener('click', function() {
                setMenu(!menuOpen);
            });
            mobileNav.querySelectorAll('a').forEach(function(a) {
                a.addEventListener('click', closeMenu);
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && menuOpen) {
                    setMenu(false);
                    toggle.focus();
                }
            });
            window.matchMedia('(min-width: 1024px)').addEventListener('change', function(m) {
                if (m.matches) closeMenu();
            });

            function updateHeader() {
                // On non-landing pages the header carries is-forced and stays solid.
                if (header.classList.contains('is-forced')) return;
                var y = window.scrollY;
                header.classList.toggle('is-solid', y > 40 || menuOpen);
                header.classList.toggle('is-compact', y > 120);
            }

            /* ---------- Account dropdown: close on outside click / Escape ---------- */
            document.addEventListener('click', function(e) {
                document.querySelectorAll('.nx-account-menu details[open]').forEach(function(d) {
                    if (!d.contains(e.target)) d.removeAttribute('open');
                });
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.nx-account-menu details[open]').forEach(function(d) {
                        d.removeAttribute('open');
                    });
                }
            });

            /* ---------- Reveal on scroll ---------- */
            var revealEls = document.querySelectorAll('.reveal, .reveal-img');
            if ('IntersectionObserver' in window && !reduce.matches) {
                var io = new IntersectionObserver(function(entries) {
                    entries.forEach(function(en) {
                        if (en.isIntersecting) {
                            en.target.classList.add('is-in');
                            io.unobserve(en.target);
                        }
                    });
                }, {
                    rootMargin: '0px 0px -10% 0px',
                    threshold: 0.08
                });
                revealEls.forEach(function(el) {
                    io.observe(el);
                });
            } else {
                revealEls.forEach(function(el) {
                    el.classList.add('is-in');
                });
            }

            /* ---------- Scroll-driven effects ---------- */
            var root = document.documentElement;
            var heroFrame = document.getElementById('heroFrame');
            var heroContent = document.getElementById('heroContent');
            var heroImg = document.getElementById('heroImg');
            var story = document.getElementById('story');
            var track = document.getElementById('storyTrack');
            var stage = document.getElementById('storyStage');
            var bar = document.getElementById('storyBar');
            var storyImgs = stage.querySelectorAll('img');
            var storySteps = track.querySelectorAll('.story-step');
            var storyLbls = track.querySelectorAll('.lbl');
            var finalArt = document.getElementById('finalArt');
            var parallaxImgs = document.querySelectorAll('.parallax img');
            var currentStep = 0;

            function clamp(v, a, b) {
                return Math.min(b, Math.max(a, v));
            }

            function useStacked() {
                return reduce.matches || isSmall.matches;
            }

            function applyMode() {
                story.classList.toggle('is-stacked', useStacked());
            }
            applyMode();
            reduce.addEventListener('change', applyMode);
            isSmall.addEventListener('change', applyMode);

            function frame() {
                ticking = false;
                updateHeader();
                if (reduce.matches) return;
                var vh = window.innerHeight;
                var y = window.scrollY;

                // Hero: image scales gently, frame insets, text drifts and fades.
                var hp = clamp(y / vh, 0, 1);
                heroImg.style.setProperty('--hero-s', (1.05 + hp * 0.1).toFixed(4));
                heroFrame.style.setProperty('--hero-p', isSmall.matches ? 0 : (hp * 20).toFixed(2));
                heroContent.style.setProperty('--hero-y', (hp * -80).toFixed(1));
                heroContent.style.setProperty('--hero-o', clamp(1 - hp * 1.4, 0, 1).toFixed(3));

                // Sticky story.
                if (!useStacked()) {
                    var r = track.getBoundingClientRect();
                    var total = r.height - vh;
                    var p = clamp(-r.top / total, 0, 1);
                    stage.style.setProperty('--clip', clamp(1 - p / 0.15, 0, 1).toFixed(3));
                    bar.style.setProperty('--prog', p.toFixed(4));
                    var s = Math.min(2, Math.floor(p * 3));
                    if (s !== currentStep) {
                        storyImgs[currentStep].classList.remove('is-active');
                        storySteps[currentStep].classList.remove('is-active');
                        storyLbls[currentStep].classList.remove('is-active');
                        storyImgs[s].classList.add('is-active');
                        storySteps[s].classList.add('is-active');
                        storyLbls[s].classList.add('is-active');
                        currentStep = s;
                    }
                }

                // Restrained parallax (in-view only).
                var strength = isSmall.matches ? 18 : 36;
                parallaxImgs.forEach(function(img) {
                    var b = img.parentElement.getBoundingClientRect();
                    if (b.bottom < 0 || b.top > vh) return;
                    var c = (b.top + b.height / 2 - vh / 2) / vh;
                    img.style.setProperty('--py', (c * -strength).toFixed(1));
                });

                // Final CTA: the door opens as it enters view.
                var fr = finalArt.getBoundingClientRect();
                finalArt.style.setProperty('--door', clamp(1 - (fr.top + fr.height / 2) / vh, 0, 1).toFixed(3));
            }

            var ticking = false;

            function onScroll() {
                if (!ticking) {
                    ticking = true;
                    window.requestAnimationFrame(frame);
                }
            }
            window.addEventListener('scroll', onScroll, {
                passive: true
            });
            window.addEventListener('resize', onScroll, {
                passive: true
            });
            frame();
        })();
    