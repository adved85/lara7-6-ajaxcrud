<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>laravel-7.29-Yajra</title>

    <!-- Bootstrap 4 -->

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">


    <style>
        /* adding red color for error messages */
        label.error {
            color: crimson;
        }
    </style>

</head>
<body>

    <div class="container">
        <h1 class="mt-4 mb-2">Laravel 7-6 Ajax CRUD tutorial using Datatable - ItSolutionStuff.com</h1>
        <a class="btn btn-success my-1" href="javascript:void(0)" id="createNewProduct"> Create New Product</a>
        <code id="log">
        </code>
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Details</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="productModel" tabindex="-1" aria-labelledby="productModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModelLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="productForm" id="productForm" class="form-horizontal" novalidate="novalidate">
                    <input type="hidden" id="productId" name="product_id" value="product_id">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Product Name" minlength="3" required>
                    </div>
                    <div class="form-group">
                        <label for="detail">Name</label>
                        <textarea class="form-control" name="detail" id="detail" cols="30" rows="10" placeholder="Enter Details" required></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <h6>create and update Modal</h6>
            </div>
        </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <!-- JQuery-validate -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.js"></script>

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            /* URLs */
            const productsIndex = @JSON(route('ajaxproducts.index'));
            const productEditTemplate = @JSON(route('ajaxproducts.edit', ':productId'));
            const productStore = @JSON(route('ajaxproducts.store'));
            const productDestroyTemplate = @JSON(route('ajaxproducts.destroy', ':productId'));

            /* init form vaidation */
            const $productForm = $('#productForm');
            const $productFormValidate = $productForm.validate(); // console.log($productFormValidate.errorList);

            const $table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: productsIndex,
                columns: [
                    {data:'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'detail', name: 'detail'},
                    {data: 'action', name:'action'},
                ]
            });

            $('#createNewProduct').click( function() {

                $('#productModel').modal('show');
                $('#productModelLabel').text('New Product');
                $productForm.trigger('reset'); // document.getElementById("productForm").reset();
                $('#productId').val('');
                $('#saveBtn').text('Create Product').val('create-product');

            });

            $('.data-table').on('click', '.editProduct', function() {
                $productFormValidate.resetForm();

                const productId = $(this).data('id');
                const productEdit = productEditTemplate.replace(':productId', productId);
                $.get(productEdit, function(data) {
                    // console.log(data);
                    $('#productModel').modal('show');
                    $('#productModelLabel').text(`Edit Product N-${productId}`);
                    $('#productId').val(data.id);
                    $('#name').val(data.name);
                    $('#detail').val(data.detail);
                    $('#saveBtn').text('Update Product').val('update-product');
                })
            });


            $('#saveBtn').on('click', function(event) {
                event.preventDefault();
                const productFormData = $productForm.serialize();
                const isValid = $productForm.valid();
                if(!isValid) return false;

                const request = $.ajax({
                    url: productStore,
                    method: "POST",
                    data: productFormData,
                    dataType: "json"
                });

                request.done(function( msg, textStatus, jqXHR ) {
                    // console.log(msg)
                    // console.log("Request success: " + textStatus)
                    // console.log(jqXHR)
                    $('#productModel').modal('hide');
                    $productForm.trigger('reset');
                    $table.draw();
                });

                request.fail(function( jqXHR, textStatus ) {
                    alert( "Request failed: " + textStatus );
                });
            });


            $('.data-table').on('click', '.deleteProduct', function() {

                const productId = $(this).data('id');
                const productDestroy = productDestroyTemplate.replace(':productId', productId);

                const request = $.ajax({
                    url: productDestroy,
                    method: "DELETE",
                });

                request.done(function(msg, textStatus, jqXHR) {
                    $table.draw();
                });

                request.fail(function( jqXHR, textStatus ) {
                    alert( "Request failed: " + textStatus );
                });
            });
        });

    </script>
</body>
</html>
