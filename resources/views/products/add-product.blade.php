@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0">Add Product</h1>
                </div>
                <div class="card-body">

                    <!-- Create the product add form -->
                    <form method="POST" action="{{ route('products.store') }}">
                        @csrf

                        <!-- Basic product information -->
                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required minlength="3" maxlength="255">
                            <div class="invalid-feedback">
                                Please provide a valid product name (3-255 characters).
                            </div>
                        </div>

                        <!-- Product Description -->
                        <div class="mb-3">
                            <label for="product_description" class="form-label">Product Description</label>
                            <textarea class="form-control" id="product_description" name="product_description" rows="2" required minlength="10"></textarea>
                            <div class="invalid-feedback">
                                Please provide a valid product description (at least 10 characters).
                            </div>
                        </div>

                        <!-- Product Price -->
                        <div class="mb-3">
                            <label for="product_price" class="form-label">Product Price</label>
                            <input type="number" class="form-control" id="product_price" name="product_price" min="0" required>
                            <div class="invalid-feedback">
                                Please enter a valid product price (non-negative value).
                            </div>
                        </div>

                        <!-- Product Category -->
                        <div class="mb-3">
                            <label for="product_category" class="form-label">Product Category</label>
                            <select class="form-select" id="product_category" name="product_category" required>
                                <option value="" selected>Select</option>
                                <option value="T-Shirt">T-Shirt</option>
                                <option value="Polo-Shirt">Polo-Shirt</option>
                                <option value="Formal-Shirt">Formal-Shirt</option>
                                <option value="Casual-Shirt">Casual-Shirt</option>
                                <option value="Printed-Shirt">Printed-Shirt</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a valid product category.
                            </div>
                        </div>

                        <!-- Product Attributes -->
                        <div class="mb-4">
                            <h3 class="mb-3">Product Attributes</h3>
                            <div id="attributeFields">
                                <!-- Attribute fields will be added dynamically here -->
                            </div>
                            <button type="button" class="btn btn-primary" id="addAttribute">Add New Attribute</button>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let attributeFieldIndex = 0;
    let optionFieldIndex = 1;
    let productAttributes = [];

    function addAttributeField() {
        const attributeFields = document.getElementById('attributeFields');
        const card = document.createElement('div');
        card.className = 'row mb-3';

        card.innerHTML = `
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    Product Attributes
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeAttributeCard(this)">Delete</button>
                </div>
                <div class="container mt-3">
                    <div class="row justify-content-center">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="attributeName">Attribute Name</label>
                                    <input type="text" class="form-control" id="attributeName" name="options[${attributeFieldIndex}][attribute_name]" placeholder="Attribute Name" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="selectionType">Selection Type</label>
                                    <select class="form-select" id="selectionType" name="options[${attributeFieldIndex}][selection_type]" required onchange="updateOptionFields(this, ${attributeFieldIndex})">
                                        <option value="" selected>Selection Type</option>
                                        <option value="single">Single Selection</option>
                                        <option value="multiple">Multiple Selection</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="minOptions">Minimum Options</label>
                                    <input type="number" class="form-control" id="minOptions" name="options[${attributeFieldIndex}][minimum_options]" min="1" max="99" disabled>
                                    <span class="text-danger" id="minOptionsError"></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="maxOptions">Maximum Options</label>
                                    <input type="number" class="form-control" id="maxOptions" name="options[${attributeFieldIndex}][maximum_options]" min="1" max="100" disabled>
                                    <span class="text-danger" id="maxOptionsError"></span>
                                </div>
                            </div>
                        </div>

                        <div class="container mt-3">
                            <div class="row justify-content-center">
                                <div class="row option-fields">
                                    <div class="col-md-12">
                                        <div class="row option-fields">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="optionName">Option Name</label>
                                                    <input type="text" class="form-control" id="optionName" name="options[${attributeFieldIndex}][values][0][option_name]" placeholder="Option Name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="additionalPrice">Additional Price</label>
                                                    <input type="number" class="form-control" id="additionalPrice" name="options[${attributeFieldIndex}][values][0][additional_price]" placeholder="Additional Price" min="0">
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="addOptionField(this, ${attributeFieldIndex})">Add Option</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

        // Get references to the minimum and maximum options input fields
        const minOptionsInput = card.querySelector('#minOptions');
        const maxOptionsInput = card.querySelector('#maxOptions');

        // Add event listeners to the input fields for validation
        minOptionsInput.addEventListener('input', () => {
            validateMinMaxOptions(minOptionsInput, maxOptionsInput);
        });

        maxOptionsInput.addEventListener('input', () => {
            validateMinMaxOptions(minOptionsInput, maxOptionsInput);
        });

        attributeFields.appendChild(card);
        attributeFieldIndex++;
    }

    function validateMinMaxOptions(minOptionsInput, maxOptionsInput) {
        const minOptions = parseInt(minOptionsInput.value);
        const maxOptions = parseInt(maxOptionsInput.value);

        const minOptionsError = minOptionsInput.parentElement.querySelector('#minOptionsError');
        const maxOptionsError = maxOptionsInput.parentElement.querySelector('#maxOptionsError');

        minOptionsError.textContent = '';
        maxOptionsError.textContent = '';

        if (minOptions > maxOptions) {
            minOptionsError.textContent = 'Minimum options cannot be greater than maximum options.';
        }
    }


    // Function to remove an attribute card
    function removeAttributeCard(button) {
        const card = button.closest('.row.mb-3');
        card.parentElement.removeChild(card);
    }


    // Function to add an option field within an attribute card
    function addOptionField(button, attributeIndex) {
        // newly added condition
        const selectionType = document.querySelector(`[name="options[${attributeIndex}][selection_type]"]`).value;
        const minOptionsInput = document.querySelector(`[name="options[${attributeIndex}][minimum_options]"]`);
        const maxOptionsInput = document.querySelector(`[name="options[${attributeIndex}][maximum_options]"]`);
        const optionFields = button.parentElement.parentElement.querySelector('.option-fields');
        // Parse values as integers
        const minOptions = parseInt(minOptionsInput.value);
        const maxOptions = parseInt(maxOptionsInput.value);


        if (selectionType === 'single' || selectionType === '') {
            // Disable adding new option fields for 'single' selection_type
            return;
        }
        // Check if optionFieldIndex is within the valid range
        const currentOptionFields = optionFields.querySelectorAll('.row.option-fields');
        const currentOptionCount = ((currentOptionFields.length) / 2) + 1;
        if (currentOptionCount >= maxOptions) {
            alert('You have reached the maximum number of options.');
            return;
        }

        const optionField = document.createElement('div');
        optionField.className = 'row option-fields';
        optionField.innerHTML = `
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row option-fields">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Option Name</label>
                        <input type="text" class="form-control"
                    name="options[${attributeIndex}][values][${optionFieldIndex }][option_name]" placeholder="Option Name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Additional Price</label>
                        <input type="number" class="form-control"
                            name="options[${attributeIndex}][values][${optionFieldIndex }][additional_price]" placeholder="Additional Price" min="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <br>
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="removeOptionField(this)">Delete</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
    `;
        optionFields.appendChild(optionField);
        optionFieldIndex++;
    }

    // Function to remove an option field
    function removeOptionField(button) {
        const optionField = button.closest('.row');
        optionField.parentElement.removeChild(optionField);
    }

    // Function to update option fields based on selection type
    function updateOptionFields(selectElement, attributeIndex) {
        const parentDiv = selectElement.parentElement.parentElement.parentElement;
        const minOptionsInput = parentDiv.querySelector(
            `[name="options[${attributeIndex}][minimum_options]"]`);
        const maxOptionsInput = parentDiv.querySelector(
            `[name="options[${attributeIndex}][maximum_options]"]`);

        if (selectElement.value === 'single') {
            minOptionsInput.disabled = true;
            maxOptionsInput.disabled = true;
            minOptionsInput.value = '';
            maxOptionsInput.value = '';
        } else if (selectElement.value === 'multiple') {
            minOptionsInput.disabled = false;
            maxOptionsInput.disabled = false;
        }
    }
    // Function to add a new attribute card when the "Add New Attribute" button is clicked
    document.getElementById('addAttribute').addEventListener('click', function() {
        addAttributeField();
    });
    // Initial call to set the option fields based on the default selection type (Single Selection)
    updateOptionFields(document.querySelector('[name^="options[][selection_type]"]'));
</script>

@endsection