<?php
require_once('index.php');
$page=$content;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $filename;?></title>
</head>
<body>
<?php
echo "<textarea readonly='readonly'>{$content}</textarea>"
?>
</body>
</html>