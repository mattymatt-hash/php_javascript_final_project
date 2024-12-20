
window.onload = function() {
    let recipes = JSON.parse(localStorage.getItem('recipes')) || [];
    const recipeList = document.getElementById('recipe-list');

    if (recipes.length === 0) {
        recipeList.innerHTML = '<p>No recipes available.</p>';
    } else {
        recipes.forEach((recipe, index) => {
            const recipeElement = document.createElement('div');
            recipeElement.innerHTML = `
                <h3 onclick="viewRecipe(${index})">${recipe.name}</h3>
                <img src="${recipe.image}" alt="${recipe.name}" width="100">
            `;
            recipeList.appendChild(recipeElement);
        });
    }
}

// View Recipe Details
function viewRecipe(index) {
    const recipes = JSON.parse(localStorage.getItem('recipes')) || [];
    const recipe = recipes[index];

    document.getElementById('recipe-name').textContent = recipe.name;
    document.getElementById('recipe-image').src = recipe.image;
    document.getElementById('servings-count').textContent = recipe.servings;
    document.getElementById('prep-time').textContent = recipe.prepTime;
    document.getElementById('difficulty').textContent = recipe.difficulty;

    // Update ingredients
    const ingredientsList = document.getElementById('ingredients-list');
    ingredientsList.innerHTML = '';
    recipe.ingredients.forEach(ingredient => {
        const li = document.createElement('li');
        li.textContent = `${ingredient.quantity} ${ingredient.name}`;
        ingredientsList.appendChild(li);
    });

    // Update instructions
    const instructionsList = document.getElementById('instructions-list');
    instructionsList.innerHTML = '';
    recipe.instructions.forEach(instruction => {
        const li = document.createElement('li');
        li.textContent = instruction;
        instructionsList.appendChild(li);
    });

    document.getElementById('recipe-detail').style.display = 'block';
    document.getElementById('recipe-list').style.display = 'none';
}

// Create a new recipe (this would be triggered by a form submission)
function createRecipe(event) {
    event.preventDefault();
    
    let ingredients = [];
    const ingredientInputs = document.querySelectorAll('.ingredient-input');
    ingredientInputs.forEach(input => {
        ingredients.push({
            name: input.querySelector('.ingredient-name').value,
            quantity: input.querySelector('.ingredient-quantity').value
        });
    });

    let instructions = [];
    const instructionInputs = document.querySelectorAll('.instruction-input');
    instructionInputs.forEach(input => {
        instructions.push(input.value);
    });

    const newRecipe = {
        name: document.getElementById('recipe-name-input').value,
        chefName: document.getElementById('chef-name-input').value,
        recipeType: document.getElementById('recipe-type-input').value,
        image: document.getElementById('image-input').value,
        servings: parseInt(document.getElementById('servings-input').value),
        prepTime: document.getElementById('prep-time-input').value,
        difficulty: document.getElementById('difficulty-input').value,
        ingredients: ingredients,
        instructions: instructions
    };

    let recipes = JSON.parse(localStorage.getItem('recipes')) || [];
    recipes.push(newRecipe);
    localStorage.setItem('recipes', JSON.stringify(recipes));

    alert('Recipe created successfully!');
    document.getElementById('recipe-form').reset();
}

// Change servings (half, normal, double)
function changeServings(size) {
    const recipes = JSON.parse(localStorage.getItem('recipes')) || [];
    const recipeName = document.getElementById('recipe-name').textContent;
    const recipe = recipes.find(r => r.name === recipeName);

    let factor;
    if (size === 'half') {
        factor = 0.5;
    } else if (size === 'double') {
        factor = 2;
    } else {
        factor = 1;
    }

    recipe.ingredients.forEach(ingredient => {
        ingredient.quantity = ingredient.baseQuantity * factor;
    });

    localStorage.setItem('recipes', JSON.stringify(recipes));

    viewRecipe(recipes.indexOf(recipe));
}

// Toggle ingredients or instructions visibility
function toggleSection(section) {
    const sectionElement = document.getElementById(`${section}-section`);
    sectionElement.style.display = sectionElement.style.display === 'none' ? 'block' : 'none';
}



// Go back to recipe list
function goBack() {
    document.getElementById('recipe-detail').style.display = 'none';
    document.getElementById('recipe-list').style.display = 'block';
}

// Search function for filtering recipes
function searchFunction() {
    const input = document.getElementById('search-input').value.toLowerCase();
    const recipes = JSON.parse(localStorage.getItem('recipes')) || [];
    const suggestions = recipes.filter(recipe => recipe.name.toLowerCase().includes(input));

    const suggestionsContainer = document.getElementById('suggestions');
    suggestionsContainer.innerHTML = suggestions.map(recipe => `
        <p onclick="viewRecipe(${recipes.indexOf(recipe)})">${recipe.name}</p>
    `).join('');
}