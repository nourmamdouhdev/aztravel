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
});
