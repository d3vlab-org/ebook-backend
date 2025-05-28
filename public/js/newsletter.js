document.getElementById("newsletter-form").addEventListener("submit", function (e) {
    e.preventDefault();
    const email = document.getElementById("email").value;

    fetch("/subscribe", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ email })
    })
        .then(response => {
            if (!response.ok) throw new Error("Błąd zapisu.");
            return response.json();
        })
        .then(data => {
            document.getElementById("form-message").textContent = "✅ Dziękujemy za zapis!";
            document.getElementById("email").value = "";
        })
        .catch(error => {
            document.getElementById("form-message").textContent = "❌ Coś poszło nie tak.";
            console.error(error);
        });
});