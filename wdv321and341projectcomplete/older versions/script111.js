 // Utility Functions
 function displayFriendlyDate() {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  document.getElementById('friendlyDate').textContent = new Date().toLocaleDateString('en-US', options);
}

function searchRecipes() {
  const query = document.getElementById('search-input').value.toLowerCase();
  document.querySelectorAll('.recipe-item').forEach(item => {
      const title = item.querySelector('.recipe-title').innerText.toLowerCase();
      item.style.display = title.includes(query) ? '' : 'none';
  });
}

function viewRecipe(index) {
  const recipes = JSON.parse(localStorage.getItem('recipes')) || [];
  const recipe = recipes[index];
  const recipeDetail = document.getElementById('recipe-detail');

  window.onload = function() {
      let recipes = JSON.parse(localStorage.getItem('recipes')) || [];
      const recipeList = document.getElementById('recipe-list');
      recipes.sort((a, b) => a.name.localeCompare(b.name));
      localStorage.setItem('recipes', JSON.stringify(recipes));
      
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
  
       function populatePublicRecipes() {
          const publicRecipes = JSON.parse(localStorage.getItem('publicRecipes')) || [];
          const publicRecipesList = document.getElementById('public-recipes-list');
          if (publicRecipes.length === 0) {
              publicRecipesList.innerHTML = '<p>No public recipes available.</p>';
          } else {
              publicRecipes.forEach(recipe => {
  const recipeElement = document.createElement('div');
  recipeElement.innerHTML = `<h4>${recipe.name}</h4>
                             <img src="${recipe.image}" alt="${recipe.name}" width="100">`; 
  publicRecipesList.appendChild(recipeElement);
});

          }
      }
  
      // Populate data when window is loaded
     
      populatePublicRecipes();
  };  
          
   function toggleRecipes(type) {
      // Hide all sections
      document.getElementById('favorites-section').style.display = 'none';
      document.getElementById('public-recipes-section').style.display = 'none';
      document.getElementById('private-recipes-section').style.display = 'none';
  
      // Show the selected section
       if (type === 'public') {
          document.getElementById('public-recipes-section').style.display = 'block';
      } else if (type === 'private') {
          document.getElementById('private-recipes-section').style.display = 'block';
      }
  }
 
  recipeDetail.innerHTML = `
  <h2>${recipe.name}</h2>
 <h5>
  <img src="${recipe.image}" alt="${recipe.name}" width="200">
  <p><strong>Servings:</strong> ${recipe.servings}</p>
  <p><strong>Preparation Time:</strong> ${recipe.prepTime} minutes</p>
  <p><strong>Difficulty:</strong> ${recipe.difficulty}</p>
  </h5>
  <h3>Ingredients</h3>
  <ul>
      ${recipe.ingredients.map(ingredient => `
          <li>${ingredient.quantity || 1} ${ingredient.name}</li>`).join('')}
  </ul>
  <h3>Instructions</h3>
  <ol>
      ${recipe.instructions.map(step => `<li>${step}</li>`).join('')}
  </ol>
  <button onclick="goBack()">Close</button>
`;
recipeDetail.style.display = 'block';
}
 
function goBack() {
  document.getElementById('recipe-detail').style.display = 'none';
}

function populateRecipes() {
  const recipes = JSON.parse(localStorage.getItem('recipes')) || [];
  const recipeList = document.getElementById('recipe-list');
  recipeList.innerHTML = recipes.map((recipe, index) => `
      <div class="recipe-item">
          <h3 class="recipe-title" onclick="viewRecipe(${index})">${recipe.name}</h3>
          <img src="${recipe.image}" alt="${recipe.name}" width="100">
      </div>
  `).join('');
}
document.addEventListener('DOMContentLoaded', () => {
  displayFriendlyDate();
  populateRecipes();
});

<div id="ingredientsModal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <h2>Ingredients</h2>

 <ul id="ingredientsList">
  ${recipe.ingredients.map(ingredient => {
    // Set a default quantity of 1 if not provided
    const baseQuantity = ingredient.quantity || 1;
    return `
      <li class="ingredient" data-base-quantity="${baseQuantity}">
        ${baseQuantity} ${ingredient.name}
      </li>
    `;
  }).join('')} 
</ul>
</div>
<h3>Instructions</h3>
<section>
  <ol>
    ${recipe.instructions && recipe.instructions.length > 0
      ? recipe.instructions.map(step => `<li>${step}</li>`).join('') // Map instructions to list items
      : '<li>No instructions provided.</li>'} <!-- Handle case when instructions are missing -->
  </ol>
</section>

<!-- Action Buttons -->
<button onclick="viewIngredients"> Ingredients</button>
<button onclick="viewIngredients">instructions</button>
<button onclick="editRecipe(${index})">Edit Recipe</button>
<button onclick="deleteRecipe(${index})">Delete Recipe</button>
<button onclick="goBack()">Close Recipe</button>
</div>

