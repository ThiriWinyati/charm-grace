$(document).ready(function () {
    // Dynamically add image input fields based on the count entered
    $('#imageCount').on('input', function () {
        let count = $(this).val();
        $('#imageInputs').empty();
        for (let i = 0; i < count; i++) {
            $('#imageInputs').append(
                `<input type="file" class="form-control mb-2" name="product_images[]" required>`
            );
        }
    });
});