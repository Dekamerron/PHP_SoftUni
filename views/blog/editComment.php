<div class="main edit-comment">
	<form action="/blog/editComment/<?= $this->article_id; ?>/<?= $this->comment_id; ?>" method="POST">
		<div class="comments">
			<p class="comment-from">
				From: <b><?php echo $this->comment_user; ?></b>
			</p>
			<p class="comment-date">
				Date: <?php echo $this->comment_date; ?>
			</p>
			<br>
			<textarea name="comment-content" cols="113" rows="10"><?php echo stripslashes(str_replace('\r\n', PHP_EOL, $this->comment_content)); ?></textarea>
			<br>
			<input type="submit" value="Edit" class="button">
			<a href="/blog/view/<?= $this->article_id; ?>" id="go-back-button">
				<button type="button" class="button">Go back</button>
			</a>
		</div>
	</form>
</div>