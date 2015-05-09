<div class="main">
	<form action="/blog/editArticle/<?= $this->article_id ?>" method="POST">
		<h2><label for="article_title">Article title</label></h2>
		<input type="text" name="article_title" id="article_title" maxlength="100" size="100"
		value="<?= $this->article_title; ?>">

		<h2><label for="article_content">Article body</label></h2>
		<textarea name="article_content" name="article_content" id="article_content" rows="20" cols="130"><?= $this->article_content; ?></textarea>
		<br>
		<input type="submit" value="Submit" name="submit_article" class="button">

		<a href="/blog/deleteArticle/<?= $this->article_id ?>">
			<button type="button" class="button" onClick="return confirmDelete()">Delete</button>
		</a>

		<a href="/blog/view/<?= $this->article_id ?>">
			<button type="button" class="button">View comments</button>
		</a>

		<a href="/blog/index" id="go-back-button">
			<button type="button" class="button">Go back</button>
		</a>
	</form>
</div>