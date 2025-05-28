document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("newsletter-form");
  if (!form) {
    console.warn("Formularz #newsletter-form nie został znaleziony.");
    return;
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const emailInput = document.getElementById("email");
    const messageBox = document.getElementById("form-message");

    if (!emailInput) return;

    const email = emailInput.value;

    fetch("/subscribe", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ email }),
    })
      .then((response) => {
        if (!response.ok) throw new Error("Błąd zapisu.");
        return response.json();
      })
      .then((data) => {
        messageBox.textContent = "✅ Dziękujemy za zapis!";
        emailInput.value = "";
      })
      .catch((error) => {
        messageBox.textContent = "❌ Coś poszło nie tak.";
        console.error(error);
      });
  });
});