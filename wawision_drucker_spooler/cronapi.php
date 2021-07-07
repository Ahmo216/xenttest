<?php


for($i=0;$i<59;$i++)
{
system("php /var/www/html/wawisionspooler/api.php getJobs");
sleep(1);
}

?>
