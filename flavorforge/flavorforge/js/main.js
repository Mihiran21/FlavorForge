document.addEventListener("DOMContentLoaded", function () {
    
    // 1. Dynamic Search & Category Filtering
    const searchInput = document.getElementById("recipeSearch");
    const categoryFilter = document.getElementById("categoryFilter");
    const recipeItems = document.querySelectorAll(".recipe-item");

    function filterRecipes() {
        if (!searchInput || !categoryFilter) return;

        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;

        recipeItems.forEach(item => {
            const title = item.querySelector(".recipe-title").textContent.toLowerCase();
            const ingredients = item.querySelector(".recipe-ingredients").textContent.toLowerCase();
            const category = item.getAttribute("data-category");

            const matchesSearch = title.includes(searchTerm) || ingredients.includes(searchTerm);
            const matchesCategory = (selectedCategory === "All" || category === selectedCategory);

            if (matchesSearch && matchesCategory) {
                item.style.display = "block";
            } else {
                item.style.display = "none";
            }
        });
    }

    if (searchInput && categoryFilter) {
        searchInput.addEventListener("input", filterRecipes);
        categoryFilter.addEventListener("change", filterRecipes);
    }

    // 2. Client-Side Form Validation (Contact & Registration)
    const formsToValidate = document.querySelectorAll("#registerForm, #contactForm");

    formsToValidate.forEach(form => {
        form.addEventListener("submit", function (e) {
            let isValid = true;
            const inputs = form.querySelectorAll("input[required], textarea[required]");

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add("is-invalid");
                    isValid = false;
                } else {
                    input.classList.remove("is-invalid");
                }

                if (input.type === "email" && input.value.trim() !== "") {
                    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
                    if (!input.value.match(emailPattern)) {
                        input.classList.add("is-invalid");
                        isValid = false;
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    });

    // 3. Smooth Scrolling for Anchor Links
    document.querySelectorAll('.smooth-scroll').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            document.querySelector(targetId).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});