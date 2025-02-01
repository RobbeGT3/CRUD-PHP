function openPopup(persoonnummer) {
    currentPersoonnummer = persoonnummer;
    document.getElementById('popup').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
  };

  
function closePopup() {
    document.getElementById('popup').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
  };

function deleteRecord() {
  if (currentPersoonnummer) {
      fetch('delete_user.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
          },
          body: JSON.stringify({ persoonnummer: currentPersoonnummer }),
      })
      .then(response => response.text()) 
      .then(data => {
          alert(data); // Weergeeft server response aan
          closePopup(); // Sluit the popup
          location.reload(); // Refreshed de pagina
      })
      .catch(error => console.error('Error:', error));
  }
}