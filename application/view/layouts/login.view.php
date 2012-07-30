<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?= $layoutHeader ?></title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" type="text/css" href="<?= Anchors::Refer('stylesheet_folder') ?>/login.css" />
<?php	if ( isset($layoutStylesheets) ) : 
				foreach ( $layoutStylesheets as $css ) : ?>
    <link rel="stylesheet" type="text/css" href="<?= Anchors::Refer('stylesheet_folder') . '/' . $css['href'] . '.css' ?>"<?= ( (isset($css['media']) ) ? ' media="' . $css['media'] . '"' : '') ?> />
<?php 	endforeach;
		endif; ?>
  </head>
  <body>
<?= $layoutContent ?>  
  </body>
</html>