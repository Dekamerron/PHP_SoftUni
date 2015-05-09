<?php foreach($this->articles as $article) : ?>
	<div class="container">
		<div class="article-header">
			<h1 class="article-title">
				<?php echo stripslashes(str_replace('\r\n','<br>', $article['title'])); ?>

			</h1>
		</div>
		<div class="article-content">
			<p class="first">
				<?php echo stripslashes(str_replace('\r\n','<br>', substr($article['content'], 0, 300))) . "..."; ?>
			</p>
		</div>
		<p>
			<?php if($this->isAdmin) : ?>
			<a href="/blog/editArticle/<?= $article['id'] ?>">
				<button class="button">Edit</button>
			</a>
			<a href="/blog/deleteArticle/<?= $article['id'] ?>">
				<button class="button" onClick="return confirmDelete()">Delete</button>
			</a>
			<?php endif; ?>

			<?php if($this->isLoggedIn) : ?>
			<a href="/blog/view/<?= $article['id'] ?>">
				<button type="button" class="button">View article</button>
			</a>
			<?php endif; ?>

			<span class="pull-right italic">
				Posted on <?php echo substr($article['date_created'], 0, 10); ?>
			</span>
		</p>
		<hr>
	</div>

<?php endforeach; ?>