`;

 recipeDetail.style.display = 'block';
  document.getElementById('private-recipes-section').style.display = 'none';
  }

// Add event listener for serving selector
document.getElementById('servingSelector').addEventListener('change', function () {
  const scale = parseFloat(this.value) || 1;
  
  // Loop through all ingredient elements
  document.querySelectorAll('.ingredient').forEach(ingredient => {
      const baseQuantity = parseFloat(ingredient.dataset.baseQuantity) || 0;
      
      // Calculate the new quantity of ingredients
      const newQuantity = (baseQuantity * scale).toFixed(2);
      
      // Get the ingredient name (text after the quantity)
      const ingredientName = ingredient.textContent.split(' ').slice(1).join(' ');
      
      // Update ingredient with new quantity
      ingredient.textContent = `${newQuantity} ${ingredientName}`;
  });
});

 // Delete a recipe
function deleteRecipe(index) {
  if (confirm('Are you sure you want to delete this recipe?')) {
  let recipes = JSON.parse(localStorage.getItem('recipes')) || [];
  recipes.splice(index, 1); // Remove the recipe at the specified index
  localStorage.setItem('recipes', JSON.stringify(recipes));
  alert('Recipe deleted successfully!');
  goBack();
}
}
// Edit a recipe
function editRecipe(index) {
  let recipes = JSON.parse(localStorage.getItem('recipes')) || [];
  const recipe = recipes[index];
  window.location.href = `editRecipe.html?index=${index}&name=${encodeURIComponent(recipe.name)}`;
}

/ Go back to the recipe list
function goBack() {
  document.getElementById('recipe-detail').style.display = 'none';
  document.getElementById('private-recipes-section').style.display = 'block';
}

    function logoutbutton() {
  const logoutButton = document.getElementById('logout-button');
  if (logoutButton) {
      logoutButton.addEventListener('click', function() {
          window.location.href = 'cookit!index.html';
      });
  }
}
  
const friendlyDateElement = document.getElementById('friendlyDate');
  if (friendlyDateElement) {
      friendlyDateElement.innerText = formattedDate;
  } else {
      console.error("friendlyDate element not found!");
  }
}


document.getElementById("viewIngredients").addEventListener("click", () => {
    const modal = document.getElementById("ingredientsModal");
    const list = document.getElementById("ingredientsList");


// Populate ingredient list
list.innerHTML = "";
    ingredients.forEach(ingredient => {
      const li = document.createElement("li");
      li.textContent = ingredient;
      list.appendChild(li);
    });

// Show modal
modal.style.display = "block";
  });


// Close modal functionality
document.querySelector(".close-btn").addEventListener("click", () => {
    document.getElementById("ingredientsModal").style.display = "none";
  });


// Close modal when clicking outside of it
window.addEventListener("click", (event) => {
    const modal = document.getElementById("ingredientsModal");
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

  function showIngredientsModal() {
  const modal = document.getElementById("ingredientsModal");
  const ingredientsList = document.getElementById("ingredientsList");
  const recipes = JSON.parse(localStorage.getItem('recipes')) || [];
  const currentRecipeIndex =; /* Retrieve the current recipe index based on context */
  const recipe = recipes[currentRecipeIndex];

  // Populate the ingredients list
  ingredientsList.innerHTML = recipe.ingredients.map(ingredient => {
      const baseQuantity = ingredient.quantity || 1;
      return `<li>${baseQuantity} ${ingredient.name}</li>`;
  }).join('');

  // Display the modal
  modal.style.display = "block";
}

function closeIngredientsModal() {
  document.getElementById("ingredientsModal").style.display = "none";
}

// Close modal when clicking outside the modal content
window.onclick = function(event) {
  const modal = document.getElementById("ingredientsModal");
  if (event.target === modal) {
      closeIngredientsModal();
  }
};
localStorage.setItem("recipes", JSON.stringify(recipes));

// Render recipe list
function renderRecipeList() {
  const recipeList = document.getElementById("recipeList");
  const recipes = JSON.parse(localStorage.getItem("recipes")) || [];

  recipeList.innerHTML = "";  // Clear the list before adding new items

  recipes.forEach((recipe, index) => {
      const listItem = document.createElement("li");
      listItem.innerHTML = `
          <h3>${recipe.name}</h3>
          <button class="viewIngredients" data-index="${index}">View Ingredients</button>
      `;
      recipeList.appendChild(listItem);
  });

  attachButtonListeners();
}

// Attach event listeners to buttons
function attachButtonListeners() {
  const buttons = document.querySelectorAll(".viewIngredients");

  buttons.forEach(button => {
      button.addEventListener("click", function() {
          const index = this.getAttribute("data-index");
          showIngredientsModal(index);
      });
  });
}

// Show ingredients in the modal
function showIngredientsModal(recipeIndex) {
  const modal = document.getElementById("ingredientsModal");
  const ingredientsList = document.getElementById("ingredientsList");

  const recipes = JSON.parse(localStorage.getItem("recipes")) || [];
  const recipe = recipes[recipeIndex];

  if (recipe) {
      ingredientsList.innerHTML = recipe.ingredients.map(ingredient => 
          `<li>${ingredient.quantity || 1} ${ingredient.name}</li>`
      ).join('');
  } else {
      ingredientsList.innerHTML = "<li>No ingredients found.</li>";
  }

  modal.style.display = "block";
}

// Close the modal
document.getElementById("closeModal").addEventListener("click", function() {
  document.getElementById("ingredientsModal").style.display = "none";
});

// Initialize the recipe list
document.addEventListener("DOMContentLoaded", function() {
  renderRecipeList();
});


