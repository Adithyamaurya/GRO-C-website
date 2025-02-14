// Function to load header and footer
function loadHeaderFooter() {
  // Load header
  fetch('header.html')
    .then(response => response.text())
    .then(data => {
      document.body.insertAdjacentHTML('afterbegin', data);
    })
    .catch(error => console.error('Error loading header:', error));

  // Load footer
  fetch('footer.html')
    .then(response => response.text())
    .then(data => {
      document.body.insertAdjacentHTML('beforeend', data);
    })
    .catch(error => console.error('Error loading footer:', error));
}

// Call the function to load header and footer
loadHeaderFooter();
