<?php

# get correct id for plugin
$thisfile=basename(__FILE__, ".php");
 
# register plugin
register_plugin(
	$thisfile, //Plugin id
	'hostingSpace', 	//Plugin name
	'1.1', 		//Plugin version
	'Mateusz Skrzypczak',  //Plugin author
	'https://discord.com/invite/9Sb8fUeyVc', //author website
	'Hosting Space detector (great for small hosting just like OVH 10MB)', //Plugin description
	'plugins', //page type - on which admin tab to display
	'hostingSpace'  //main function (administration)
);

i18n_merge('hostingSpace') || i18n_merge('hostingSpace', 'en_US');
 
# activate filter 
  
# add a link in the admin tab 'theme'
add_action('plugins-sidebar','createSideMenu',array($thisfile,i18n_r("hostingSpace/HOSTINGNAME")));
 
add_action('footer','alert');


 
 
 
 
# functions
function hostingSpace() {
$dir = '../';
function folderSize ($dir)
{
    $size = 0;

    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folderSize($each);
    }

    return $size;
}
$bytes =folderSize($dir);
function formatSizeUnits($bytes)
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


global $sizeWebsite;
$sizeWebsite = formatSizeUnits($bytes);

global $sizeWebsiteValue;
$sizeWebsiteValue = str_replace(' MB','',$sizeWebsite);
 

 
global $SITEURL;


$MBlimit = @file_get_contents('../data/other/hostingSpace/mblimit.txt');

if(file_exists('../data/other/hostingSpace/mblimit.txt')){
    $sizeWebsiteWithoutMB = str_replace(' MB','',$sizeWebsite);
$limitLine = $sizeWebsiteWithoutMB/$MBlimit  * 100;
}else{
    $sizeWebsite=0;
    $limitLine=0;
};


echo'<h3>Hosting Space detector</h3>';

echo' <label for="file">'.i18n_r("hostingSpace/WEBSITEUSES").'</label><br>';

echo $sizeWebsite.'/<b>'.@file_get_contents('../data/other/hostingSpace/mblimit.txt').' MB<b><br>';

echo'<div style="width:100%;height:30px;background:#fff;border:solid 1px #ddd;margin-top:5px;overflow:hidden;"><div class="" style="width:'.$limitLine.'%;background:red;height:30px;"></div></div>';


echo'<form action="#" method="POST" style="background:#fafafa;color:#000;width:100%;padding:20px;border:solid 1px #ddd;box-sizing:border-box;margin-top:20px;">';
echo'<label for="limit">'.i18n_r("hostingSpace/INFORMADMIN").'</label>';
echo'<input type="text" name="limit" placeholder="'.i18n_r("hostingSpace/PLACEHOLDERMB").'" value="'.@file_get_contents('../data/other/hostingSpace/mblimit.txt').'" style="width:100%;padding:5px;box-sizing:border-box;margin:10px 0;">';
echo'<label for="email">'.i18n_r("hostingSpace/SPACEOVER").'</label>';
echo'<input type="email" value="'.@file_get_contents('../data/other/hostingSpace/email.txt').'" placeholder="example@example.com" name="email" style="width:100%;padding:5px;box-sizing:border-box;margin:10px 0;"/>';
echo'<input type="submit" value="'.i18n_r("hostingSpace/SAVESETTINGS").'" style="background:#000;border:none;padding:5px 15px;color:#fff;display:block;" name="submit">';
echo'</form>';

echo '  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" style="display:flex; width:100%;align-items:center;justify-content:space-between;margin-top:20px; ">
    <p style="margin:0;padding:0;">'.i18n_r("hostingSpace/PAYPAL").'</p>
    <input type="hidden" name="cmd" value="_s-xclick" />
    <input type="hidden" name="hosted_button_id" value="KFZ9MCBUKB7GL" />
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
    <img alt="" border="0" src="https://www.paypal.com/en_PL/i/scr/pixel.gif" width="1" height="1" />
    </form>';


if(isset($_POST['submit'])){


    $data = $_POST['email'];
    $limit = $_POST['limit'];
 
// Set up the folder name and its permissions
// Note the constant GSDATAOTHERPATH, which points to /path/to/getsimple/data/other/
$folder        = GSDATAOTHERPATH . '/hostingSpace/';
$filename      = $folder . 'email.txt';
$filelimit      = $folder . 'mblimit.txt';
$chmod_mode    = 0755;
$folder_exists = file_exists($folder) || mkdir($folder, $chmod_mode);
 
// Save the file (assuming that the folder indeed exists)
if ($folder_exists) {
  file_put_contents($filename, $data);
  file_put_contents($filelimit, $limit);
  echo("<meta http-equiv='refresh' content='0'>");
}


};


};


function alert(){
include('hostingSpace/alert.php');
}


 
 
?>


