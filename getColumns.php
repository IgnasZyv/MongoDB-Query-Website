<?php

$filter = [
    // 'town' => 'Bradford',
    // 'views' => [
    //     '$gte' => 100,
    // ],
    // "_id" => ['$gte' => 1],
];

$options = [
    /* Only return the following fields in the matching documents */
    'projection' => [
        // 'chargeDeviceID' => 1,
        // 'town' => 1,
        // '_id' => 1,
    ],
    /* Return the documents in descending order of views */
    // 'sort' => [
    //     'views' => -1
    // ],
    "limit" => 1,
];

// Create a Query object with no filter and no projection
$query = new MongoDB\Driver\Query($filter, $options);

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017'); // connect to mongodb
$readPreference = new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY); // read from primary
$cursor = $manager->executeQuery('chargingDevice.devices', $query, $readPreference); // execute query

// Create an HTML select element and add the columns as options

$select .= "<option>" . "----" . "</option>";
foreach ($cursor as $document) {
    // Get the columns of the document
    foreach ($document as $key => $value) {
        $select .= "<option>" . $key . "</option>";
    }
}


return $select;