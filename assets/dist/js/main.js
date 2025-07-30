    document.addEventListener("DOMContentLoaded", function() {
      const elements = document.querySelectorAll(".animate-on-scroll");
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add("animated");
          }
        });
      }, { threshold: 0.1 });

      elements.forEach(el => observer.observe(el));

      // Counter animation
      const counters = document.querySelectorAll(".stat-number");
      counters.forEach(counter => {
        const updateCount = () => {
          const target = +counter.getAttribute("data-count");
          const count = +counter.innerText;
          const increment = target / 200;
          if (count < target) {
            counter.innerText = Math.ceil(count + increment);
            setTimeout(updateCount, 10);
          } else {
            counter.innerText = target;
          }
        };
        updateCount();
      });
    });