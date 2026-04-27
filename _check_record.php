<?php
/**
 * Repair script: scan ALL existing BibliographicRecords,
 * create missing MarcTagDefinition + MarcSubfieldDefinition,
 * and link tags to the record's framework.
 */
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BibliographicRecord;
use App\Models\MarcFramework;
use App\Models\MarcTagDefinition;
use App\Models\MarcSubfieldDefinition;

// MARC21 tag labels
$tagLabels = [
    '001'=>'Control Number','003'=>'Control Number Identifier','005'=>'Date/Time of Latest Transaction',
    '008'=>'Fixed-Length Data Elements','010'=>'LCCN','020'=>'ISBN','022'=>'ISSN',
    '035'=>'System Control Number','040'=>'Cataloging Source','041'=>'Language Code',
    '050'=>'LC Call Number','082'=>'Dewey Classification','084'=>'Other Classification',
    '100'=>'Main Entry - Personal Name','110'=>'Main Entry - Corporate Name',
    '111'=>'Main Entry - Meeting Name','130'=>'Main Entry - Uniform Title',
    '245'=>'Title Statement','246'=>'Varying Form of Title','250'=>'Edition Statement',
    '260'=>'Publication, Distribution','264'=>'Production, Publication',
    '300'=>'Physical Description','336'=>'Content Type','337'=>'Media Type','338'=>'Carrier Type',
    '490'=>'Series Statement','500'=>'General Note','504'=>'Bibliography Note',
    '505'=>'Formatted Contents Note','520'=>'Summary',
    '600'=>'Subject - Personal Name','610'=>'Subject - Corporate Name',
    '650'=>'Subject - Topical Term','651'=>'Subject - Geographic Name',
    '653'=>'Index Term - Uncontrolled','655'=>'Genre/Form',
    '700'=>'Added Entry - Personal Name','710'=>'Added Entry - Corporate Name',
    '830'=>'Series Added Entry','852'=>'Location','856'=>'Electronic Location',
    '942'=>'Added Entry Elements (Koha)','952'=>'Holdings (Koha)','999'=>'Local Data',
];

// MARC21 subfield labels (tag-specific)
$sfLabels = [
    '001'=>['_'=>'Control Number'],'003'=>['_'=>'Control Number Identifier'],
    '005'=>['_'=>'Date and Time'],'008'=>['_'=>'Fixed-Length Data Elements'],
    '020'=>['a'=>'ISBN','c'=>'Terms of Availability','z'=>'Canceled ISBN','q'=>'Qualifying Info'],
    '040'=>['a'=>'Original Cataloging Agency','b'=>'Language','c'=>'Transcribing Agency','d'=>'Modifying Agency','e'=>'Description Conventions'],
    '041'=>['a'=>'Language Code of Text','b'=>'Language Code of Summary','h'=>'Language Code of Original'],
    '050'=>['a'=>'Classification Number','b'=>'Item Number'],
    '082'=>['a'=>'Classification Number','b'=>'Item Number','2'=>'Edition Number'],
    '100'=>['a'=>'Personal Name','b'=>'Numeration','c'=>'Titles','d'=>'Dates','e'=>'Relator Term','q'=>'Fuller Form'],
    '110'=>['a'=>'Corporate Name','b'=>'Subordinate Unit'],
    '245'=>['a'=>'Title','b'=>'Remainder of Title','c'=>'Statement of Responsibility','h'=>'Medium','n'=>'Number of Part','p'=>'Name of Part'],
    '246'=>['a'=>'Title Proper','b'=>'Remainder of Title','i'=>'Display Text'],
    '250'=>['a'=>'Edition Statement','b'=>'Remainder of Edition'],
    '260'=>['a'=>'Place of Publication','b'=>'Publisher Name','c'=>'Date of Publication'],
    '264'=>['a'=>'Place','b'=>'Name','c'=>'Date'],
    '300'=>['a'=>'Extent','b'=>'Other Physical Details','c'=>'Dimensions','e'=>'Accompanying Material'],
    '490'=>['a'=>'Series Statement','v'=>'Volume','x'=>'ISSN'],
    '500'=>['a'=>'General Note'],'504'=>['a'=>'Bibliography Note'],
    '505'=>['a'=>'Contents Note','g'=>'Misc','r'=>'Responsibility','t'=>'Title'],
    '520'=>['a'=>'Summary','b'=>'Expansion'],
    '650'=>['a'=>'Topical Term','v'=>'Form Subdivision','x'=>'General Subdivision','y'=>'Chronological','z'=>'Geographic','2'=>'Source'],
    '651'=>['a'=>'Geographic Name','v'=>'Form Subdivision','x'=>'General Subdivision'],
    '700'=>['a'=>'Personal Name','b'=>'Numeration','c'=>'Titles','d'=>'Dates','e'=>'Relator Term','t'=>'Title of Work'],
    '710'=>['a'=>'Corporate Name','b'=>'Subordinate Unit','e'=>'Relator Term'],
    '852'=>['a'=>'Location','b'=>'Sublocation','c'=>'Shelving Location','h'=>'Classification Part','p'=>'Piece Designation'],
    '856'=>['u'=>'URI','y'=>'Link Text','z'=>'Public Note'],
    '942'=>['a'=>'Institution','c'=>'Item Type','h'=>'Classification Part','2'=>'Source'],
    '952'=>['a'=>'Home Branch','b'=>'Current Branch','c'=>'Shelving Location','d'=>'Date Acquired','o'=>'Call Number','p'=>'Barcode','t'=>'Copy Number','y'=>'Item Type'],
];

