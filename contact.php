<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRO-C - Contact</title>
  <link rel="stylesheet" href="css/main.css">
  <script defer src="main.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="search.js"></script>
 
  <style>
   
    /* Contact Section */
    @keyframes fadeIn {
     from {
       opacity: 0;
     }
     to {
       opacity: 1;
     }
    }
    .contact {
      animation: fadeIn 0.5s ease-in-out;
      padding: 40px 0;
    }

    .contact h3 {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 24px;
    }

    .contact-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      align-items: center;
    }

    .contact-image img {
      width: 100%;
      height: auto;
      border-radius: 8px;
    }

    .contact-form {
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 24px;
      width: 100%;
    }

    .contact-form h2 {
      font-size: 1.75rem;
      font-weight: bold;
      margin-bottom: 16px;
      color: #16a34a;
      text-align: center;
    }

    .contact-form label {
      display: block;
      font-weight: 500;
      margin-bottom: 8px;
    }

    .contact-form input,
    .contact-form textarea {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #838586;
      border-radius: 4px;
      margin-bottom: 16px;
    }

    .contact-form textarea {
      resize: vertical;
      min-height: 120px;
    }

    .contact-form button {
      padding: 12px 24px;
      background-color: #16a34a;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .contact-form button:hover {
      background-color: #15803d;
    }

  </style>
</head>
<body style="background-color:rgb(199, 223, 206)  ;">
  

  <!-- Contact Section -->
  <section class="contact">
    <div class="container">
      <h3>Contact Us</h3>
      <div class="contact-content">
        <div class="contact-image">
          <img src="images/contact.png" alt="Contact Us" />
        </div>
        <div class="contact-form">
          <h2>Get in Touch</h2>
            <form action="https://formspree.io/f/xwpvjyob" method="POST" id="contactForm">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required />

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required />

            <label for="message">Message</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Send Message</button>
            </form>
        </div>
      </div>
    </div>
  </section>

  <script>
    // Contact Form Submission
    const contactForm = document.getElementById("contactForm");
    contactForm.addEventListener("submit", async (e) => {
      try {
        const response = await fetch(e.target.action, {
          method: 'POST',
          body: new FormData(e.target),
          headers: {
            'Accept': 'application/json'
          }
        });
        
        if (response.ok) {
          alert("Thank you for your message! We'll get back to you soon.");
          contactForm.reset();
        } else {
          alert("There was a problem submitting your form. Please try again.");
        }
      } catch (error) {
        alert("There was an error submitting your form. Please check your connection and try again.");
      }
    });


  </script>
   <?php include 'footer.php'; ?>
</body>
</html>
