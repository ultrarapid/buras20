<?php ?>
<h1><span>Ultrarapid</span></h1>
				<h2><span>Webbutveckling &amp; grafisk design</span></h2>
				<ul id="nav">
					<li<?= ( ( $thisPage == 'live' ) ? ' class="active"' : '' ) ?>>
						<a href="<?= Anchors::Refer('live') ?>">vad h&auml;nder?</a>
					</li>
					<li<?= ( ( $thisPage == 'portfolio' ) ? ' class="active"' : '' ) ?>>
						<a href="<?= Anchors::Refer('portfolio') ?>.html">portfolio</a>
					</li>
					<li<?= ( ( $thisPage == 'om-oss' ) ? ' class="active"' : '' ) ?>>
						<a href="<?= Anchors::Refer('about') ?>.html">om oss</a>
					</li>
					<li<?= ( ( $thisPage == 'utveckling' ) ? ' class="active"' : '' ) ?>>
						<a href="<?= Anchors::Refer('dev') ?>.html">utveckling</a>
					</li>                    
				</ul>
			<div class="status<?= ( ( $thisPage == 'utveckling') ? ' devstatus' : '' ) ?>">
<?php	if ( $post['Post']['category'] == 2) : ?>
				<h3 class="bloggheader"><?= $post['Post']['title'] ?></h3>
<?php	endif; ?>
					<p><?= $post['Post']['body'] ?></p>
<?php		//echo $format->autoUrl($post['body']); ?>

<?php	$commentcount = 0;
		if ( array_key_exists('Comment', $post ) ) :
			foreach ( $post['Comment'] as $comment ) :
				$commentcount++;
			endforeach;
		endif; ?>
					<p class="commentwrap">
						<a class="commentlink" href="<?= Anchors::Refer('post') . '/' . $post['Post']['url'] ?>.html"><?= $commentcount ?></a>
					</p>
					<a href="<?= Anchors::Refer('post') . '/' . $post['Post']['url'] ?>.html" class="statusdate"><?= $post['Post']['created']//$format->easyDate($post['created']) ?></a>
<?php	if ( array_key_exists('Tag', $post) ) :
			foreach ( $post['Tag'] as $tag ) : ?>
					<a href="/<?= $tag['Tag']['tag'] ?>.html" class="taglink"><?= $tag['Tag']['tag'] ?></a>
<?php		endforeach;
		endif;				
/*	
		if($category == 2){
			foreach($posts['Tag'] as $tag){
				echo $html->link($tag['tag'], "../".$tag['tag'].".html", array('class'=>'taglink'));
			}
		}else{
			echo "<a class=\"tweeta\" href=\"http://www.twitter.com/_ultrarapid/status/".$id."\">Twitter</a>";
		}
		*/
		?>
            <div class="commentdisplay">
				<a class="commentref" href="#kommentera">Kommentera</a>
<?php	if ( array_key_exists('Comment', $post) ) : 
			foreach ( $post['Comment'] as $comment ) : ?>
                <p class="commenttext"><?= $comment['Comment']['text'] ?></p>
                <p class="commentname"><?= $comment['Comment']['name'] ?></p>
<?php			if ( isset($comment['Comment']['homepage']) ) : ?>
                <p class="commenthomepage">
                    <a href="<?= $comment['Comment']['homepage'] ?>"><?= $comment['Comment']['homepage'] ?></a>
                </p>
<?php			endif; ?>
				<p class="commentdate"><?= $comment['Comment']['created'] ?></p>
