@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Edit Product</h1>
    <form method="POST" action="{{ route('products.update', $product->id) }}">
        @csrf
        @method('PUT')

        <!-- Basic product information -->
        <div class="mb-3">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="product_name" name="product_name" required minlength="3" maxlength="255" value="{{ $product->product_name }}">
            <div class="invalid-feedback">
                Please provide a valid product name (3-255 characters).
            </div>
        </div>

        <div class="mb-3">
            <label for="product_description" class="form-label">Product Description</label>
            <textarea class="form-control" id="product_description" name="product_description" rows="4" required minlength="10">{{ $product->product_description }}</textarea>
            <div class="invalid-feedback">
                Please provide a valid product description (at least 10 characters).
            </div>
        </div>

        <div class="mb-3">
            <label for="product_price" class="form-label">Product Price</label>
            <input type="number" class="form-control" id="product_price" name="product_price" min="0" required value="{{ $product->product_price }}">
            <div class="invalid-feedback">
                Please enter a valid product price (non-negative value).
            </div>
        </div>

        <!-- Product Category -->
        <div class="mb-3">
            <label for="product_category" class="form-label">Product Category</label>
            <select class="form-select" id="product_category" name="product_category" required>
                <option value="" selected>Select</option>
                @foreach ($productCategories as $categoryValue => $categoryLabel)
                <option value="{{ $categoryValue }}" {{ $product->product_category === $categoryValue ? 'selected' : '' }}>
                    {{ $categoryLabel }}
                </option>
                @endforeach
            </select>
            <div class="invalid-feedback">
                Please select a valid product category.
            </div>
        </div>

        <!-- Existing product attributes and options -->
        @foreach (json_decode($product->variations) as $key => $attribute)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <!-- Attribute Name Input Field for Editing -->
                    <input type="text" class="form-control" id="attribute_name" name="options[$key][attribute_name]" value="{{ $attribute->attribute_name }}" required>
                </div>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeAttributeCard(this)">Delete</button>
            </div>
            <div class="card-body">
                <!-- Attribute-specific fields -->
                <div class="mb-3">
                    <label for="attribute_selection_type" class="form-label">Selection Type</label>
                    <select class="form-select" id="attribute_selection_type" name="options[{$key][selection_type]" required onchange="updateOptionFields(this, {$key})">
                        <option value="single" {{ $attribute->selection_type === 'single' ? 'selected' : '' }}>
                            Single Selection</option>
                        <option value="multiple" {{ $attribute->selection_type === 'multiple' ? 'selected' : '' }}>
                            Multiple Selection</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="attribute_minimum_options" class="form-label">Minimum Options</label>
                    <input type="number" class="form-control" id="attribute_minimum_options" name="options[$key][minimum_options]" min="0" max="99" value="{{ $attribute->minimum_options }}" {{ $attribute->selection_type === 'single' ? 'disabled' : '' }}>
                </div>

                <div class="mb-3">
                    <label for="attribute_maximum_options" class="form-label">Maximum Options</label>
                    <input type="number" class="form-control" id="attribute_maximum_options" name="options[${key}][maximum_options]" min="0" max="100" value="{{ $attribute->maximum_options }}" {{ $attribute->selection_type === 'single' ? 'disabled' : '' }}>
                </div>

                <!-- Options for this attribute -->
                <div class="option-fields">

                    @foreach($attribute->values as $key => $value)
                    <div class="row mb-2">
                        <!-- Option ID (Hidden Input) -->
                        <input type="hidden" name="options[${key}][values][0][]" value="options[${key}]">
                        <!-- Option Name -->
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="options[${key}][values][0][option_name]" value="{{$value->option_name}}" placeholder="Option Name" required>
                        </div>
                        <!-- Additional Price -->
                        <div class="col-md-4">
                            <input type="number" class="form-control" name="options[${key}][values][0][additional_price]" value="{{$value->additional_price}}" placeholder="Additional Price" min="0">
                        </div>
                        <!-- Delete Option Button -->
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeOptionField(this)">Delete</button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Add Option Button -->
                <div class="text-end">
                    <button type="button" class="btn btn-primary btn-sm" onclick="addOptionField($key,'')">Add Option</button>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Product Attributes -->
        <div id="attributeFields">
            <!-- Attribute fields will be added dynamically here -->
            <button type="button" class="btn btn-primary" id="addAttribute">Add New Attribute</button>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

