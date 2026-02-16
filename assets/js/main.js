document.addEventListener('DOMContentLoaded', () => {
  const slides = Array.from(document.querySelectorAll('.hero-slide'));
  let index = 0;

  if (slides.length) {
    slides[0].classList.add('active');
    setInterval(() => {
      slides[index].classList.remove('active');
      index = (index + 1) % slides.length;
      slides[index].classList.add('active');
    }, 5000);
  }

  const navToggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.nav');
  if (navToggle && nav) {
    navToggle.addEventListener('click', () => {
      nav.classList.toggle('open');
    });
  }

  const currencyBtn = document.querySelector('.currency-btn');
  const currencyModal = document.getElementById('currency-modal');
  if (currencyBtn && currencyModal) {
    const closeModal = () => {
      currencyModal.classList.remove('open');
      currencyModal.setAttribute('aria-hidden', 'true');
    };

    currencyBtn.addEventListener('click', () => {
      currencyModal.classList.add('open');
      currencyModal.setAttribute('aria-hidden', 'false');
    });

    currencyModal.addEventListener('click', (event) => {
      if (event.target.dataset.close === 'true') {
        closeModal();
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        closeModal();
      }
    });
  }

  const revealItems = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && revealItems.length) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('in-view');
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15, rootMargin: '0px 0px -10% 0px' }
    );
    revealItems.forEach((item) => observer.observe(item));
  } else {
    revealItems.forEach((item) => item.classList.add('in-view'));
  }

  const userToggle = document.querySelector('.user-toggle');
  const userDropdown = document.querySelector('.user-dropdown');
  if (userToggle && userDropdown) {
    userToggle.addEventListener('click', () => {
      const isOpen = userDropdown.classList.toggle('open');
      userToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', (event) => {
      if (!userToggle.contains(event.target) && !userDropdown.contains(event.target)) {
        userDropdown.classList.remove('open');
        userToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }
});
