<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$datos = App\Models\bait\BaitVentas::limit(10)->cursor();
$ids = $datos->pluck('id');

try {
    $historicos = App\Models\bait\BaitHistoricos::whereIn('bait_ventas_id', $ids)->cursor();
    echo "Success\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
} catch (\Error $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
