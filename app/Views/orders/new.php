<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Create New Order</h2>
    
    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>
    
    <form method="post" action="<?= site_url('orders/new') ?>">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="customer_id">Customer</label>
                    <select class="form-control" id="customer_id" name="customer_id" required>
                        <option value="">Select Customer</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['id'] ?>">
                                <?= $customer['first_name'] ?> <?= $customer['last_name'] ?> - <?= $customer['phone'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Delivery Type</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="delivery_type" id="pickup" value="pickup" checked>
                        <label class="form-check-label" for="pickup">Pickup</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="delivery_type" id="delivery" value="delivery">
                        <label class="form-check-label" for="delivery">Delivery</label>
                    </div>
                </div>
                
                <div class="form-group delivery-address-group" style="display: none;">
                    <label for="delivery_address">Delivery Address</label>
                    <textarea class="form-control" id="delivery_address" name="delivery_address" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="delivery_date">Delivery/Pickup Date</label>
                    <input type="datetime-local" class="form-control" id="delivery_date" name="delivery_date" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="5"></textarea>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <h4>Order Items</h4>
                
                <div class="form-group">
                    <label for="inventory_search">Search Inventory</label>
                    <input type="text" class="form-control" id="inventory_search" placeholder="Search flowers, arrangements...">
                </div>
                
                <table class="table table-bordered mt-2" id="order_items_table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Vendor</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Items will be added here via JavaScript -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total:</strong></td>
                            <td id="order_total">$0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                
                <input type="hidden" name="items" id="items_input">
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Create Order</button>
    </form>
</div>

<script>
$(document).ready(function() {
    // Show/hide delivery address based on delivery type
    $('input[name="delivery_type"]').change(function() {
        if ($(this).val() === 'delivery') {
            $('.delivery-address-group').show();
        } else {
            $('.delivery-address-group').hide();
        }
    });
    
    // Inventory search autocomplete
    $('#inventory_search').autocomplete({
        source: function(request, response) {
            $.get('<?= site_url('orders/searchInventory') ?>', { term: request.term }, function(data) {
                response($.map(data, function(item) {
                    return {
                        label: item.description + ' - ' + item.vendor_name + ' ($' + item.final_price + ')',
                        value: item.description,
                        id: item.id,
                        vendor: item.vendor_name,
                        price: item.final_price,
                        description: item.description
                    };
                }));
            });
        },
        minLength: 2,
        select: function(event, ui) {
            addItemToOrder(ui.item);
            $('#inventory_search').val('');
            return false;
        }
    });
    
    // Function to add item to order table
    function addItemToOrder(item) {
        var rowId = 'item_' + item.id;
        
        if ($('#' + rowId).length) {
            // Item already exists, increment quantity
            var qtyInput = $('#' + rowId + ' .item-qty');
            qtyInput.val(parseInt(qtyInput.val()) + 1);
            qtyInput.trigger('change');
            return;
        }
        
        var row = '<tr id="' + rowId + '">' +
            '<td>' + item.description + '</td>' +
            '<td>' + item.vendor + '</td>' +
            '<td class="item-price">$' + parseFloat(item.price).toFixed(2) + '</td>' +
            '<td><input type="number" class="form-control item-qty" value="1" min="1" style="width: 70px;"></td>' +
            '<td class="item-total">$' + parseFloat(item.price).toFixed(2) + '</td>' +
            '<td><button class="btn btn-sm btn-danger remove-item">Remove</button></td>' +
            '</tr>';
        
        $('#order_items_table tbody').append(row);
        updateOrderTotal();
        updateItemsInput();
    }
    
    // Handle quantity changes
    $(document).on('change', '.item-qty', function() {
        var price = parseFloat($(this).closest('tr').find('.item-price').text().replace('$', ''));
        var qty = parseInt($(this).val());
        var total = (price * qty).toFixed(2);
        $(this).closest('tr').find('.item-total').text('$' + total);
        updateOrderTotal();
        updateItemsInput();
    });
    
    // Handle item removal
    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        updateOrderTotal();
        updateItemsInput();
    });
    
    // Update order total
    function updateOrderTotal() {
        var total = 0;
        $('.item-total').each(function() {
            total += parseFloat($(this).text().replace('$', ''));
        });
        $('#order_total').text('$' + total.toFixed(2));
    }
    
    // Update hidden items input for form submission
    function updateItemsInput() {
        var items = [];
        $('#order_items_table tbody tr').each(function() {
            var itemId = $(this).attr('id').replace('item_', '');
            var qty = $(this).find('.item-qty').val();
            items.push({
                inventory_id: itemId,
                quantity: qty
            });
        });
        $('#items_input').val(JSON.stringify(items));
    }
});
</script>
<?= $this->endSection() ?>