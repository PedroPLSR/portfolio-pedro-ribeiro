import '../styles/main.css';

const APPEARANCE_KEY = 'pedro-ribeiro-appearance';

const readSavedAppearance = () => {
  try {
    const saved = localStorage.getItem(APPEARANCE_KEY);
    return saved === 'light' || saved === 'dark' ? saved : '';
  } catch {
    return '';
  }
};

const applyAppearance = (theme, persist) => {
  const root = document.documentElement;
  root.setAttribute('data-theme', theme);
  root.style.colorScheme = theme;
  if (persist) {
    try {
      localStorage.setItem(APPEARANCE_KEY, theme);
    } catch {
      /* private mode */
    }
  }
  document.querySelectorAll('[data-theme-set]').forEach((button) => {
    button.setAttribute('aria-pressed', button.getAttribute('data-theme-set') === theme ? 'true' : 'false');
  });
};

const resolvedAppearance = () => {
  const saved = readSavedAppearance();
  if (saved) return saved;
  return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

applyAppearance(resolvedAppearance(), false);

document.querySelectorAll('[data-theme-set]').forEach((button) => {
  button.addEventListener('click', () => {
    const theme = button.getAttribute('data-theme-set');
    if (theme === 'light' || theme === 'dark') {
      applyAppearance(theme, true);
    }
  });
});

const schemeQuery = window.matchMedia('(prefers-color-scheme: dark)');
const onSchemeChange = (event) => {
  if (readSavedAppearance()) return;
  applyAppearance(event.matches ? 'dark' : 'light', false);
};
if (typeof schemeQuery.addEventListener === 'function') {
  schemeQuery.addEventListener('change', onSchemeChange);
} else if (typeof schemeQuery.addListener === 'function') {
  schemeQuery.addListener(onSchemeChange);
}

const nav = document.querySelector('[data-site-nav]');

const onScroll = () => {
  if (!nav) return;
  nav.classList.toggle('is-scrolled', window.scrollY > 12);
};

onScroll();
window.addEventListener('scroll', onScroll, { passive: true });

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (!prefersReducedMotion && 'IntersectionObserver' in window) {
  const reveals = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver(
    (entries) => {
      for (const entry of entries) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      }
    },
    { rootMargin: '0px 0px -8% 0px', threshold: 0.12 },
  );

  reveals.forEach((el) => observer.observe(el));
} else {
  document.querySelectorAll('.reveal').forEach((el) => el.classList.add('is-visible'));
}
