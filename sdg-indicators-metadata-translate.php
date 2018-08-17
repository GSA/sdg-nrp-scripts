<?php 

$output_folder = 'data/output/';
$parse_file = file_get_contents('./data/source/fr/sdg_indicator_metadata.csv');
$csv = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $parse_file));


//var_dump($csv); exit;

foreach ($csv as $key => $indicator) {
	
	// Skip first row
	if ($key < 1) {
        continue;
	}

    

    $goal_num = $indicator[2];
    
    $indicator_id = $indicator[6];
    $indicator_title = addcslashes($indicator[7], '"');
    $indicator_short = slugify($indicator[6]);
    $indicator_definition = addcslashes(str_replace(".''", '.', preg_replace( "/\r|\n/", "", stripcslashes($indicator[10]))), '"');
    $indicator_method = addcslashes(preg_replace( "/\r|\n/", "", stripcslashes($indicator[11]) ), '"' );
    $indicator_target = addcslashes(preg_replace( "/\r|\n/", "", stripcslashes($indicator[5]) ), '"' );

    $target_id = $indicator[0];

    $frontmatter = "---\n";
    $frontmatter .= "title: \"$indicator_title\"\n";
    $frontmatter .= "lang: fr\n";
    $frontmatter .= "permalink: /fr/$indicator_short/\n";
    $frontmatter .= "sdg_goal: $goal_num\n";
    $frontmatter .= "layout: indicator\n";

    $frontmatter .= "indicator: \"$indicator_id\"\n";
    $frontmatter .= "target_id: \"$target_id\"\n";


    $frontmatter .= "---\n\n";

    $text = $frontmatter . "";

    $filename = $indicator_short;

    $file = fopen($output_folder . $filename . '.md', 'w');
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