<?php		endforeach; 
		endif; ?>
		<?php /*
		echo $form->create('Post.Comment', 
						   array('url' => array('controller' => 'comments', 'action' => 'add', $url), 
								 'class' => 'commentform', 'id' => 'kommentera'))."\n\t\t\t";	

		//echo $form->create('Post.Comment', array('url' => '/comments/add/'.$url, 'class' => 'commentform', 'id' => 'kommentera')) ."\n\t\t\t";	
		echo $form->input('name', array('label' => array('class' => 'formlabel', 'text' => 'Namn:')))."\n\t\t\t\t";
		echo $form->input('email', array('label' => array('class' => 'formlabel', 'text' => 'Epost (visas ej):')))."\n\t\t\t\t";
		echo $form->input('homepage', array('label' => array('class' => 'formlabel', 'text' => 'Hemsida:')))."\n\t\t\t\t";
		echo $form->input('fix', array('label' => array('class' => 'formlabel', 'text' => 'Skriv "hej" h&auml;r (spam kontroll):')))."\n\t\t\t\t";
		echo $form->input('text', array('label' => array('class' => 'formlabel', 'text' => 'Kommentar:')))."\n\t\t\t\t";
		echo $form->hidden('post_id', array('value' => $id))."\n\t\t\t";
		echo $form->end('Kommentera');
		
		echo "\n\t\t</div>"; */
?>
			<form class="commentform" id="kommentera" method="post" action="<?= $_SERVER['REQUEST_URI'] ?>">
				<div class="input">
					<label for="CommentName" class="formlabel">Namn:</label>
					<input name="data[Comment][name]" type="text" maxlength="255" value="" id="CommentName" />
				</div> 
				<div class="input">
					<label for="CommentEmail" class="formlabel">Epost (visas ej):</label>
					<input name="data[Comment][email]" type="text" maxlength="255" value="" id="CommentEmail" />
				</div>
				<div class="input">
					<label for="CommentHomepage" class="formlabel">Hemsida:</label>
					<input name="data[Comment][homepage]" type="text" maxlength="255" value="" id="CommentHomepage" />
				</div> 
				<div class="input">
					<label for="CommentFix" class="formlabel">Skriv "hej" h&auml;r (spam kontroll):</label>
					<input name="data[fix]" type="text" value="" id="CommentFix" />
				</div> 
				<div class="input">
					<label for="CommentText" class="formlabel">Kommentar:</label>
					<textarea name="data[Comment][text]" cols="30" rows="6" id="CommentText" ></textarea>
				</div> 
				<input type="hidden" name="data[Comment][post_id]" value="<?= $post['Post']['id'] ?>" id="CommentPostId" /> 
				<div class="submit">
					<input type="submit" value="Kommentera" />
				</div>
			</form>
		</div>
<?php 
/*		
		
	}else{
		foreach($posts as $post){
			$category 	= $post['Post']['category'];
			$title 		= $post['Post']['title'];
			$body		= $post['Post']['body'];
			$url		= $post['Post']['url'];
			$created	= $post['Post']['created'];		
			
			echo "\t<div class=\"status\">";
			echo (($category == 2) ? "\n\t\t<h3 class=\"bloggheader\">".$title."</h3>" : "");		
			echo "\n\t\t<p>";
			echo $format->autoUrl($body);
			echo "</p>\n\t\t";
			$commentcount = 0;
			
			foreach($post['Comment'] as $comment){
				$commentcount++;		
			}
			
			echo "<p class=\"commentwrap\">";
			
			echo $html->link($commentcount, '/post/'.$url.'.html', array('class'=>'commentlink'));
			echo "</p>";
			echo "<a href=\"/post/".$url.".html\" class=\"statusdate\">".$format->easyDate($created)."</a>";
			
			if($category == 2){
				foreach($post['Tag'] as $tag){
					echo $html->link($tag['tag'], "../".$tag['tag'].".html", array('class'=>'taglink'));
				}
			}else{
				echo "<a class=\"tweeta\" href=\"http://www.twitter.com/_ultrarapid/status/".$post['Post']['id']."\">Twitter</a>";
			}
	
			echo "\n\t</div>\n";
		}		
	}	
	
	echo $javascript->link('jquery-1.4.2.js', true);
	echo $javascript->link('jquery.fancybox-1.3.0.pack.js', true);
	echo $javascript->link('posts.js', true);
		*/
?>