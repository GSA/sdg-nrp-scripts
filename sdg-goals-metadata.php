<?php 


$parse_file = file_get_contents('./data/source/es/sdg_goals.csv');
$csv = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $parse_file));


//var_dump($csv); exit;

foreach ($csv as $key => $goals) {
	
	// Skip first row
	if ($key < 1) {
        continue;
	}

    $goal_num = $goals[0];
    $goal_title = $goals[1];
    $goal_short = slugify($goals[2]);

    $frontmatter = "---\n";
    $frontmatter .= "title: $goal_title\n";
    $frontmatter .= "lang: es\n";
    $frontmatter .= "permalink: /es/$goal_short/\n";
    $frontmatter .= "sdg_goal: $goal_num\n";
    $frontmatter .= "layout: goal\n";
    $frontmatter .= "---\n\n";

    $text = $frontmatter . "";

    $filename = $goal_num . '_' . $goal_short;

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
