<?php 


$parse_file = file_get_contents('./data/source/sdg_indicator_metadata.csv');
$csv = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $parse_file));


//var_dump($csv); exit;

foreach ($csv as $key => $indicator) {
	
	// Skip first row
	if ($key < 1) {
        continue;
	}

    

    $goal_num = $indicator[2];
    $indicator_id = $indicator[6];
    $indicator_short = slugify($indicator[6]);

    $frontmatter = "---\n";
    $frontmatter .= "permalink: /api/$indicator_short" . '.json' . "\n";
    $frontmatter .= "sdg_goal: $goal_num\n";
    $frontmatter .= "layout: json_indicator\n";

    $frontmatter .= "indicator: \"$indicator_id\"\n";

    $frontmatter .= "---\n\n";

    $text = $frontmatter . "";

    $filename = $indicator_short;

    $file = fopen($filename . '.md', 'w');
    fwrite($file, $text);
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
