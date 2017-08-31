<?php

include('lib/Boot.php');

// try
$image = new Image();
// catch
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
    <link rel="stylesheet" href="jcrop/css/jquery.Jcrop.css" type="text/css" />
    <script src="jcrop/js/jquery.min.js" type="text/javascript"></script>
    <script src="jcrop/js/jquery.Jcrop.js" type="text/javascript"></script>
    <script src="event-handlers.js" type="text/javascript"></script>
    <link rel="stylesheet" href="cropresize.css" type="text/css"/>
</head>

<body>

<div id="left_col">
    <img src="<?php echo $image->getUrl(); ?>" id="target" alt="<?php echo $image->getName(); ?>" />
    <form action="#" id="crop_submit" method="post">
        <input type="hidden" id="x" name="x" />
        <input type="hidden" id="y" name="y" />
        <input type="hidden" id="w" name="w" />
        <input type="hidden" id="h" name="h" />
        <input type="hidden" id="image_url" name="imageUrl" value="<?php echo $image->getUrl(); ?>" />
        <input type="hidden" id="image_name" name="fileName" value="<?php echo $image->getName(); ?>" />
        <input type="hidden" id="folder_name" name="folderName" value="<?php echo $image->getFolderName(); ?>" />
        <input type="hidden" id="image_quality" name="imageQuality" value="90" />
        <input type="submit" value="Crop Image" style="float:left; width: 98px;" />
    </form>
</div>

<div id="right_col">
    <h2 id="heading">Settings</h2>
    <ul class="setting_list">
        <li><label for="crop_width" class="crop_label">Crop Width:</label><input type="text" id="crop_width" class="crop_input"  name="img_width" value="" /> <span class="field_hint">pixels</span></li>
        <li><label for="crop_height" class="crop_label">Crop Height:</label><input type="text" id="crop_height" class="crop_input" name="img_height" value="" /> <span class="field_hint">pixels</span></li>
        <li><label for="crop_image_quality" class="crop_label">Image Quality:</label><input type="text" id="crop_image_quality" class="crop_input" name="crop_image_quality" value="90" /> <span class="field_hint">%</span></li>
        <!--<li><label for="crop_image_filename" class="full_label">New Filename:</label><input type="text" id="crop_image_filename" class="full_input" name="crop_image_filename" value="<?php /*echo $image->getName(); */?>" /></li>-->
    </ul>
    <!--<p class="crop_note">Your new image will be saved in folder <strong><?php /*echo $image->getFolderName(); */?></strong>.</p>-->
    <p id="crop_completed_value"></p>

</div>

</body>
</html>

