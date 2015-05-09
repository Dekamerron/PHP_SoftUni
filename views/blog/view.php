<div class="container min-height-single-view">
	<div class="article-header">
		<h1 class="article-title">
			<?php echo $this->article_title; ?>
		</h1>
	</div>
	<div class="article-content">
		<p class="first">
			<?php echo $this->article_content; ?>

		</p>
	</div>
	<p>
		<?php if($this->isAdmin) : ?>
		<a href="/blog/editArticle/<?= $this->article_id ?>">
			<button type="button" class="button">Edit</button>
		</a>
		<a href="/blog/deleteArticle/<?= $this->article_id ?>">
			<button type="button" class="button" onClick="return confirmDelete()">Delete</button>
		</a>
		<?php endif; ?>

		<a href="/blog/index" id="go-back-button">
			<button type="button" type="button" class="button">Go back</button>
		</a>
		
		<span class="italic pull-right">
			Posted on <?php echo substr($this->article_date, 0, 10); ?>
		</span>
	</p>
	<p>
		<span class="italic pull-right">
			View count: <?php echo $this->article_view_count + 1; ?>
		</span>
	</p>

	<hr>
	<?php foreach($this->comments as $comment) : ?>
		<div class="comments">
			<p class="comment-from">
				From: <b><?php echo $comment['user']; ?></b>
			</p>
			<p class="comment-date">
				<?php echo $comment['date_created']; ?>
			</p>
			<hr>
			<p>
				<?php echo stripslashes(str_replace('\r\n', "<br>", $comment['content'])); ?>
			</p>
			<?php if($this->isAdmin) : ?>
				<div class="pull-right">
					<a href="/blog/editComment/<?= $comment['article_id']; ?>/<?= $comment['id']; ?>">
						<button class="button">Edit</button>
					</a>
					<a href="/blog/deleteComment/<?= $comment['id']; ?>/<?= $comment['article_id']; ?>">
						<button class="button" onClick="return confirmDelete()">Delete</button>
					</a>
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
	<form action="/blog/addComment/<?= $this->article_id ?>" method="POST" id="new-comment-form">
		<label for="new-comment">Add new comment:</label>
		<textarea name="comment-content" id="new-comment" cols="113" rows="10"></textarea>
		<input type="submit" value="Comment" class="button">
		
	</form>
</div>