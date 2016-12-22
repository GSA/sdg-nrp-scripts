<?php

$arguments = arguments($argv);

require 'vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;


// Load arguments passed from CLI 

if(empty($arguments['input'])) {
    echo "No input file path specified. Use --input=indicators/" . PHP_EOL . PHP_EOL; 
    exit;
}

if(!empty($arguments['output'])) {
    $output_path = $arguments['output'];
        
    if(!file_exists($output_path)) {
        echo "Creating output directory $output_path" . PHP_EOL . PHP_EOL;
        mkdir($output_path);
    }

} else {
    $output_path = '';
}


//$parse_file = file_get_contents('./data/source/master_indicator_database.csv');
//$csv = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $parse_file));

$csv = array();
if (($handle = fopen('./data/source/master_indicator_database.csv', "r")) !== FALSE) {
  while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
    $csv[] = $row;
  }
  fclose($handle);
}

// Load list of markdown files
$markdown_files = scandir($arguments['input']);

$count = 0;

foreach ($markdown_files as $key => $filename) {

    if (strlen($filename) < 3 || substr($filename, -3) != '.md') { continue; }

    $file = file_get_contents($arguments['input'] . $filename);

    $parser = new Mni\FrontYAML\Parser();
    $document = $parser->parse($file, false);

    $yaml = $document->getYAML();
    $markdown = $document->getContent();
    
    
    $new_values = array('graph_title' => null, 'un_designated_tier' => null, 'un_custodial_agency' => null);

    // Look for new values from spreadsheet
    foreach ($csv as $key => $indicator) {

        // Skip first row
        if ($key < 1) {
            continue;
        }

        if ($indicator[2] == $yaml['indicator']) {

            $new_values['graph_title'] = (!empty($indicator[38])) ? $indicator[38] : null;

            $new_values['un_designated_tier'] = (!empty($indicator[25])) ? $indicator[25] : null;
            $new_values['un_custodial_agency'] = (!empty($indicator[26])) ? $indicator[26] : null;

        }

    }
    reset($csv);

    // inject new values into YAML array
    $yaml_beginning = array_slice( $yaml , 0 , 7, true );
    $yaml_end = array_slice( $yaml , 7 , NULL, true );
    $yaml_middle = array('graph_title' => $new_values['graph_title']);

    $yaml = array_merge($yaml_beginning, $yaml_middle, $yaml_end);

    $yaml_beginning = array_slice( $yaml , 0 , 12, true );
    $yaml_end = array_slice( $yaml , 12 , NULL, true );
    $yaml_middle = array('un_designated_tier' => $new_values['un_designated_tier'], 'un_custodial_agency' => $new_values['un_custodial_agency']);

    $yaml = array_merge($yaml_beginning, $yaml_middle, $yaml_end);

    $yaml_text = "---\n" . Yaml::dump($yaml) . "---\n" . $markdown;

    file_put_contents($output_path . '/' . $filename, $yaml_text);
    $count++;
}

if ($count > 0) {
    echo "$count files converted" . PHP_EOL . PHP_EOL;
}

function arguments($argv) {
    $_ARG = array();
    foreach ($argv as $arg) {
      if (preg_match('/--([^=]+)=(.*)/',$arg,$reg)) {
        $_ARG[$reg[1]] = $reg[2];
      } elseif(preg_match('/-([a-zA-Z0-9])/',$arg,$reg)) {
            $_ARG[$reg[1]] = 'true';
        }
  
    }
  return $_ARG;
}


?>