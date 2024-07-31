# SpaceWise

## Purpose:
This is a basic event management system built with PHP, Javascript, and a MySQL Database.

## Requirements:
- XAMPP

## Things to Improve:
- color differentiation
- user should be able to download their chart
- better GPT suggestions

- Improve the front end design of the site
- Users being able to add multiple events and have AI determine where they should go to avoid conflicts
- After adding a building or room, remove window pop-up message and instead display a table and update it every time something is added
- When a user searches for events, display the results in calendar form instead of a table
- Error handling. Don’t let user search or submit on empty/invalid input. 
- Display an “Upcoming events” calendar on the dashboard
- When a building or room is created, show a table of “Buildings added” or “Rooms added” instead of an alert message

## Demo

<div style="max-width:600px; margin:auto;">
  <div style="display:none;">
    <img src="./images/image1.png" style="width:100%;">
  </div>
  <div style="display:none;">
    <img src="./images/image2.png" style="width:100%;">
  </div>
  <div style="display:none;">
    <img src="./images/image3.png" style="width:100%;">
  </div>

  <div style="text-align:center;">
    <span onclick="currentSlide(1)" style="cursor:pointer;">&#10094;</span>
    <span onclick="currentSlide(2)" style="cursor:pointer;">&#10095;</span>
  </div>
</div>

<script>
  var slideIndex = 0;
  showSlides();

  function showSlides() {
    var i;
    var slides = document.querySelectorAll("div > div");
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}
    slides[slideIndex-1].style.display = "block";
    setTimeout(showSlides, 3000);
  }

  function currentSlide(n) {
    slideIndex = n-1;
    showSlides();
  }
</script>

