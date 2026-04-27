<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\MarcSubfieldDefinition;
use App\Models\MarcTagDefinition;

$sfLabels = [
    '001'=>['_'=>'Control Number'],'003'=>['_'=>'Control Number Identifier'],
    '005'=>['_'=>'Date and Time'],'008'=>['_'=>'Fixed-Length Data Elements'],
    '020'=>['a'=>'ISBN','c'=>'Terms of Availability','z'=>'Canceled ISBN','q'=>'Qualifying Info'],
    '040'=>['a'=>'Original Cataloging Agency','b'=>'Language','c'=>'Transcribing Agency','d'=>'Modifying Agency','e'=>'Description Conventions'],
    '041'=>['a'=>'Language Code of Text','h'=>'Language Code of Original'],
    '050'=>['a'=>'Classification Number','b'=>'Item Number'],
    '082'=>['a'=>'Classification Number','b'=>'Item Number','2'=>'Edition Number'],
    '100'=>['a'=>'Personal Name','b'=>'Numeration','c'=>'Titles','d'=>'Dates','e'=>'Relator Term','q'=>'Fuller Form'],
    '245'=>['a'=>'Title','b'=>'Remainder of Title','c'=>'Statement of Responsibility','h'=>'Medium','n'=>'Number of Part','p'=>'Name of Part'],
    '250'=>['a'=>'Edition Statement','b'=>'Remainder of Edition'],
    '260'=>['a'=>'Place of Publication','b'=>'Publisher Name','c'=>'Date of Publication'],
    '264'=>['a'=>'Place','b'=>'Name','c'=>'Date'],
    '300'=>['a'=>'Extent','b'=>'Other Physical Details','c'=>'Dimensions','e'=>'Accompanying Material'],
    '490'=>['a'=>'Series Statement','v'=>'Volume','x'=>'ISSN'],
    '500'=>['a'=>'General Note'],'504'=>['a'=>'Bibliography Note'],
    '520'=>['a'=>'Summary','b'=>'Expansion'],
    '650'=>['a'=>'Topical Term','v'=>'Form Subdivision','x'=>'General Subdivision','y'=>'Chronological','z'=>'Geographic','2'=>'Source'],
    '700'=>['a'=>'Personal Name','d'=>'Dates','e'=>'Relator Term','t'=>'Title of Work'],
    '852'=>['a'=>'Location','b'=>'Sublocation','c'=>'Shelving Location','h'=>'Classification Part','p'=>'Piece Designation'],
    '856'=>['u'=>'URI','y'=>'Link Text','z'=>'Public Note'],
];
$generic = [
    'a'=>'Primary Data','b'=>'Secondary Data','c'=>'Qualifier','d'=>'Date','e'=>'Relator',
    '_'=>'Control Data','2'=>'Source','4'=>'Relator Code',
];

$badLabels = ['Subfield', 'Primary data', 'Secondary data', 'Tertiary data', 'Saved'];
$fixed = 0;

$allDefs = MarcSubfieldDefinition::with('tagDefinition')->get();
foreach ($allDefs as $def) {
    $isBad = false;
    foreach ($badLabels as $bad) {
        if (str_starts_with($def->label, $bad)) { $isBad = true; break; }
    }
    if (!$isBad) continue;

    $tag = $def->tagDefinition->tag ?? '???';
    $code = $def->code;
    $newLabel = $sfLabels[$tag][$code] ?? $generic[$code] ?? $def->label;

    if ($newLabel !== $def->label) {
        echo "[FIX] Tag $tag \$$code: '{$def->label}' => '$newLabel'\n";
        $def->update(['label' => $newLabel]);
        $fixed++;
    }
}

echo "\nFixed $fixed subfield label(s)\n";
