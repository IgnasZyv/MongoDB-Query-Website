<?php
// session_reset();
session_start();


$columns = include_once "getColumns.php";
?>

<head>
    <title>MongoDB Query Results</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg&callback=initMap">
    </script> -->
    <script>
    var locations;

    $(function() {
        $('form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: 'query.php',
                data: $('form').serialize(),
                success: function(res) {
                    $('#data').html(res);
                    <?php
                        $locationArray = $_SESSION["location"];
                        ?>
                    alert("hey " +
                        <?php echo json_encode($_SESSION["location"]); ?>);
                    var locations = <?php echo json_encode($locationArray); ?>;
                    console.log(locations);

                    // // Initialize and add the map
                    // function initMap() {
                    //     // The location of Uluru
                    //     const uluru = {
                    //         lat: -25.344,
                    //         lng: 131.031
                    //     };
                    //     // The map, centered at Uluru
                    //     const map = new google.maps.Map(document.getElementById("map"), {
                    //         zoom: 4,
                    //         center: uluru,
                    //     });
                    //     // The marker, positioned at Uluru
                    //     const marker = new google.maps.Marker({
                    //         position: uluru,
                    //         map: map,
                    //     });
                    // }

                    // window.initMap = initMap;

                    // $infowindow = new google.maps.InfoWindow({});
                    // var marker, count;
                    // for (count = 0; count < locations.length; count++) {
                    //     marker = new google.maps.Marker({
                    //         position: new google.maps.LatLng(locations[count][0],
                    //             locations[count][1]),
                    //         map: map,
                    //         title: locations[count][0]
                    //     });
                    //     google.maps.event.addListener(marker, 'click', (function(marker,
                    //         count) {
                    //         return function() {
                    //             infowindow.setContent(locations[count][0]);
                    //             infowindow.open(map, marker);
                    //         }
                    //     })(marker, count));
                    // }

                    // print();
                    // console.log(res);
                    // alert("Query executed successfully!");
                }
            });
        });
    });
    </script>
</head>

<body>

    <div class="center-div">
        <h1>MongoDB Query Results</h1>
        <form>
            <div class="container-flex">
                <div class="search-primary search-container">
                    <h3>Primary Search</h3>
                    <label for="columnPrimary">Select the columns you want to display:</label><br />
                    <select name="columnPrimary"><br />
                        <?php echo $columns ?>
                    </select>

                    <label for="query">Enter your MongoDB query:</label><br />
                    <input type="text" name="queryPrimary"> <br />
                </div>

                <div class="search-secondary search-container">
                    <h3>Secondary Search</h3>
                    <label for="columnSecondary">Select the columns you want to display:</label><br />
                    <select name="columnSecondary">
                        <?php echo $columns ?> <br />
                    </select>
                    <label for="querySecondary">Enter your MongoDB query:</label><br />
                    <input type="text" name="querySecondary"> <br />
                </div>
            </div>

            <div class="container-flex">
                <div class="filters">
                    <p>Primary Only - use only the primary search tool</p>
                    <p>And - use the primary and secondary search tools</p>
                    <p>Or - use the primary or secondary search tools</p>
                    <label for="filter">Select filter tools:</label>
                    <select name="filters">
                        <option value="primary">Primary Only</option>
                        <option value="and">And</option>
                        <option value="or">Or</option>
                    </select>
                </div>

                <div class="options">
                    <label for="sort">Select sort order:</label> <br />
                    <select name="sort">
                        <option value="ASC">Sort Ascending</option>
                        <option value="DESC">Sort Descending</option>
                    </select>
                    <br />

                    <label for="sortColumn">Select the column you want to sort by:</label><br />
                    <select name="sortColumn"><br />
                        <?php echo $columns ?>
                    </select>
                    <br />
                    <label for="limit">Enter the limit:</label> <br />
                    <input type="number" name="limit" placeholder="Limit">
                </div>
            </div>

            <input style="margin: 2em; padding: 1em;" type="submit" id="submitBtn" name="submitBtn"
                value="Submit Query" />

        </form>

        <div id="map"></div>
    </div>

    <div class="form-group">>
        <div id="data"></div>
    </div>





</body>