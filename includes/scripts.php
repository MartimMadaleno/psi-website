<?php 
?>
<script>
// Modal Image Gallery
function onClick(element) {
  document.getElementById("img01").src = element.src;
  document.getElementById("modal01").style.display = "block";
  var captionText = document.getElementById("caption");
  captionText.innerHTML = element.alt;
}


// Toggle between showing and hiding the sidebar when clicking the menu icon
var menuLateral = document.getElementById("menuLateral");

function w3_open() {
  if (menuLateral.style.display === 'block') {
    menuLateral.style.display = 'none';
  } else {
    menuLateral.style.display = 'block';
  }
}

// Close the sidebar with the close button
function w3_close() {
    menuLateral.style.display = "none";
}
</script>
