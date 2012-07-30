<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?= $layoutHeader ?></title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" type="text/css" href="<?= Anchors::Refer('stylesheet_folder') ?>/admin.css" />
<?php	if ( isset($layoutStylesheets) ) : 
				foreach ( $layoutStylesheets as $css ) : ?>
    <link rel="stylesheet" type="text/css" href="<?= Anchors::Refer('stylesheet_folder') . '/' . $css['href'] . '.css' ?>"<?= ( (isset($css['media']) ) ? ' media="' . $css['media'] . '"' : '') ?> />
<?php 	endforeach;
		endif; ?>
  </head>
  <body>
    <div class="wrapper">
      <div class="top">
<?php /*
        <div class="logo">
          <a href="<?= Anchors::Refer('admin_start') ?>"><img src="<?= Anchors::Refer('graphics_folder') ?>/logo.png" alt="" /></a>
        </div>
		 */ ?>
      </div>
      <div class="div-settings">
        <ul>
          <li><a href="<?= Anchors::Refer('admin_user_edit') . '/' . $_SESSION['User']['id'] ?>">mina inst&auml;llningar</a></li>
          <li><a href="<?= Anchors::Refer('logout') ?>">logga ut</a></li>
        </ul>
      </div>
      <div class="menu">
        <ul class="admin-nav">
<?php  foreach ( $adminMenu as $amo ) : ?>
          <li>
            <a href="<?= ( ( !empty($amo['Section']['adminUrl']) ) ? Anchors::Refer('admin') . '/' . $amo['Section']['adminUrl'] : ( ( !empty($amo['Section']['target']) ) ? Anchors::Refer('admin') . '/' . $amo['Section']['target'] : Anchors::Refer('admin_section_default') . '/' . $amo['Section']['id'] ) ) ?>"><?= ( ( empty($amo['Section']['adminname']) ) ? $amo['Section']['name'] : $amo['Section']['adminname'] ) ?></a>
          </li>
<?php  endforeach; ?>
        </ul>
        <div class="clear"></div>
      </div>		
      <div class="submenu">
      </div>
      <div class="page">
<?= $layoutContent ?>
        <div class="cbg"></div>
      </div>           
    </div>
    <div class="footer">
      <p class="footer">Bur&aring;s IK - Administration<span class="twospace">||</span> Webbdesign: <a href="http://www.ultrarapid.se/">ultrarapid</a></p>
    </div>
<?php	if ( isset($layoutJavascripts) ) :
			foreach	( $layoutJavascripts as $js ) : ?>
    <script type="text/javascript" src="<?= Anchors::Refer('javascript_folder') . '/' . $js ?>.js"></script>
<?php		endforeach; 
		endif;?>
  </body>
</html>