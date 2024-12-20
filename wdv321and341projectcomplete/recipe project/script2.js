
        // Populate recipe types dropdown
        const recipeTypes = ["drinks", "meals", "snacks", "desserts"];
        function populateRecipeTypes() {
            const select = document.getElementById('recipeType');
            recipeTypes.forEach(type => {
                let option = document.createElement('option');
                option.value = type;
                option.textContent = type.charAt(0).toUpperCase() + type.slice(1);  // Capitalize first letter
                select.appendChild(option);
            });
        }
         window.onload = populateRecipeTypes();  // Call the function to populate recipe types on page load
        // Recipe form submission handling
        document.getElementById('recipe-form').addEventListener('submit', function(event) {
            event.preventDefault();

            // Get form data for static fields
            const name = document.getElementById('recipeName').value;
            const chefName = document.getElementById('Chef-name').value;       // Chef name
            const recipeType = document.getElementById('recipeType').value;    // Recipe type (dropdown)
           // const image = document.getElementById('recipe-image').value;
           // Collect form data for static fields
                const imageInput = document.getElementById('recipe-image-upload');
               let imageBase64 = '';
               if (imageInput.files.length > 0) {
               const file = imageInput.files[0];
                const reader = new FileReader();
              reader.onloadend = function() {
                  imageBase64 = reader.result; 
                   // This is a Base64 string
              // Now you can save the Base64 image along with other recipe data
     const newRecipe = {
            name,
            chefName,
            recipeType,
            image: imageBase64, // Store Base64 image
            servings,
            prepTime,
            difficulty,
            ingredients,
            instructions
        };

        // Save to localStorage (or upload to server)
        const recipes = JSON.parse(localStorage.getItem('recipes')) || [];
        recipes.push(newRecipe);
        localStorage.setItem('recipes', JSON.stringify(recipes));
    };
    reader.readAsDataURL(file);  // Convert file to Base64 string
}
           
           const servings = parseInt(document.getElementById('servings').value, 10);
            const prepTime = document.getElementById('prep-time').value;
            const difficulty = document.querySelector('input[name="difficulty"]:checked')?.value;

            // Check if recipe name already exists in localStorage
            const existingRecipes = JSON.parse(localStorage.getItem('recipes')) || [];
            const isDuplicate = existingRecipes.some(recipe => recipe.name.toLowerCase() === name.toLowerCase());

            if (isDuplicate) {
                alert('A recipe with this name already exists. Please choose a different name.');
                return; // Exit the function to prevent form submission
            }

            // Collect ingredients
            const ingredients = [];
            document.querySelectorAll('.ingredient').forEach((ingredientDiv, index) => {
                const ingredientName = document.getElementById(`ingredient-name-${index + 1}`).value;
                const ingredientAmount = document.getElementById(`ingredient-amount-${index + 1}`).value;
                ingredients.push({ name: ingredientName, amount: ingredientAmount });
            });

            // Collect instructions
            const instructions = [];
            document.querySelectorAll('.instructions').forEach((instructionDiv, index) => {
                const step = document.getElementById(`instructions-step-${index + 1}`).value;
                instructions.push(step);
            });

            // Create new recipe object
            const newRecipe = {
                name,
               chefName,
               recipeType,
               image,
               servings,
                prepTime,
                difficulty,
                ingredients,
                instructions
            };

            // Get existing recipes from localStorage
            const recipes = JSON.parse(localStorage.getItem('recipes')) || [];
            recipes.push(newRecipe);

            // Save updated recipes back to localStorage
            localStorage.setItem('recipes', JSON.stringify(recipes));
        
            reader.readAsDataURL(file);  // Convert file to Base64 string
    
        
             console.log('Recipe saved:', newRecipe);
           } );

        // Add ingredient input dynamically
        let ingredientCount = 1;
        document.getElementById('add-another-ingredient').addEventListener('click', function() {
            ingredientCount++;
            const newIngredientInputLine = `
                <div class="ingredient" id="ingredient-${ingredientCount}">
                    <label for="ingredient-name-${ingredientCount}">Ingredient ${ingredientCount} Name:</label>
                    <input type="text" name="ingredient-name-${ingredientCount}" id="ingredient-name-${ingredientCount}" required>
                    <label for="ingredient-amount-${ingredientCount}">Amount:</label>
                    <input type="text" name="ingredient-amount-${ingredientCount}" id="ingredient-amount-${ingredientCount}" required>
                </div>
            `;
            document.getElementById('ingredients-container').insertAdjacentHTML('beforeend', newIngredientInputLine);
        });

        // Add instruction input dynamically
        let instructionCount = 1;
        document.getElementById('add-another-step').addEventListener('click', function() {
            instructionCount++;
            const newInstructionInputLine = `
                <div class="instructions" id="instructions-step-${instructionCount}">
                    <label for="instructions-step-${instructionCount}">Step ${instructionCount}:</label>
                    <input type="text" name="instructions-step-${instructionCount}" id="instructions-step-${instructionCount}" required>
                </div>
            `;
            document.getElementById('instruction-steps').insertAdjacentHTML('beforeend', newInstructionInputLine);
        });

        // Image preview logic
       // document.getElementById('recipe-image').addEventListener('input', function(event) {
         //   const imageUrl = event.target.value;
        //    document.getElementById('image-preview').src = imageUrl;
       // });

// Preview image before submission (if file is selected)
document.getElementById('recipe-image-upload').addEventListener('change', function(event) {
    const file = event.target.files[0]; // Get the selected file
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Show the preview image
            document.getElementById('image-preview').src = e.target.result;
        };
        reader.readAsDataURL(file); // Convert the file to a data URL (base64 encoded)
    }
});

        // Update the displayed difficulty when a selection is made
        function showSelectedDifficulty() {
            const selectedDifficulty = document.querySelector('input[name="difficulty"]:checked')?.value;
            document.getElementById('outputDifficulty').textContent = selectedDifficulty || 'None selected';
        }

        // Handle recipe type selection
        document.getElementById('recipeType').addEventListener('change', function() {
            const selectedType = this.value;
            document.getElementById('outputType').textContent = selectedType || 'None selected';
        });
   
        document.getElementById('recipe-form').reset();
    document.getElementById('outputType').textContent = '';
    document.getElementById('outputDifficulty').textContent = '';
    document.getElementById('image-preview').src = '';
   
