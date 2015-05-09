<!DOCTYPE html>
<html>
<head>
    <link href='http://fonts.googleapis.com/css?family=Clicker+Script' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/content/styles.css" />
    <link rel="stylesheet" href="/content/main.css" />
    <link rel="stylesheet" href="/content/text.css" />
    <title><?php echo htmlspecialchars($this->title) ?></title>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="/content/images/article_icon.jpg">
</head>

<body>
    
<header>
    <a href="/"><img src="/content/images/site-logo.png"></a>
    <ul class="menu">
        <li><a href="/">Home</a></li>
        <li><a href="/blog/index">Blog posts</a></li>
        <?php if($this->isAdmin) : ?>
            <li><a href="/blog/create">Write an article</a></li>
        <?php endif; ?>
        <li><a href="/accounts/login">Login</a></li>
        <li><a href="/accounts/register">Register</a></li>
    </ul>
    <?php if($this->isLoggedIn) : ?>
        <div id="logged-in-info">
            <span>
                Hello, <?php echo $_SESSION['username']; ?>
            </span>
            <form action="/accounts/logout">
                <input type="submit" value="Logout">
            </form>
        </div>
    <?php endif; ?>
</header>
<?php include_once('views/layouts/messages.php'); ?>