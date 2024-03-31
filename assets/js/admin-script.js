(function($){
    function addNewAddonRow() {
        var container = document.getElementById('addons-container');
        var index = container.querySelectorAll('.addon-row').length;
        var newRow = document.createElement('div');
        newRow.classList.add('addon-row');
        newRow.innerHTML = `
            <div>
                <label for="addon_title_${index}">Addon Title</label>
                <input type="text" name="product_extra_addons[${index}][title]" id="addon_title_${index}" value="" required>
            </div>
            <div>
                <label for="addon_price_${index}">Addon Price</label>
                <input type="number" step="0.01" min="0" name="product_extra_addons[${index}][price]" id="addon_price_${index}" value="">
            </div>
            <div><span class="dashicons dashicons-trash remove-addon"></span></div>
        `;
        container.appendChild(newRow);
    }
    
    document.getElementById('add-new-addon').addEventListener('click', addNewAddonRow);
    
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-addon')) {
            e.target.closest('.addon-row').remove();
        }
    });
})(jQuery);