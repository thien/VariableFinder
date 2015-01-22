<?php


$directory = '/Users/ghost/booker/real_system';
$showfoundfolders = FALSE; // you can use this to debug.


// -------------------------------------

echo "<pre>";
echo "<h1>COMP4 Variable and Function Finder</h1>";
echo "<p>Because you're lazy and you can't be bothered to sort through your code.</p>";
echo "<h2> Searching: ".$directory."</h2>";
$line = "<br><br>--------------------------------------------------------<br><br>";
$phps = array();
$final_list_of_variables = array();
$final_list_of_functions = array();
$folders = glob($directory . '/*' , GLOB_ONLYDIR);
array_push($folders, '/Users/ghost/booker/real_system');


if ($showfoundfolders == TRUE){
	echo "<h2>Folders Found:</h2><br>";
}
foreach ($folders as $folder){
	if ($showfoundfolders == TRUE){
		echo "<b>".$folder.'</b><br>';
	}
	foreach (scandir($folder) as $file){
		if (strpos($file, '.php') !== FALSE){
			array_push($phps, $folder."/".$file);
			if ($showfoundfolders == TRUE){
				echo " - ".$file."<br>";
			}
		}
	}
	if ($showfoundfolders == TRUE){
		echo "<br>";
	}
}
if ($showfoundfolders == TRUE){
	echo $line;
}
echo "<br>";

foreach ($phps as $php){ //goes through all php files in array.
	if (strpos($php,basename($_SERVER['SCRIPT_NAME'])) == FALSE){
		$file = file_get_contents($php); 
		preg_match_all('/\$[A-Za-z0-9-_]+/', $file, $vars_in_file); //gets all variables in php file.
		foreach ($vars_in_file[0] as $variable){
			if (!in_array($variable, $final_list_of_variables)){
				$smallindex = array($variable, str_replace($directory, '', $php));
				array_push($final_list_of_variables, $smallindex);
			} 
		}

		preg_match_all('/function[\s\n]+(\S+)[\s\n]*\(/', $file, $functs_in_file); //gets all functions in php file.
		foreach ($functs_in_file[0] as $function){
			if (!in_array($function, $final_list_of_functions)){
				$smallindex2 = array(str_replace('function', '', $function), str_replace($directory, '', $php));
				array_push($final_list_of_functions, $smallindex2);
			} 
		}
	}
}

$final_list_of_variables = array_map("unserialize", array_unique(array_map("serialize", $final_list_of_variables)));
$final_list_of_functions = array_map("unserialize", array_unique(array_map("serialize", $final_list_of_functions)));

//DISPLAY RESULTS
echo "<table>";
echo "<tr>
    <th>Variables</th><th>Location</th></tr>";
foreach ($final_list_of_variables as $var_array){
	array_unique($var_array);
		echo "<tr><td>".$var_array[0]."</td><td>".$var_array[1]."</td></tr>";
}
echo "</table>";
echo $line;
echo "<table>";
echo "<tr>
    <th>Functions</th><th>Location</th></tr>";
foreach ($final_list_of_functions as $var_array){
	array_unique($var_array);
		echo "<tr><td>".$var_array[0]."</td><td>".$var_array[1]."</td></tr>";
}
echo "</table>";

?>