<script>
    // var count= {{isset($product->variations)?count(json_decode($product->variations,true)):0}};
    // let attributeFieldIndex = count(json_decode($product->variations));
    // let optionFieldIndex = 0;
    $(document).ready(function() {
        var count = {
            {
                isset($product - > variations) ? count(json_decode($product - > variations, true)) : 0
            }
        };
        let attributeFieldIndex = count; // Initialize attributeFieldIndex with the count value
        console.log(attributeFieldIndex);
        // Now you can use attributeFieldIndex in the rest of your JavaScript code
    });
    $(document).ready(function() {
        var optionCount = {
            {
                isset($attribute - > values) ? count(json_decode($attribute - > values, true)) : 0
            }
        };
        let optionFieldIndex = optionCount;
        console.log(optionFieldIndex);
    });

    // Function to add a new attribute card
    function addAttributeField() {
        const attributeFields = document.getElementById('attributeFields');
        const card = document.createElement('div');
        card.className = 'card mb-3';
        card.innerHTML = `
            <div class="card-header d-flex justify-content-between">
                <!-- Attribute Name Input Field for Editing -->
                <input type="text" class="form-control" id="attribute_name" name="options[new_${attributeFieldIndex}][attribute_name]"
                    placeholder="Attribute Name" required>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeAttributeCard(this)">Delete</button>
            </div>
            <div class="card-body">
                <!-- Attribute-specific fields -->
                <div class="mb-3">
                    <label for="attribute_selection_type" class="form-label">Selection Type</label>
                    <select class="form-select" id="attribute_selection_type" name="options[new_${attributeFieldIndex}][selection_type]"
                        required onchange="updateOptionFields(this, 'new_${attributeFieldIndex}')">
                        <option value="single">Single Selection</option>
                        <option value="multiple">Multiple Selection</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="attribute_minimum_options" class="form-label">Minimum Options</label>
                    <input type="number" class="form-control" id="attribute_minimum_options"
                        name="options[new_${attributeFieldIndex}][minimum_options]" min="0" max="99"
                        placeholder="Minimum Options" disabled>
                </div>
                <div class="mb-3">
                    <label for="attribute_maximum_options" class="form-label">Maximum Options</label>
                    <input type="number" class="form-control" id="attribute_maximum_options"
                        name="options[new_${attributeFieldIndex}][maximum_options]" min="0" max="100"
                        placeholder="Maximum Options" disabled>
                </div>
                <!-- Options for this attribute -->
                <div class="option-fields">
                    <!-- Options will be added dynamically here -->
                </div>
                <!-- Add Option Button -->
                <div class="text-end">
                    <button type="button" class="btn btn-primary btn-sm"
                        onclick="addOptionField(this, 'new_${attributeFieldIndex}', '')">Add Option</button>
                </div>
            </div>
        `;
        attributeFields.appendChild(card);
        attributeFieldIndex++;
    }

    // Function to remove an attribute card
    function removeAttributeCard(button) {
        const card = button.closest('.card');
        const attributeIndex = card.getAttribute('data-attribute-index');

        console.log('Attribute Index:', attributeIndex); // Log the attributeIndex

        if (attributeIndex !== null) {
            // Send an AJAX request to delete the attribute and its options
            fetch(`/delete-attribute/${attributeIndex}`, {
                    method: 'DELETE',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the card from the HTML if the deletion was successful
                        card.parentElement.removeChild(card);
                    } else {
                        // Handle any errors or display an error message
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        } else {
            console.error("Attribute index is null. Unable to delete.");
        }
    }

    // Function to add option fields based on maximum options
    function addOptionField(button, attributeIndex) {
        const optionFields = button.parentElement.parentElement.querySelector('.option-fields');
        const maxOptionsInput = document.querySelector(
            `[name="options[${attributeIndex}][maximum_options]"]`);

        if (maxOptionsInput && maxOptionsInput.value > 0) {
            const maxOptions = parseInt(maxOptionsInput.value);
            const optionField = document.createElement('div');
            optionField.className = 'row mb-2';
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
    }

    // Function to remove an option field
    function removeOptionField(button) {
        const optionField = button.closest('.row.mb-2');
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

    // Initial call to set the option fields based on the default selection type (Single Selection)
    updateOptionFields(document.querySelector('[name^="options[new_][selection_type]"]'));

    // Function to initialize the option fields for existing attributes
    function initializeOptionFields() {
        const existingAttributes = document.querySelectorAll('[name^="options["]');
        existingAttributes.forEach(attribute => {
            const attributeIndex = attribute.getAttribute('name').match(/\d+/)[0];
            const selectionType = document.querySelector(
                `[name="options[${attributeIndex}][selection_type]"]`);
            updateOptionFields(selectionType, attributeIndex);
        });
    }

    // Function to add a new attribute card when the "Add New Attribute" button is clicked
    document.getElementById('addAttribute').addEventListener('click', function() {
        addAttributeField();
    });
    // Call the function to initialize the option fields for existing attributes
    initializeOptionFields();
</script>
@endsection