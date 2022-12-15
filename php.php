<?php
/* Select only documents authord by "bjori" with at least 100 views */
$filter = [
    'town' => 'Bradford',
    // 'views' => [
    //     '$gte' => 100,
    // ],
];

$options = [
    /* Only return the following fields in the matching documents */
    'projection' => [
        'chargeDeviceID' => 1,
        'town' => 1,
    ],
    /* Return the documents in descending order of views */
    // 'sort' => [
    //     'views' => -1
    // ],
];

$query = new MongoDB\Driver\Query($filter, $options);

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017'); // connect to mongodb
$readPreference = new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY); // read from primary
$cursor = $manager->executeQuery('chargingDevices.devices', $query, $readPreference); // execute query

foreach ($cursor as $document) {
    print_r($document);
}