$genericSf = [
    'a'=>'Primary Data','b'=>'Secondary Data','c'=>'Qualifier','d'=>'Date','e'=>'Relator',
    'f'=>'Date of Work','g'=>'Miscellaneous','h'=>'Medium','l'=>'Language',
    'n'=>'Number of Part','p'=>'Name of Part','q'=>'Qualifying Info','t'=>'Title',
    'u'=>'URI','v'=>'Volume','w'=>'Control Number','x'=>'ISSN','z'=>'ISBN/Note',
    '_'=>'Control Data','2'=>'Source','4'=>'Relator Code',
];

echo "=== Scanning all BibliographicRecords ===\n\n";

$records = BibliographicRecord::with('fields.subfields')->get();
echo "Total records: " . $records->count() . "\n\n";

$createdTags = 0;
$createdSubfields = 0;
$linkedToFramework = 0;

// Cache frameworks
$frameworkCache = [];

foreach ($records as $record) {
    $fwCode = $record->framework;
    if (!isset($frameworkCache[$fwCode])) {
        $fw = MarcFramework::where('code', $fwCode)->first();
        $frameworkCache[$fwCode] = $fw;
    }
    $fw = $frameworkCache[$fwCode];

    foreach ($record->fields as $field) {
        $tag = $field->tag;

        // 1. Ensure MarcTagDefinition
        $tagDef = MarcTagDefinition::where('tag', $tag)->first();
        if (!$tagDef) {
            $label = $tagLabels[$tag] ?? "Tag $tag";
            $tagDef = MarcTagDefinition::create(['tag' => $tag, 'label' => $label]);
            echo "[NEW TAG] $tag => $label\n";
            $createdTags++;
        }

        // 2. Link to framework
        if ($fw && !$fw->tags()->where('marc_tag_definitions.id', $tagDef->id)->exists()) {
            $maxOrder = $fw->tags()->max('order') ?? 0;
            $fw->tags()->attach($tagDef->id, ['is_visible' => true, 'order' => $maxOrder + 1]);
            echo "[LINKED] Tag $tag => Framework '{$fw->name}'\n";
            $linkedToFramework++;
        }

        // 3. Ensure MarcSubfieldDefinition for each subfield code
        foreach ($field->subfields as $sf) {
            $code = strtolower(trim($sf->code));
            if ($code === '') continue;

            $exists = MarcSubfieldDefinition::where('tag_id', $tagDef->id)->where('code', $code)->exists();
            if (!$exists) {
                $label = $sfLabels[$tag][$code] ?? $genericSf[$code] ?? "Subfield \$$code";
                MarcSubfieldDefinition::create([
                    'tag_id' => $tagDef->id,
                    'code' => $code,
                    'label' => $label,
                    'is_visible' => true,
                ]);
                echo "[NEW SUBFIELD] Tag $tag \$$code => $label\n";
                $createdSubfields++;
            }
        }
    }
}

echo "\n=== DONE ===\n";
echo "Created $createdTags new MarcTagDefinition(s)\n";
echo "Created $createdSubfields new MarcSubfieldDefinition(s)\n";
echo "Linked $linkedToFramework tag(s) to framework(s)\n";
