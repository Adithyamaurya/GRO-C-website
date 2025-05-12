window.addEventListener('load', () => {
    const perfEntries = performance.getEntriesByType('navigation');
    if (perfEntries.length > 0) {
      const navTiming = perfEntries[0];
      console.log('Page load time:', navTiming.loadEventEnd - navTiming.startTime);
    }
  });
  
  // Add resource timing
  const resources = performance.getEntriesByType('resource');
  resources.forEach(resource => {
    console.log(`${resource.name} loaded in ${resource.duration}ms`);
  });

  

  // Add Intersection Observer for lazy loading
const lazyLoad = (elements) => {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const element = entry.target;
          if (element.dataset.src) {
            element.src = element.dataset.src;
          }
          if (element.dataset.srcset) {
            element.srcset = element.dataset.srcset;
          }
          observer.unobserve(element);
        }
      });
    });
  
    elements.forEach(element => observer.observe(element));
  };
  
  // Initialize lazy loading
  document.addEventListener('DOMContentLoaded', () => {
    const lazyElements = [
      ...document.querySelectorAll('img[data-src]'),
      ...document.querySelectorAll('iframe[data-src]')
    ];
    lazyLoad(lazyElements);
  });