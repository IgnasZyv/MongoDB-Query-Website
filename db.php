<?php
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017'); // connect to mongodb
$readPreference = new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY); // read from primary
echo "Connected to MongoDB successfully";