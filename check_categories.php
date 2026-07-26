<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('categories')->select('id', 'name', 'description')->get();
foreach ($rows as $row) {
    echo $row->id . '|' . $row->name . '|' . $row->description . PHP_EOL;
}
