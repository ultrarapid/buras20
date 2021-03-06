<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?= isset($layoutTitle) ? $layoutTitle : ( isset($section['Section']['title']) && $section['Section']['title'] != '' ? $section['Section']['title'] : 'Burås Innebandy - Göteborg'); ?></title>
<?php if ( isset($layoutMetaDescription) ) : ?>
    <meta name="description" content="<?= $layoutMetaDescription ?>" />
<?php endif; ?>
<?php if ( isset($layoutMetaKeywords) ) : ?>
    <meta name="keywords" content="<?= $layoutMetaKeywords ?>" />
<?php endif; ?>
    <link rel="stylesheet" type="text/css" href="<?= Anchors::Refer('stylesheet_folder') ?>/style.css" />
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Arimo:400,700,900|Droid+Serif:400,700,700italic,400italic" />
    <link rel="shortcut icon" href="<?= Anchors::Refer('base') ?>/favicon.ico" />
    <link rel="apple-touch-icon" href="<?= Anchors::Refer('base') ?>/phone-small.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="<?= Anchors::Refer('base') ?>/phone-large.png" />
    <script type="text/javascript">
      document.documentElement.className = 'jsActive';
    </script>
    <!--[if lt IE 9]>
      <script src="<?= Anchors::Refer('javascript_folder') ?>/html5-js/dist/html5shiv.js"></script>
    <![endif]-->    
  </head>
  <body>
    <div class="section-login">
    </div>
    <div class="wrapper">
      <section class="section-header">
        <header>
          <h1>
            <a href="<?= Anchors::Refer('start') ?>"><span class="first-part">Burås</span> <span class="second-part">Innebandy</span></a>
          </h1>
        </header>
        <div class="section-extras-nav">
          <div class="extras">
            <article class="next-mens-game next-game">
              <header>
                <h3>Nästa herrmatch</h3>
              </header>
<?php if ( $nextMensGame ) : ?>
              <p><?= ( $nextMensGame['Game']['homegame'] ? 'Hemma' : 'Borta' ) . ' mot: ' . $nextMensGame['Game']['opponent'] ?></p>
              <time datetime="<?= substr($nextMensGame['Game']['gamedate'], 0, 16) ?>"><?= Formatter::ReadableDateWithDay($nextMensGame['Game']['gamedate']) . ' ' . substr($nextMensGame['Game']['gamedate'], 11, 5) ?></time>
              <p>@ <?= $nextMensGame['Game']['location'] ?></p>
<?php else : ?>
              <p>Ingen match bokad</p>
<?php endif; ?>
            </article>
            <article class="next-womens-game next-game">
              <header>
                <h3>Nästa dammatch</h3>
              </header>
<?php if ( $nextWomensGame ) : ?>
              <p><?= ( $nextWomensGame['Game']['homegame'] ? 'Hemma' : 'Borta' ) . ' mot: ' . $nextWomensGame['Game']['opponent'] ?></p>
              <time datetime="<?= substr($nextWomensGame['Game']['gamedate'], 0, 16) ?>"><?= Formatter::ReadableDateWithDay($nextWomensGame['Game']['gamedate']) . ' ' . substr($nextWomensGame['Game']['gamedate'], 11, 5) ?></time>
              <p>@ <?= $nextWomensGame['Game']['location'] ?></p>
<?php else : ?>
              <p>Ingen match bokad</p>
<?php endif; ?>
            </article>
            <article class="next-event">
              <header>
                <h3>Utanför planen</h3>
              </header>
<?php if ( $nextEvent ) : ?>
              <p><?= $nextEvent['Event']['header'] ?></p>
              <time datetime="<?= substr($nextEvent['Event']['eventdate'], 0, 16) ?>"><?= Formatter::ReadableDateWithDay($nextEvent['Event']['eventdate']) . ' ' . substr($nextEvent['Event']['eventdate'], 11, 5) ?></time>
              <p>@ <?= $nextEvent['Event']['location'] ?></p>
<?php else : ?>
              <p>Inget planerat</p>
<?php endif; ?>
            </article>            
          </div>
          <div class="navigation-wrapper">
            <nav class="nav main-nav">
<?php	if ( isset($layoutMenu) && !empty($layoutMenu) ) : ?>
              <ul>
<?php		foreach ( $layoutMenu as $mo ) : ?>
                <li>
                  <a <?= ( ( $active_main == $mo['Section']['id'] ) ? 'class="active" ' : '' ) ?>href="<?= Anchors::Refer('base') . '/' . ( ( !empty($mo['Section']['url']) ) ? $mo['Section']['url'] . '.html' : '' ) ?>"><?= $mo['Section']['name'] ?></a>
                </li>
<?php		endforeach; ?>
              </ul>
<?php	endif; ?>
            </nav> 
<?php if ( isset($layoutSubMenu) && !empty($layoutSubMenu) ) : ?>
            <nav class="nav sub-nav">
              <ul>
<?php   foreach ( $layoutSubMenu as $smo ) : ?>
                <li>
                  <a <?= ( ( $active_sub == $smo['Section']['id'] ) ? 'class="active" ' : '' ) ?>href="<?= Anchors::Refer('base') . '/' . $smo['Section']['url'] ?>.html"><?= $smo['Section']['name'] ?></a>
                </li>              
<?php   endforeach; ?>
              </ul>
            </nav>
<?php endif; ?>
          </div>
        </div>
        <footer class="section-header-footer"></footer>
      </section>
      <div class="page">
        <div class="page-top"></div>
<?php if ( !isset($removeHeader) ) : ?>
        <div class="page-header-wrap">
          <h2 class="page-header"><?= $section['Section']['header'] ?></h2>
        </div>
<?php endif; ?>
        <div class="outer-content">          
<?= $layoutContent ?>
        </div>
      </div>    
    </div>    
<?php if ( isset($layoutBodyElements) ) : ?>
<?php   foreach ( $layoutBodyElements as $be ) : ?>
    <<?php echo $be['ElementName']; 
          foreach ( $be['Attributes'] as $a ) : 
            echo ' ' . $a['name'] . '="' . $a['value'] . '"'; 
          endforeach; ?> />
<?php   endforeach; ?>
<?php endif; ?>
    <script type="text/javascript" src="<?= Anchors::Refer('javascript_folder') . '/jquery-1.7.1.min' ?>.js"></script>
<?php	if ( isset($layoutJavascripts) ) : ?>
<?php		foreach	( $layoutJavascripts as $js ) : ?>
    <script type="text/javascript" src="<?= Anchors::Refer('javascript_folder') . '/' . $js ?>.js"></script>
<?php		endforeach; ?>
<?php	endif;?>
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-27216236-1']);
      _gaq.push(['_trackPageview']);
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
    <script type="text/javascript">
      document.documentElement.className = '';
    </script>
<?php if ( isset($toConsole) ) : ?>
    <script type="text/javascript">
<?php   if ( is_array($toConsole) ) : ?>
      console.log('<?php echo json_encode($toConsole); ?>');
<?php   else : ?>
      console.log('<?= $toConsole ?>');
<?php   endif; ?>
    </script>
<?php endif; ?>
    <form action="<?= $_SERVER['REQUEST_URI'] ?>">
      <input type="hidden" value="<?= Anchors::Refer('base') ?>" id="base" />
    </form>
  </body>
</html>