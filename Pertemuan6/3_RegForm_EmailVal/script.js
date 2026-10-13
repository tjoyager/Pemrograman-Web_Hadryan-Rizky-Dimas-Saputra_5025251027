document.addEventListener("DOMContentLoaded", function () {
  const regForm = document.getElementById("registrationForm");

  if (regForm) {
    const nameInput = document.getElementById("name");
    const addressInput = document.getElementById("address");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const courseSelect = document.getElementById("course");
    const collegeTextarea = document.getElementById("college");
    const agreeCheckbox = document.getElementById("agree");

    const nameError = document.getElementById("nameError");
    const addressError = document.getElementById("addressError");
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");
    const courseError = document.getElementById("courseError");
    const collegeError = document.getElementById("collegeError");
    const agreeError = document.getElementById("agreeError");

    function resetErrors() {
      const errorSpans = regForm.querySelectorAll(".error-message");
      errorSpans.forEach(function (span) {
        span.textContent = "";
      });
    }

    regForm.addEventListener("reset", function () {
      resetErrors();
    });

    regForm.addEventListener("submit", function (e) {
      e.preventDefault();
      resetErrors();

      let isValid = true;

      const nameVal = nameInput.value.trim();
      if (nameVal === "") {
        nameError.textContent = "Name cannot be empty.";
        isValid = false;
      } else if (/\d/.test(nameVal)) {
        nameError.textContent = "Name cannot contain numbers.";
        isValid = false;
      }

      const addressVal = addressInput.value.trim();
      if (addressVal === "") {
        addressError.textContent = "Address cannot be empty.";
        isValid = false;
      }

      const emailVal = emailInput.value.trim();
      if (emailVal === "") {
        emailError.textContent = "E-mail Address cannot be empty.";
        isValid = false;
      } else if (!emailVal.includes("@") || !emailVal.includes(".")) {
        emailError.textContent = 'E-mail must contain "@" and "."';
        isValid = false;
      }

      const passwordVal = passwordInput.value;
      if (passwordVal === "") {
        passwordError.textContent = "Password cannot be empty.";
        isValid = false;
      } else if (passwordVal.length < 6) {
        passwordError.textContent = "Password must be at least 6 characters.";
        isValid = false;
      }

      const courseVal = courseSelect.value;
      if (!courseVal || courseVal === "" || courseVal === "Select Course") {
        courseError.textContent = "Please select a course.";
        isValid = false;
      }

      const collegeVal = collegeTextarea.value.trim();
      if (collegeVal === "") {
        collegeError.textContent = "College Name cannot be empty.";
        isValid = false;
      }

      if (!agreeCheckbox.checked) {
        agreeError.textContent = "You must agree to the above information.";
        isValid = false;
      }

      if (isValid) {
        window.location.href = "email-validation.html";
      }
    });
  }

  const emailForm = document.getElementById("emailValidationForm");

  if (emailForm) {
    const validationEmailInput = document.getElementById("validationEmail");
    const validationEmailError = document.getElementById("validationEmailError");

    emailForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const emailVal = validationEmailInput.value.trim();
      const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

      if (!emailRegex.test(emailVal)) {
        validationEmailError.textContent = "Please enter a valid email address.";
        validationEmailError.style.display = "block";
      } else {
        validationEmailError.style.display = "none";
        alert("Email is valid! Form Submitted.");
        emailForm.reset();
      }
    });
  }
});
