const form = document.getElementById('newForm');
form.addEventListener('input', function(event) {
  // get the fields from the form
  const formData = new FormData(form);
  const serial_number = formData.get('serial_number');

  console.log(serial_number);
});

form.addEventListener('submit', function(event) {
  // If either the form is invalid or the passwords don't match, stop!
  if ( form.checkValidity() === false || (serial_number.length != 32) ) {
    event.preventDefault();
    event.stopPropagation();
  }

  form.classList.add('was-validated');
});