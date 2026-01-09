(function () {
	var container = document.getElementById("catBooks");
	var btnPrev = document.getElementById("catPrev");
	var btnNext = document.getElementById("catNext");
	if (!container || !btnPrev || !btnNext) return;

	var books = JSON.parse(container.getAttribute("data-books"));
	var cards = container.querySelectorAll("a[data-slot]");
	var start = 0;

	function show() {
		for (var i = 0; i < 4; i++) {
			var index = start + i;
			if (index >= books.length) index = index - books.length;

			var book = books[index];
			var card = cards[i];

			card.href = "../views/book-detail.php?id=" + book.id_livre;
			card.querySelector("img").src = "/assets/bo/img/" + book.livre_couverture;
			card.querySelector(".title").textContent = book.livre_titre;
			card.querySelector(".author").textContent = book.auteur_nom;
		}
	}

	btnNext.addEventListener("click", function () {
		start++;
		if (start >= books.length) start = 0;
		show();
	});

	btnPrev.addEventListener("click", function () {
		start--;
		if (start < 0) start = books.length - 1;
		show();
	});

	show();
})();


