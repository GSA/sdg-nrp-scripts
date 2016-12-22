<?php 


$parse_file = file_get_contents('./data/source/sdg_indicator_metadata.csv');
$csv = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $parse_file));


//var_dump($csv); exit;

foreach ($csv as $key => $indicator) {
	
	// Skip first row
	if ($key < 1) {
        continue;
	}

    
    $indicator_short = slugify($indicator[6]);

    $csv = "year,var_1,var_2\n";
    $csv .= "2015,0,0\n";

    $filename = 'indicator_' . $indicator_short . '.csv';

    $file = fopen($filename, 'w');
    fwrite($file, $csv);
    fclose($file);


}



function slugify($text) {
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}




?>
