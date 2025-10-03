const container = document.querySelector(".container-login");
const registerBtn = document.querySelector("#register");
const loginBtn = document.querySelector("#login");

registerBtn.addEventListener("click", () => {
	container.classList.add("active");
});

loginBtn.addEventListener("click", () => {
	container.classList.remove("active");
});

// Password Strength Checker
let pswrd = document.getElementById("pswrd");
let toggleBtn = document.getElementById("toggleBtn");

let lowerCase = document.getElementById("lower");
let upperCase = document.getElementById("upper");
let digit = document.getElementById("number");
let specialChar = document.getElementById("special");
let minLength = document.getElementById("length");

let button = document.getElementById("ok");

function checkPassword(data) {
	const lower = new RegExp("(?=.*[a-z])");
	const upper = new RegExp("(?=.*[A-Z])");
	const number = new RegExp("(?=.*[0-9])");
	const spécial = new RegExp("(?=.*[!@#$%^&*?])");
	const length = new RegExp("(?=.{8,})");

	// Vérification de la présence de minuscules
	if (lower.test(data)) {
		lowerCase.classList.add("valid");
	} else {
		lowerCase.classList.remove("valid");
	}
	// Vérification de la présence de majuscules
	if (upper.test(data)) {
		upperCase.classList.add("valid");
	} else {
		upperCase.classList.remove("valid");
	}
	// Vérification de la présence de chiffres
	if (number.test(data)) {
		digit.classList.add("valid");
	} else {
		digit.classList.remove("valid");
	}
	// Vérification de la présence de caractères spéciaux
	if (spécial.test(data)) {
		specialChar.classList.add("valid");
	} else {
		specialChar.classList.remove("valid");
	}
	// Vérification de la longueur minimale
	if (length.test(data)) {
		minLength.classList.add("valid");
	} else {
		minLength.classList.remove("valid");
	}
	if (lower.test(data) && upper.test(data) && number.test(data) && spécial.test(data) && length.test(data)) {
		button.disabled = false;
	} else {
		button.disabled = true;
	}
}

toggleBtn.onclick = function () {
	if (pswrd.type === "password") {
		pswrd.setAttribute("type", "text");
		toggleBtn.classList.add("hide");
	} else {
		pswrd.setAttribute("type", "password");
		toggleBtn.classList.remove("hide");
	}
};
