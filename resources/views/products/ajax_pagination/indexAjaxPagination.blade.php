<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ajax Pagination</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
</head>
<body>

    <div class="container">
        <div class="row">
            <h3>Ajax Pagination</h3>
            @if (count($data))
                <section id="data">
                    @include('products.ajax_pagination.loadAjaxPaginationData', ['data' => $data])
                </section>
            @else
                No Data found!
            @endif
        </div>
    </div>

    <p>
        {{$data->links()}}
    </p>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.js"></script>
    <script type="text/javascript">
        $(function () {
            $('body').on('click', '.pagination a', function (e) {
                e.preventDefault();
                $('#load').append('<p>Loading ....</p>');
                var url = $(this).attr('href');
                setTimeout(() => {
                    window.history.pushState("", "", url);
                    loadPosts(url);
                }, 100);
            });
            function loadPosts(url) {
                $.ajax({
                    url: url
                }).done(function (data) {
                    $('#data').html(data);
                }).fail(function () {
                    console.log("Failed to load data!");
                });
            }
        });
    </script>
</body>
</html>
