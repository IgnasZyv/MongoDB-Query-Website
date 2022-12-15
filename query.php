<?php
session_start();
if (isset($_POST["queryPrimary"]) || isset($_POST["querySecondary"])) {
    $inputQueryPrimary = $_POST["queryPrimary"];
    $inputColumnPrimary = $_POST["columnPrimary"];
    $inputQuerySecondary = $_POST["querySecondary"];
    $inputColumnSecondary = $_POST["columnSecondary"];
    $inputSortColumn = $_POST["sortColumn"];
    $inputFilter = $_POST["filters"];
    $inputSort = $_POST["sort"];
    $inputLimit = $_POST["limit"];

    // Check if the primary query is an integer or a float
    if (filter_var($inputQueryPrimary, FILTER_VALIDATE_INT) !== false) {
        // if the primary query is an integer, cast it as an integer
        $inputQueryPrimary = (int) $inputQueryPrimary;
    } elseif (filter_var($inputQueryPrimary, FILTER_VALIDATE_FLOAT) !== false) {
        $inputQueryPrimary = (float) $inputQueryPrimary;
    }

    // Check if the secondary query is an integer or a float
    if (filter_var($inputQuerySecondary, FILTER_VALIDATE_INT) !== false) {
        // if the secondary query is an integer, cast it as an integer
        $inputQuerySecondary = (int) $inputQuerySecondary;
    } elseif (filter_var($inputQuerySecondary, FILTER_VALIDATE_FLOAT) !== false) {
        $inputQuerySecondary = (float) $inputQuerySecondary;
    }

    if ($inputFilter == "primary") { // if the filter is set to primary, only use the primary query
        $filter = [
            $inputColumnPrimary => $inputQueryPrimary // set the filter to the primary query
        ];
    } elseif ($inputFilter == "and") { // if the filter is set to and, use both queries
        $filter = [
            $inputColumnPrimary => $inputQueryPrimary,
            $inputColumnSecondary => $inputQuerySecondary
        ];
    } elseif ($inputFilter == "or") { // if the filter is set to or, use both queries
        $filter = [
            '$or' => [
                [$inputColumnPrimary => $inputQueryPrimary],
                [$inputColumnSecondary => $inputQuerySecondary]
            ]
        ];
    }


    if (isset($inputSortColumn) && isset($inputLimit)) { // if both the sort column and limit input fields are set
        if ($inputSort == "ASC") { // if the sort order is set to ascending
            $options = [
                'sort' => [ // set the sort order to ascending
                    $inputSortColumn => 1
                ],
                "limit" => $inputLimit, // set the limit to the limit input field
            ];
        } elseif ($inputSort == "DESC") {
            $options = [
                'sort' => [ // set the sort order to descending
                    $inputSortColumn => -1
                ],
                "limit" => $inputLimit,
            ];
        }
    } elseif (isset($inputSortColumn)) { // if only the sort column input field is set
        if ($inputSort == "ASC") {
            $options = [
                'sort' => [
                    $inputSortColumn => 1
                ],
            ];
        } elseif ($inputSort == "DESC") {
            $options = [
                'sort' => [
                    $inputSortColumn => -1
                ],
            ];
        }
    } elseif (isset($inputLimit)) { // if only the limit input field is set
        $options = [
            "limit" => $inputLimit,
        ];
    } else { // if neither the sort column or limit input fields are set
        $options = [];
    }

    // Create a Query object with no filter and no projection
    $query = new MongoDB\Driver\Query($filter, $options);

    // Execute the query and retrieve the columns from the collection
    // $cursor = $collection->executeQuery($query, ['projection' => ['_id' => 0]]);

    $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017'); // connect to mongodb
    $readPreference = new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY); // read from primary
    $cursor = $manager->executeQuery('chargingDevice.devices', $query, $readPreference); // execute query

    $cursorArray = $cursor->toArray(); // convert cursor to array
    $locationArray = array();
    $table = "<table>";

    foreach ($cursorArray as $document) { // loop through the cursor array
        $latitude;
        $longitude;
        $id;

        if ($cursorArray[0] == $document) { // if the document is the first document in the array
            $table .= "<tr>";
            foreach ($document as $key => $value) { // loop through the first document and create the table header
                $table .= "<th>" . $key . "</th>";
            }
            $table .= "</tr>";
            $table .= "<tr>"; // create a new row
            foreach ($document as $key => $value) { // loop through the first document and create the table data
                if ($value == "") { // if the value is empty, set it to null
                    $value = "null";
                    $table .= "<td style='font-style: italic'>" . $value . "</td>";
                } else { // otherwise, just set the value to the value
                    $table .= "<td>" . $value . "</td>";
                    if ($key == "chargeDeviceID") {
                        $id = $value;
                    } elseif ($key == "latitude") {
                        $latitude = $value;
                    } elseif ($key == "longitude") {
                        $longitude = $value;
                    }
                }
            }
            $table .= "</tr>";
        } else { // if the document is not the first document in the array
            $table .= "<tr>";
            foreach ($document as $key => $value) { // loop through the document and create the table data
                if ($value == "") {
                    $value = "null";
                    $table .= "<td style='font-style: italic'>" . $value . "</td>";
                } else {
                    $table .= "<td>" . $value . "</td>";
                    if ($key == "chargeDeviceID") {
                        $id = $value;
                    } elseif ($key == "latitude") {
                        $latitude = $value;
                    } elseif ($key == "longitude") {
                        $longitude = $value;
                    }
                }
            }
            $table .= "</tr>";
        }
        $locArray = array($latitude, $longitude);
        array_push($locationArray, $locArray);
    }
    $table .= "</table>";
    $_SESSION["location"] = $locationArray;
    if (empty($cursorArray)) { // if no data was returned from the query, display a message
        $table = "<p style='color: red; font-size:24px; text-align: center;'>No results found</p>";
    }
    echo $table; // return the table structure to the html page
}