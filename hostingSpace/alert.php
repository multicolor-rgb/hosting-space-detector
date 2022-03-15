<?php

$limit = @file_get_contents('../data/other/hostingSpace/mblimit.txt');




$limitPercent95 = $limit * 0.95;

$dir = '../';
function folderSizez ($dir)
{
    $size = 0;

    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folderSizez($each);
    }

    return $size;
}
$bytes =folderSizez($dir);
function formatSizeUnitz($bytes)
    {
        if ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
};


$sizeWebsite = formatSizeUnitz($bytes);

$sizeWebsiteValue = str_replace(' MB','',$sizeWebsite);




$counter = 0;

 
     

    
if($sizeWebsiteValue > $limitPercent95 && $counter == 0  && $limit !== false ){
    


global $SITEURL;

$data = '1';
$data2 = 'done';
$folder        = GSDATAOTHERPATH . '/hostingSpace/';
$filename      = $folder . 'counter.txt';
$filenameDone      = $folder . 'emailDone.txt';
$chmod_mode    = 0755;
$folder_exists = file_exists($folder) || mkdir($folder, $chmod_mode);



if(file_exists($filename) && file_exists($filenameDone)){
    
echo '<script>document.querySelectorAll(".wrapper")[1].insertAdjacentHTML("afterbegin",`<div class="error">you are running out of space, please free up some space</div>`);</script>';    

}else{
    
    $message = 'Your website '.$SITEURL.' is more than 95% server usage';   

 
if ($folder_exists) {
  file_put_contents($filename, $data);
  file_put_contents($filenameDone, $data2);
};


$counterFile = file_get_contents('../data/other/hostingSpace/counter.txt');
$email = file_get_contents('../data/other/hostingSpace/email.txt');
$emailDone = file_get_contents('../data/other/hostingSpace/emailDone.txt');

    
      mail($email, 'Storage Alert!', $message);
      
};

};




if($sizeWebsiteValue < $limitPercent95){

$file_delete = '../data/other/hostingSpace/counter.txt';
$file_delete2 = '../data/other/hostingSpace/emailDone.txt';
 if (file_exists($file_delete)){unlink($file_delete);};
  if (file_exists($file_delete2)){unlink($file_delete2);};
  
};?>