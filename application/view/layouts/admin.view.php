<!doctype html>
<html>
  <head>
    <title><?php echo $layoutHeader ?></title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="<?= Anchors::Refer('stylesheet_folder') ?>/admin.css" />
<?php	if ( isset($layoutStylesheets) ) : 
				foreach ( $layoutStylesheets as $css ) : ?>
    <link rel="stylesheet" type="text/css" href="<?= Anchors::Refer('stylesheet_folder') . '/' . $css['href'] . '.css' ?>"<?= ( (isset($css['media']) ) ? ' media="' . $css['media'] . '"' : '') ?> />
<?php 	endforeach;
		endif; ?>
  </head>
  <body class="admin-body">
    <div class="logo"><a href="<?php echo Anchors::Refer('admin_start') ?>">Hem</a></div>
    <div class="menu">
      <ul>
<?php  foreach ( $adminMenu as $amo ) : ?>
          <li<?php echo ( (str_replace(Anchors::Refer('admin') . '/', '', $_SERVER['REQUEST_URI']) == $amo['Section']['target'] ) ? ' class="active"' : '' ) ?>>
            <a href="<?= ( ( !empty($amo['Section']['adminUrl']) ) ? Anchors::Refer('admin') . '/' . $amo['Section']['adminUrl'] : ( ( !empty($amo['Section']['target']) ) ? Anchors::Refer('admin') . '/' . $amo['Section']['target'] : Anchors::Refer('admin_section_default') . '/' . $amo['Section']['id'] ) ) ?>"><?= ( ( empty($amo['Section']['adminname']) ) ? $amo['Section']['name'] : $amo['Section']['adminname'] ) ?></a>
          </li>
<?php  endforeach; ?>                       
      </ul>
    </div>
    <div class="submenu">
      <ul>
<?php if ( $section['Section']['allow_create'] == 1 ) : ?>
        <li><a class="btn-add" title="Lägg till" href="<?php echo Anchors::Refer('admin') . '/' . $section['Section']['controller'] . '/add/' . $section['Section']['params'] ?>">Lägg till</a></li>
<?php endif; ?>
<?php if ( strstr($_SERVER['REQUEST_URI'], '/add/') ) : ?>
        <li class="active"><a class="btn-add" title="Lägg till" href="<?php echo $_SERVER['REQUEST_URI'] ?>">Lägg till</a></li>
<?php endif; ?>
<?php if ( isset($adminCustomSubMenu) ) : ?>
<?php   foreach ( $adminCustomSubMenu as $acsmo ) : ?>
        <li<?php echo ( ( $acsmo['href'] == str_replace(Anchors::Refer('admin') . '/', '', $_SERVER['REQUEST_URI']) ) ? ' class="active"' : '' )  ?>><a<?php echo ( ( array_key_exists('class', $acsmo) ) ? ' class="' . $acsmo['class'] . '"' : ' class="btn"' ) . ( ( array_key_exists('title', $acsmo) ) ? ' title="' . $acsmo['title'] . '"' : '' ) ?> href="<?php echo Anchors::Refer('base') . $acsmo['href'] ?>"><?php echo $acsmo['text'] ?></a></li>
<?php   endforeach; ?>
<?php endif; ?>
<?php if ( isset($adminSubMenu) ) : ?>
<?php   foreach ( $adminSubMenu as $asmo ) : ?>
        <li<?php echo ( (str_replace('/admin/', '', $_SERVER['REQUEST_URI']) == $asmo['Section']['target'] ) ? ' class="active"' : '' ) ?>><a class="btn" href="<?= ( ( !empty($asmo['Section']['adminUrl']) ) ? Anchors::Refer('admin') . '/' . $asmo['Section']['adminUrl'] : ( ( !empty($asmo['Section']['target']) ) ? Anchors::Refer('admin') . '/' . $asmo['Section']['target'] : Anchors::Refer('admin_section_default') . '/' . $asmo['Section']['id'] ) ) ?>"><?= ( ( empty($asmo['Section']['adminname']) ) ? $asmo['Section']['name'] : $asmo['Section']['adminname'] ) ?></a></li>
<?php   endforeach; ?>
<?php endif; ?>
        <li><a class="btn-settings" href="<?php echo Anchors::Refer('admin_mysettings') ?>">Mina inställningar</a></li>
        <li><a class="btn-logout" href="<?= Anchors::Refer('logout') ?>">Logga ut</a></li>                
      </ul>
    </div>
    <div class="content">
      <h1><?php echo ( ( !isset($sectionHeader) ) ? $section['Section']['name'] : $sectionHeader ) ?></h1>
<?= $layoutContent ?>
    </div>
    <script type="text/javascript" src="<?= Anchors::Refer('javascript_folder') . '/jquery-1.7.1.min' ?>.js"></script>
<?php	if ( isset($layoutJavascripts) ) : ?>
<?php   foreach	( $layoutJavascripts as $js ) : ?>
    <script type="text/javascript" src="<?= Anchors::Refer('javascript_folder') . '/' . $js ?>.js"></script>
<?php		endforeach; ?>
<?php	endif; ?>
    <script type="text/javascript" src="<?= Anchors::Refer('javascript_folder') . '/admin-default' ?>.js"></script>      
    <script type="text/javascript" src="<?= Anchors::Refer('javascript_folder') . '/tiny_mce/jquery.tinymce'?>.js"></script>
    <script type="text/javascript">
      $().ready(function() {
        $('textarea.tinymce').tinymce({
          // Location of TinyMCE script
          script_url : <?= '"' . Anchors::Refer('javascript_folder') . '/tiny_mce/tiny_mce.js"' ?>,
    
          // General options
          //force_br_newlines : true,
          remove_linebreaks : true,
          relative_urls : false,
          convert_urls : true,
          cleanup : true,
          convert_newlines_to_brs: false,
          force_br_newlines: false,
          entity_encoding: "raw",
          theme : "advanced",
          plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
    
          // Theme options
          /*
          theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
          theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
          theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
          theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
          theme_advanced_toolbar_location : "top",
          theme_advanced_toolbar_align : "left",
          theme_advanced_statusbar_location : "bottom",
          theme_advanced_resizing : true,
          */
          theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontsizeselect",
          theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,blockquote,|,undo,redo,|,code,link,unlink,anchor,image,|,forecolor,backcolor",
          theme_advanced_buttons3 : "tablecontrols",
          theme_advanced_toolbar_location : "top",
          theme_advanced_toolbar_align : "left",
          theme_advanced_statusbar_location : "bottom",
          theme_advanced_resizing : true,


          // Example content CSS (should be your site CSS)
          content_css : <?= '"' . Anchors::Refer('stylesheet_folder') . '/css/style.css"' ?>,
    
          // Drop lists for link/image/media/template dialogs
          template_external_list_url : "lists/template_list.js",
          external_link_list_url : "lists/link_list.js",
          external_image_list_url : "lists/image_list.js",
          media_external_list_url : "lists/media_list.js",
    
          // Replace values for the template plugin
          template_replace_values : {
            username : "Some User",
            staffid : "991234"
          }
        });
      });
    </script>    
  </body>
</html>