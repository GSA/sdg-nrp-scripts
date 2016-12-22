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
    $indicator_title = addcslashes($indicator[7], '"');
    $indicator_short = slugify($indicator[6]);
    $indicator_definition = addcslashes(str_replace(".''", '.', preg_replace( "/\r|\n/", "", stripcslashes($indicator[10]))), '"');
    $indicator_method = addcslashes(preg_replace( "/\r|\n/", "", stripcslashes($indicator[11]) ), '"' );
    $indicator_target = addcslashes(preg_replace( "/\r|\n/", "", stripcslashes($indicator[5]) ), '"' );

    $target_id = $indicator[0];
    $has_metadata = strtolower($indicator[1]);
    $rationale_interpretation = addcslashes($indicator[12], '"');
    $goal_meta_link = $indicator[3];
    $goal_meta_link_page = $indicator[4];

    $frontmatter = "---\n";
    $frontmatter .= "title: \"$indicator_title\"\n";
    $frontmatter .= "permalink: /$indicator_short/\n";
    $frontmatter .= "sdg_goal: $goal_num\n";
    $frontmatter .= "layout: indicator\n";

    $frontmatter .= "indicator: \"$indicator_id\"\n";
    $frontmatter .= "indicator_variable: \n";
    $frontmatter .= "graph: \n";
    $frontmatter .= "variable_description: \n";
    $frontmatter .= "variable_notes: \n";

    $frontmatter .= "target_id: \"$target_id\"\n";
    $frontmatter .= "has_metadata: $has_metadata\n";
    $frontmatter .= "rationale_interpretation: \"$rationale_interpretation\"\n";
    $frontmatter .= "goal_meta_link: \"$goal_meta_link\"\n";
    $frontmatter .= "goal_meta_link_page: $goal_meta_link_page\n";    

    $frontmatter .= "indicator_name: \"$indicator_title\"\n";
    $frontmatter .= "target: \"$indicator_target\"\n";
    $frontmatter .= "indicator_definition: \"$indicator_definition\"\n";
    $frontmatter .= "actual_indicator_available: \n";
    $frontmatter .= "actual_indicator_available_description: \n";
    $frontmatter .= "method_of_computation: \"$indicator_method\"\n";
    $frontmatter .= "comments_and_limitations: \n";
    $frontmatter .= "periodicity: \n";
    $frontmatter .= "time_period: \n";
    $frontmatter .= "unit_of_measure: \n";
    $frontmatter .= "disaggregation_categories: \n";
    $frontmatter .= "disaggregation_geography: \n";  
    $frontmatter .= "date_of_national_source_publication: \n";    
    $frontmatter .= "date_metadata_updated: \n";
    $frontmatter .= "scheduled_update_by_national_source: \n";
    $frontmatter .= "scheduled_update_by_SDG_team: \n";

    $frontmatter .= "source_agency_staff_name: \n";
    $frontmatter .= "source_agency_staff_email: \n";
    $frontmatter .= "source_agency_survey_dataset: \n";

    $frontmatter .= "source_title: \n";
    $frontmatter .= "source_url: \n";
    $frontmatter .= "source_notes: \n";

    $frontmatter .= "international_and_national_references: \n";

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
