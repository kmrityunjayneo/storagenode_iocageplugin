<?php 
$out = shell_exec("bash sample.sh fileA file2 fileCDE 2>&1 ");
echo "Sample Shell run : <br>" . $out ;
phpinfo();

?>
