document.addEventListener('DOMContentLoaded', () => {
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

  const activeChip = document.querySelector('.cat-chip.is-active');
  if (activeChip && activeChip.parentElement) {
    activeChip.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
  }
});
