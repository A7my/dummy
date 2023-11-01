<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Product Display</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style>
        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 5px;
        }

        .sidebar {
            width: 30%;
            background-color: #f0f0f0;
            padding: 20px;
            box-sizing: border-box;
        }

        #data-wrapper {
            width: 65%;
        }


        #data-wrapper .row {
            display: flex;
            flex-wrap: wrap;
        }

        .centered-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
        }

        .centered-content {
            width: 40%;
        }
            body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background: #f2f2f2;
            /* min-height: 100vh; */
        } */

        .sidebar {
            background: #333;
            color: white;
            padding: 20px;
            width: 20%;
        }

        .content {
            width: 80%;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
        }

        .product {
            background: #3498db;
            color: black;
            border: 1px solid #2c3e50;
            padding: 20px;
            margin: 10px;
            width: calc(33.33% - 20px);
            box-sizing: border-box;
        }

        .search {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .checkbox {
            margin: 5px 0;
        }

        button {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;

        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="sidebar">
            <form action="{{ route('dummy') }}" method="GET">
            @csrf
            <div class="search">
                <input type="text" name="search" placeholder="Search">
            </div>

            <div class="checkbox">
                <label for="category">Category</label>
                <br>
                Category-1: <input type="checkbox" name="categories[]" value="category-1">
                Category-2: <input type="checkbox" name="categories[]" value="category-2">
                Category-3: <input type="checkbox" name="categories[]" value="category-3">
            </div>
            <div class="checkbox">
                <label for="brand">Brand</label>
                <br>
                Brand-1: <input type="checkbox" name="brands[]" value="brand-1">
                Brand-2: <input type="checkbox" name="brands[]" value="brand-2">
                Brand-3: <input type="checkbox" name="brands[]" value="brand-3">
            </div>
            <button type="submit">Apply</button>
            </form>
        </div>
        <div id="data-wrapper">
            <div class="row">
        @include('data')
        </div>
    </div>
</div>
<div class="row text-center" style="padding:20px;">
    <button class="btn btn-success load-more-data">Load More Data...</button>
</div>

<div class="auto-load text-center" style="display: none;">
    <div class="d-flex justify-content-center">
        <div class="spinner-border" role="status">
            <span>Loading...</span>
        </div>
    </div>
</div>

<div class="centered-container">
    <div class="centered-content">
        <canvas id="myChart"></canvas>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
        var ENDPOINT = "{{ route('dummy') }}";
        var page = 1;

        $(".load-more-data").click(function(){
            page++;
            LoadMore(page);
        });
        function LoadMore(page) {
            $.ajax({
                    url: ENDPOINT + "?page=" + page,
                    datatype: "html",
                    type: "get",
                    beforeSend: function () {
                        $('.auto-load').show();
                    }
                })
                .done(function (response) {
                    console.log(response);
                    if (response.html == '') {
                        $('.auto-load').html("End :(");
                        return;
                    }
                    $('.auto-load').hide();
                    $("#data-wrapper").append("<div class='row'>" + response.html + "</div>");
                })
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    console.log('Server error occured');
                });
        }
</script>

    <?php
    $categoryOneCount = $items->filter(function ($item) {
    return $item->category === 'category-1';
    })->count();
    $categoryTwoCount = $items->filter(function ($item) {
    return $item->category === 'category-2';
    })->count();
    $categoryThreeCount = $items->filter(function ($item) {
    return $item->category === 'category-3';
    })->count();

    ?>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');

    const data = {
        // labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        labels: ['Products Number' , 'Category-1' , 'Category-2' , 'Category-3'],
        datasets: [
            {
                label: 'My First Dataset',
                // data: [65, 59, 80, 81, 56, 55, 40],
                data: [ {{ $items->count() }} , {{ $categoryOneCount }} , {{ $categoryTwoCount }} , {{ $categoryThreeCount }}],
                fill: true,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1, // Smoothing
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                pointRadius: 5,
                pointHoverRadius: 10,
            },
        ],
    };

    const options = {
        scales: {
            y: {
                beginAtZero: true,
            },
        },
    };

    new Chart(ctx, {
        type: 'line',
        data: data,
        options: options,
    });
</script>

</body>
</html>
