<?php

class BlogModel extends BaseModel {

	public function getAll() {
		$statement = self::$db->query("SELECT * FROM posts ORDER BY date_created DESC");
		$result = array();
		while($row = $statement->fetch_assoc()) {
		    array_push($result, $row);
		}
		
		return $result;
	}

	public function findArticle($articleId) {
		$statement = self::$db->prepare("SELECT title, content, date_created, view_count FROM posts WHERE id = ?");
		$statement->bind_param("i", $articleId);
		$statement->execute();

		$result = $statement->get_result()->fetch_assoc();
		$viewCount = $result['view_count'] + 1;
		$increaseCountStatement = self::$db->prepare("UPDATE posts SET view_count = ? WHERE id = ?");
        $increaseCountStatement->bind_param("ii", $viewCount, $articleId);
        $increaseCountStatement->execute();

		return $result;
	}
	
	public function findComment($commentId) {
		$statement = self::$db->prepare("SELECT id, user_id, content, date_created FROM comments WHERE id = ?");
		$statement->bind_param("i", $commentId);
		$statement->execute();

		$commentFromDB = $statement->get_result()->fetch_assoc();
		$user = $this->getUser($commentFromDB['user_id']);

		$comment = array(
			'id' => $commentFromDB['id'],
			'content' =>  $commentFromDB['content'],
			'date_created' =>  substr($commentFromDB['date_created'], 0, 10),
			'user_id' =>  $commentFromDB['user_id'],
			'user' =>  $user['username']
		);

		return $comment;
	}

	public function checkDoesArticleExists($articleTitle) {
		$articleTitle = htmlspecialchars($articleTitle);

		$statement = self::$db->prepare("SELECT Id FROM posts WHERE title = ?");
		$statement->bind_param("s", $articleTitle);
		$statement->execute();

		$result = $statement->get_result()->fetch_assoc();

		if($result) {
			return true;
		}

		return false;
	}

	public function create($articleTitle, $articleContent) {
		$articleTitle = htmlspecialchars($articleTitle);
		$articleContent = htmlspecialchars($articleContent);

		$statement = self::$db->prepare("INSERT INTO posts (title, content) VALUES(?, ?)");
		$statement->bind_param("ss", $articleTitle, $articleContent);
		$statement->execute();

		return true;
	}

	public function editArticle($articleId, $articleTitle, $articleContent) {
		$articleTitle = htmlspecialchars($articleTitle);
		$articleContent = htmlspecialchars($articleContent);

		$statement = self::$db->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $statement->bind_param("ssi", $articleTitle, $articleContent, $articleId);
        $statement->execute();

        return $statement->errno == 0;
	}

	public function deleteArticle($articleId) {
		$commentsStatement = self::$db->prepare("DELETE FROM comments WHERE article_id = ?");
        $commentsStatement->bind_param("i", $articleId);
        $commentsStatement->execute();

        $articleStatement = self::$db->prepare("DELETE FROM posts WHERE id = ?");
        $articleStatement->bind_param("i", $articleId);
        $articleStatement->execute();

        return $articleStatement->affected_rows > 0;
	}

	public function editComment($commentId, $commentContent) {
		$commentContent = htmlspecialchars($commentContent);

		$statement = self::$db->prepare("UPDATE comments SET content = ? WHERE id = ?");
        $statement->bind_param("si", $commentContent, $commentId);
        $statement->execute();

        return $statement->errno == 0;
	}

	public function deleteComment($commentId) {
        $statement = self::$db->prepare("DELETE FROM comments WHERE id = ?");
        $statement->bind_param("i", $commentId);
        $statement->execute();

        return $statement->affected_rows > 0;
	}

	public function getComments($articleId) {
		$statement = self::$db->query("SELECT * FROM comments WHERE article_id = $articleId ORDER BY date_created DESC");

		$comments = array();
		while($row = $statement->fetch_assoc()) {
		    array_push($comments, $row);
		}

		$result = [];
		foreach ($comments as $comment) {
			$user = $this->getUser($comment['user_id']);
			$item = array(
				'id' => $comment['id'],
				'content' =>  $comment['content'],
				'article_id' =>  $comment['article_id'],
				'date_created' =>  substr($comment['date_created'], 0, 10),
				'user_id' =>  $comment['user_id'],
				'user' =>  $user['username']
			);

			array_push($result, $item);
		}

		return $result;
	}

	public function getUser($userId) {
		$statement = self::$db->prepare("SELECT username FROM users WHERE id = ?");
		$statement->bind_param("i", $userId);
		$statement->execute();

		return $statement->get_result()->fetch_assoc();
	}

	public function addComment($articleId, $userId, $commentContent) {
		$commentContent = htmlspecialchars($commentContent);

		$statement = self::$db->prepare("INSERT INTO comments (article_id, user_id, content) VALUES(?, ?, ?)");
		$statement->bind_param("iis", $articleId, $userId, $commentContent);
		$statement->execute();

		return true;
	}
}