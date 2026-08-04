(function () {
  const THEME_KEY = 'vipqr-theme';
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function getTheme() {
    return document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
  }

  function setTheme(theme) {
    const next = theme === 'dark' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', next);
    try {
      localStorage.setItem(THEME_KEY, next);
    } catch (e) {
      // ignore storage errors
    }
    const btn = document.getElementById('themeToggle');
    if (btn) {
      btn.setAttribute('aria-pressed', next === 'dark' ? 'true' : 'false');
      const label = next === 'dark'
        ? (btn.dataset.labelLight || 'Light')
        : (btn.dataset.labelDark || 'Dark');
      btn.setAttribute('aria-label', label);
      btn.title = label;
    }
  }

  function toggleTheme() {
    setTheme(getTheme() === 'dark' ? 'light' : 'dark');
  }

  function stickyOffset() {
    const topbar = document.getElementById('menuTopbar');
    const filter = document.querySelector('.filter-bar');
    const topbarH = topbar ? topbar.getBoundingClientRect().height : 0;
    const filterH = filter ? filter.getBoundingClientRect().height : 0;
    return Math.ceil(topbarH + filterH + 12);
  }

  function softScrollTo(target) {
    if (!target) {
      return;
    }

    const top = target.getBoundingClientRect().top + window.scrollY - stickyOffset();
    window.scrollTo({
      top: Math.max(0, top),
      behavior: prefersReducedMotion ? 'auto' : 'smooth',
    });
  }

  function setActiveChip(chip) {
    document.querySelectorAll('.cat-chip[data-soft-scroll]').forEach((el) => {
      el.classList.toggle('is-active', el === chip);
    });

    if (chip && chip.parentElement) {
      chip.scrollIntoView({
        inline: 'center',
        block: 'nearest',
        behavior: prefersReducedMotion ? 'auto' : 'smooth',
      });
    }
  }

  function initSoftScroll() {
    const links = document.querySelectorAll('[data-soft-scroll]');
    if (!links.length) {
      return;
    }

    links.forEach((link) => {
      link.addEventListener('click', (event) => {
        const href = link.getAttribute('href') || '';
        if (!href.startsWith('#')) {
          return;
        }

        const id = href.slice(1);
        const target = document.getElementById(id);
        if (!target) {
          return;
        }

        event.preventDefault();
        softScrollTo(target);

        if (link.classList.contains('cat-chip')) {
          setActiveChip(link);
        }

        if (history.replaceState) {
          history.replaceState(null, '', href);
        }
      });
    });

    // Scroll-spy: highlight chip while scrolling through sections
    const sections = Array.from(document.querySelectorAll('.menu-group[id^="cat-"]'));
    const chipMap = new Map();
    links.forEach((link) => {
      if (!link.classList.contains('cat-chip')) {
        return;
      }
      const href = link.getAttribute('href') || '';
      if (href.startsWith('#cat-')) {
        chipMap.set(href.slice(1), link);
      }
    });

    const allChip = document.querySelector('.cat-chip[href="#menyü"]');

    if (sections.length && 'IntersectionObserver' in window) {
      const observer = new IntersectionObserver(
        (entries) => {
          const visible = entries
            .filter((entry) => entry.isIntersecting)
            .sort((a, b) => b.intersectionRatio - a.intersectionRatio);

          if (!visible.length) {
            return;
          }

          const id = visible[0].target.id;
          const chip = chipMap.get(id);
          if (chip) {
            document.querySelectorAll('.cat-chip[data-soft-scroll]').forEach((el) => {
              el.classList.toggle('is-active', el === chip);
            });
          }
        },
        {
          rootMargin: '-35% 0px -50% 0px',
          threshold: [0.1, 0.25, 0.5],
        }
      );

      sections.forEach((section) => observer.observe(section));

      // Near top of menu: activate "Hamısı"
      if (allChip) {
        const topObserver = new IntersectionObserver(
          (entries) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting && window.scrollY < stickyOffset() + 80) {
                setActiveChip(allChip);
              }
            });
          },
          { threshold: 0.6 }
        );
        const menuAnchor = document.getElementById('menyü');
        if (menuAnchor) {
          topObserver.observe(menuAnchor);
        }
      }
    }

    // Hash on load
    if (window.location.hash) {
      const target = document.querySelector(window.location.hash);
      if (target) {
        requestAnimationFrame(() => {
          softScrollTo(target);
          const chip = document.querySelector(`.cat-chip[href="${window.location.hash}"]`);
          if (chip) {
            setActiveChip(chip);
          }
        });
      }
    }
  }

    function initViewTracking() {
    const endpoint = document.body.dataset.viewsUrl;
    if (!endpoint || !('IntersectionObserver' in window) || !window.fetch) {
      return;
    }

    const seen = new Set();
    const queue = new Set();
    let timer = null;

    function flush() {
      timer = null;
      if (!queue.size) {
        return;
      }
      const ids = Array.from(queue);
      queue.clear();
      fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify({ ids }),
        credentials: 'same-origin',
        keepalive: true,
      }).catch(() => {
        // ignore network errors — tracking is best-effort
      });
    }

    function schedule(id) {
      if (seen.has(id)) {
        return;
      }
      seen.add(id);
      queue.add(id);
      if (timer) {
        return;
      }
      timer = window.setTimeout(flush, 700);
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting || entry.intersectionRatio < 0.45) {
            return;
          }
          const id = Number(entry.target.getAttribute('data-product-id') || 0);
          if (id > 0) {
            schedule(id);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: [0.45] }
    );

    document.querySelectorAll('.product-item[data-product-id]').forEach((el) => {
      const id = Number(el.getAttribute('data-product-id') || 0);
      if (id > 0) {
        observer.observe(el);
      }
    });
  }

    function initHeroVideo() {
    const video = document.getElementById('heroAmbiance');
    if (!video) {
      return;
    }

    const hero = video.closest('.hero');
    if (hero && video.getAttribute('poster')) {
      hero.style.setProperty('--hero-reduced-poster', `url("${video.getAttribute('poster')}")`);
    }

    if (prefersReducedMotion) {
      video.pause();
      video.removeAttribute('autoplay');
      return;
    }

    video.muted = true;
    video.playsInline = true;
    video.setAttribute('muted', '');
    video.setAttribute('playsinline', '');

    const tryPlay = () => {
      const playPromise = video.play();
      if (playPromise && typeof playPromise.catch === 'function') {
        playPromise.catch(() => {
          const unlock = () => {
            video.play().catch(() => {});
            document.removeEventListener('touchstart', unlock);
            document.removeEventListener('click', unlock);
          };
          document.addEventListener('touchstart', unlock, { once: true, passive: true });
          document.addEventListener('click', unlock, { once: true });
        });
      }
    };

    if (video.readyState >= 2) {
      tryPlay();
    } else {
      video.addEventListener('loadeddata', tryPlay, { once: true });
    }
  }

  function initFavorites() {
    const FAV_KEY = 'vipqr-favorites';
    const body = document.body;
    const favEndpoint = body.dataset.favUrl || '';
    const t = {
      add: body.dataset.i18nFavAdd || 'Add',
      remove: body.dataset.i18nFavRemove || 'Remove',
      copied: body.dataset.i18nShareCopied || 'Copied',
      listCopied: body.dataset.i18nShareList || 'List copied',
      restaurant: body.dataset.restaurantName || 'VIP Karvan',
    };

    const panel = document.getElementById('favPanel');
    const backdrop = document.getElementById('favBackdrop');
    const listEl = document.getElementById('favList');
    const emptyEl = document.getElementById('favEmpty');
    const countEl = document.getElementById('favCount');
    const toggleBtn = document.getElementById('favToggle');
    const toastEl = document.getElementById('menuToast');
    let toastTimer = null;
    let syncTimer = null;

    function normalizeIds(list) {
      if (!Array.isArray(list)) {
        return [];
      }
      return list
        .map((id) => Number(id))
        .filter((id) => id > 0)
        .filter((id, i, arr) => arr.indexOf(id) === i)
        .slice(0, 50);
    }

    function mergeIds(a, b) {
      const out = normalizeIds(a);
      normalizeIds(b).forEach((id) => {
        if (!out.includes(id)) {
          out.push(id);
        }
      });
      return out.slice(0, 50);
    }

    function readFavs() {
      try {
        const raw = localStorage.getItem(FAV_KEY);
        const parsed = raw ? JSON.parse(raw) : [];
        return normalizeIds(parsed);
      } catch (e) {
        return [];
      }
    }

    function writeFavs(ids, pushServer) {
      const clean = normalizeIds(ids);
      try {
        localStorage.setItem(FAV_KEY, JSON.stringify(clean));
      } catch (e) {
        // ignore quota / private mode
      }
      if (pushServer !== false) {
        scheduleServerSync(clean);
      }
      return clean;
    }

    function scheduleServerSync(ids) {
      if (!favEndpoint || !window.fetch) {
        return;
      }
      if (syncTimer) {
        window.clearTimeout(syncTimer);
      }
      syncTimer = window.setTimeout(() => {
        fetch(favEndpoint, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
          body: JSON.stringify({ ids }),
          credentials: 'same-origin',
          keepalive: true,
        }).catch(() => {
          // offline — localStorage remains source until next sync
        });
      }, 280);
    }

    async function pullFromServer() {
      if (!favEndpoint || !window.fetch) {
        return null;
      }
      try {
        const res = await fetch(favEndpoint, {
          method: 'GET',
          headers: { Accept: 'application/json' },
          credentials: 'same-origin',
        });
        if (!res.ok) {
          return null;
        }
        const data = await res.json();
        if (!data || !data.ok || !Array.isArray(data.ids)) {
          return null;
        }
        return normalizeIds(data.ids);
      } catch (e) {
        return null;
      }
    }

    function showToast(message) {
      if (!toastEl) {
        return;
      }
      toastEl.textContent = message;
      toastEl.hidden = false;
      if (toastTimer) {
        window.clearTimeout(toastTimer);
      }
      toastTimer = window.setTimeout(() => {
        toastEl.hidden = true;
      }, 2200);
    }

    async function copyText(text) {
      if (navigator.clipboard && navigator.clipboard.writeText) {
        await navigator.clipboard.writeText(text);
        return;
      }
      const area = document.createElement('textarea');
      area.value = text;
      area.setAttribute('readonly', '');
      area.style.position = 'fixed';
      area.style.left = '-9999px';
      document.body.appendChild(area);
      area.select();
      document.execCommand('copy');
      document.body.removeChild(area);
    }

    function productUrl(id) {
      const url = new URL(window.location.href);
      url.searchParams.delete('fav');
      url.hash = 'p-' + id;
      return url.toString();
    }

    function favoritesShareUrl(ids) {
      const url = new URL(window.location.origin + window.location.pathname);
      if (ids.length) {
        url.searchParams.set('fav', ids.join(','));
      }
      url.hash = '';
      return url.toString();
    }

    function syncButtons(ids) {
      const set = new Set(ids);
      document.querySelectorAll('.product-item[data-product-id]').forEach((card) => {
        const id = Number(card.getAttribute('data-product-id') || 0);
        const btn = card.querySelector('[data-fav-toggle]');
        const active = set.has(id);
        card.classList.toggle('is-faved', active);
        if (!btn) {
          return;
        }
        btn.classList.toggle('is-active', active);
        btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        const label = active ? t.remove : t.add;
        btn.setAttribute('aria-label', label);
        btn.title = label;
      });

      if (countEl && toggleBtn) {
        const n = ids.length;
        countEl.textContent = String(n);
        countEl.hidden = n === 0;
        toggleBtn.classList.toggle('has-items', n > 0);
      }
    }

    function renderPanel(ids) {
      if (!listEl || !emptyEl) {
        return;
      }
      listEl.innerHTML = '';
      const seen = new Set();
      ids.forEach((id) => {
        if (seen.has(id)) {
          return;
        }
        seen.add(id);
        const card = document.getElementById('p-' + id)
          || document.querySelector('.product-item[data-product-id="' + id + '"]');
        if (!card) {
          return;
        }
        const name = card.getAttribute('data-product-name') || ('#' + id);
        const price = card.getAttribute('data-product-price') || '';
        const li = document.createElement('li');
        li.innerHTML = '<a href="#p-' + id + '" data-fav-jump="' + id + '"></a>'
          + '<span class="fav-item-price"></span>'
          + '<button type="button" class="fav-item-remove" data-fav-remove="' + id + '" aria-label="' + t.remove + '">×</button>';
        li.querySelector('a').textContent = name;
        li.querySelector('.fav-item-price').textContent = price;
        listEl.appendChild(li);
      });

      const hasVisible = listEl.children.length > 0;
      emptyEl.hidden = hasVisible;
      listEl.hidden = !hasVisible;
    }

    function refresh() {
      const ids = readFavs();
      syncButtons(ids);
      renderPanel(ids);
      return ids;
    }

    function setOpen(open) {
      if (!panel || !backdrop || !toggleBtn) {
        return;
      }
      panel.hidden = !open;
      backdrop.hidden = !open;
      toggleBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.classList.toggle('fav-panel-open', open);
      if (open) {
        renderPanel(readFavs());
      }
    }

    function toggleId(id) {
      let ids = readFavs();
      if (ids.includes(id)) {
        ids = ids.filter((x) => x !== id);
      } else {
        ids = ids.concat([id]);
      }
      writeFavs(ids);
      refresh();
    }

    function flashAndScroll(id) {
      const target = document.getElementById('p-' + id);
      if (!target) {
        return;
      }
      softScrollTo(target);
      target.classList.remove('is-flash');
      void target.offsetWidth;
      target.classList.add('is-flash');
      window.setTimeout(() => target.classList.remove('is-flash'), 1600);
    }

    async function shareProduct(card) {
      const id = Number(card.getAttribute('data-product-id') || 0);
      if (id <= 0) {
        return;
      }
      const name = card.getAttribute('data-product-name') || '';
      const price = card.getAttribute('data-product-price') || '';
      const link = productUrl(id);
      const text = name + (price ? ' — ' + price : '') + '\n' + t.restaurant;

      if (navigator.share) {
        try {
          await navigator.share({ title: name || t.restaurant, text, url: link });
          return;
        } catch (e) {
          if (e && e.name === 'AbortError') {
            return;
          }
        }
      }

      try {
        await copyText(link);
        showToast(t.copied);
      } catch (e) {
        // ignore
      }
    }

    async function shareList() {
      const ids = readFavs();
      const link = favoritesShareUrl(ids);
      const names = ids.map((id) => {
        const card = document.querySelector('.product-item[data-product-id="' + id + '"]');
        return card ? card.getAttribute('data-product-name') : null;
      }).filter(Boolean);
      const text = (names.length ? names.join(', ') + '\n' : '') + t.restaurant;

      if (navigator.share) {
        try {
          await navigator.share({ title: t.restaurant, text, url: link });
          return;
        } catch (e) {
          if (e && e.name === 'AbortError') {
            return;
          }
        }
      }

      try {
        await copyText(link);
        showToast(t.listCopied);
      } catch (e) {
        // ignore
      }
    }

    // Import shared favorites from ?fav=1,2,3
    const params = new URLSearchParams(window.location.search);
    const shared = params.get('fav');
    let openAfterImport = false;
    if (shared) {
      const imported = shared.split(',')
        .map((x) => Number(x.trim()))
        .filter((id) => id > 0);
      if (imported.length) {
        writeFavs(mergeIds(readFavs(), imported));
        params.delete('fav');
        const clean = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        if (history.replaceState) {
          history.replaceState(null, '', clean);
        }
        openAfterImport = true;
      }
    }

    refresh();

    // Merge server list (cookie guest token) with local cache
    pullFromServer().then((serverIds) => {
      if (serverIds === null) {
        // Push local cache so first visit creates DB rows
        scheduleServerSync(readFavs());
        return;
      }
      const merged = mergeIds(serverIds, readFavs());
      writeFavs(merged, true);
      refresh();
      if (openAfterImport) {
        window.setTimeout(() => setOpen(true), 200);
      }
    });

    if (openAfterImport && !favEndpoint) {
      window.setTimeout(() => setOpen(true), 350);
    }

    document.addEventListener('click', (event) => {
      const favBtn = event.target.closest('[data-fav-toggle]');
      if (favBtn) {
        const card = favBtn.closest('.product-item');
        const id = card ? Number(card.getAttribute('data-product-id') || 0) : 0;
        if (id > 0) {
          toggleId(id);
        }
        return;
      }

      const shareBtn = event.target.closest('[data-share-product]');
      if (shareBtn) {
        const card = shareBtn.closest('.product-item');
        if (card) {
          shareProduct(card);
        }
        return;
      }

      const removeBtn = event.target.closest('[data-fav-remove]');
      if (removeBtn) {
        const id = Number(removeBtn.getAttribute('data-fav-remove') || 0);
        if (id > 0) {
          toggleId(id);
        }
        return;
      }

      const jump = event.target.closest('[data-fav-jump]');
      if (jump) {
        event.preventDefault();
        const id = Number(jump.getAttribute('data-fav-jump') || 0);
        setOpen(false);
        if (id > 0) {
          flashAndScroll(id);
          if (history.replaceState) {
            history.replaceState(null, '', '#p-' + id);
          }
        }
      }
    });

    if (toggleBtn) {
      toggleBtn.addEventListener('click', () => {
        setOpen(panel && panel.hidden);
      });
    }

    const closeBtn = document.getElementById('favClose');
    if (closeBtn) {
      closeBtn.addEventListener('click', () => setOpen(false));
    }
    if (backdrop) {
      backdrop.addEventListener('click', () => setOpen(false));
    }

    const shareListBtn = document.getElementById('favShareList');
    if (shareListBtn) {
      shareListBtn.addEventListener('click', shareList);
    }

    const clearBtn = document.getElementById('favClear');
    if (clearBtn) {
      clearBtn.addEventListener('click', () => {
        writeFavs([]);
        refresh();
      });
    }

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && panel && !panel.hidden) {
        setOpen(false);
      }
    });

    const hash = window.location.hash || '';
    const match = hash.match(/^#p-(\d+)$/);
    if (match) {
      const id = Number(match[1]);
      requestAnimationFrame(() => flashAndScroll(id));
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    setTheme(getTheme());

    const toggle = document.getElementById('themeToggle');
    if (toggle) {
      toggle.addEventListener('click', toggleTheme);
    }

    const topbar = document.getElementById('menuTopbar');
    if (topbar) {
      const onScroll = () => {
        topbar.classList.toggle('is-scrolled', window.scrollY > 24);
      };
      onScroll();
      window.addEventListener('scroll', onScroll, { passive: true });
    }

    document.querySelectorAll('.product-item').forEach((el, i) => {
      el.style.animationDelay = `${Math.min(i * 35, 280)}ms`;
    });

    initSoftScroll();
    initViewTracking();
    initHeroVideo();
    initFavorites();

    const activeChip = document.querySelector('.cat-chip.is-active');
    if (activeChip && activeChip.parentElement) {
      activeChip.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
    }
  });
})();
