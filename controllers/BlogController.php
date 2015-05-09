<?php

class BlogController extends BaseController {
	private $blogModel;

	public function onInit() {
		$this->title = "Blog articles";
		$this->blogModel = new BlogModel();
	}

		
	public function index () {
		$this->articles = $this->blogModel->getAll();

		$this->renderView(__FUNCTION__);
	}

	public function create() {
		$this->authorizeAdmin();

		$this->title = "Create an article";
		if (isset($_SESSION['article_title'])) {
			$this->article_title = $_SESSION['article_title'];
		}

		if (isset($_SESSION['article_content'])) {
			$this->article_content = $_SESSION['article_content'];
		}

		if ($this->isPost()) {
			$_SESSION['article_title'] = mysql_real_escape_string($_POST['article_title']);
			$_SESSION['article_content'] = mysql_real_escape_string($_POST['article_content']);

			$article_title = $_POST['article_title'];
			if ($article_title === null || $article_title === '' || strlen($article_title) < 5 || strlen($article_title) > 100) {
				$this->addErrorMessage("Article title must be in range [5...100] characters.");
				$this->redirect("blog", "create");
			}

			$doesArticleAlreadyExists = $this->blogModel->checkDoesArticleExists($article_title);

			if ($doesArticleAlreadyExists) {
				$this->addErrorMessage("Article with name '" . $article_title . "' already exists.");
				$this->redirect("blog", "create");
			}

			$article_content = $_POST['article_content'];
			if ($article_content === null || strlen($article_content) < 5 || strlen($article_content) > 5000) {
				$this->addErrorMessage("Article content must be in range [5...5000] characters.");
				$this->redirect("blog", "create");
			}

			if ($this->isAdmin) {
				$this->blogModel->create($article_title, $article_content);

				if (isset($_SESSION['article_title'])) {
					unset($_SESSION['article_title']);
				}

				if (isset($_SESSION['article_content'])) {
					unset($_SESSION['article_content']);
				}

				$this->addInfoMessage("Article successfully added.");
				$this->redirect("blog", "index");
			}
			else {
				$this->addErrorMessage("Article failed to add.");
			}
		}

		$this->renderView(__FUNCTION__);
	}

	public function editArticle($articleId) {
		$this->authorizeAdmin();

		$this->title = "Edit an article";

		if ($this->isPost()) {
			$articleTitle = $_POST['article_title'];
			$articleContent = $_POST['article_content'];
			$editedSuccessfully = $this->blogModel->editArticle($articleId, $articleTitle, $articleContent);

			if ($editedSuccessfully) {
				$this->addInfoMessage("Article successfully edited.");
			} 
			else {
				$this->addErrorMessage("Article failed to edit.");
			}

			$this->redirect("blog", "index");
		} 
		else {
			$article = $this->blogModel->findArticle($articleId);
			if ($article == null) {
				$this->redirect("blog", "index");
			}

			$this->article_title = $article['title'];
			$this->article_content = stripslashes(str_replace('\r\n', PHP_EOL, $article['content']));
			$this->article_id = $articleId;
		}

		$this->renderView(__FUNCTION__);
	}

	public function deleteArticle($articleId) {
		$this->authorizeAdmin();

		$successfullyDeleted = $this->blogModel->deleteArticle($articleId);

		if ($successfullyDeleted) {
			$this->addInfoMessage("Article successfully deleted.");
		}
		else {
			$this->addErrorMessage("Article failed to delete.");
		}

		$this->redirect("blog", "index");
	}

	public function editComment($articleId, $commentId) {
		$this->authorizeAdmin();

		$this->title = "Edit a comment";

		if ($this->isPost()) {
			$commentContent = $_POST['comment-content'];
			$editedSuccessfully = $this->blogModel->editComment($commentId, $commentContent);

			if ($editedSuccessfully) {
				$this->addInfoMessage("Comment successfully edited.");
			} 
			else {
				$this->addErrorMessage("Comment failed to edit.");
			}

			$this->redirect("blog", "view", array($articleId));
		} 
		else {
			$comment = $this->blogModel->findComment($commentId);
			if ($comment['id'] == null) {
				$this->redirect("blog", "view", array($articleId));
			}

			$this->comment_id = $comment['id'];
			$this->comment_content = $comment['content'];
			$this->comment_date = $comment['date_created'];
			$this->comment_user = $comment['user'];
			$this->article_id = $articleId;
		}

		$this->renderView(__FUNCTION__);
	}

	public function deleteComment($commentId, $articleId) {
		$this->authorizeAdmin();

		$successfullyDeleted = $this->blogModel->deleteComment($commentId);

		if ($successfullyDeleted) {
			$this->addInfoMessage("Comment successfully deleted.");
		}
		else {
			$this->addErrorMessage("Comment failed to delete.");
		}

		$this->redirect("blog", "view", array($articleId));
	}

	public function view($articleId) {
		$this->authorize();

		$article = $this->blogModel->findArticle($articleId);
		if ($article == null) {
			$this->redirect("blog", "index");
		}

		$this->article_title = $article['title'];
		$this->article_content = stripslashes(str_replace('\r\n', PHP_EOL, $article['content']));
		$this->article_date = $article['date_created'];
		$this->article_view_count = $article['view_count'];
		$this->article_id = $articleId;

		$this->comments = $this->blogModel->getComments($articleId);

		$this->renderView(__FUNCTION__);
	}

	public function addComment($articleId) {
		$this->authorize();

		$userId = $this->curentUserId();

		if ($userId != null) {
			$commentContent =  $_POST['comment-content'];
			if (strlen($commentContent) < 5 || strlen($commentContent) > 2000) {
				$this->addErrorMessage("Comment must be in range [5...2000].");
				$this->redirect("blog", "view", array($articleId));
			}

			$isCommentAddedSuccessfully = $this->blogModel->addComment($articleId, $userId, $commentContent);

			if ($isCommentAddedSuccessfully) {
				$this->addInfoMessage("Comment successfully added.");
			}
		}
		else {
			$this->addErrorMessage("Please login first.");
		}

		$this->redirect("blog", "view", array($articleId));
	}
}