Wordpress XMLRPC client
=======================

A simple XMLRPC client to work with Wordpress websites

## Features
* Support error logging to files with Monolog library
* Support UTF-8 content
* Wordpress methods:
  * Create a post
  * Create a category
  * Edit a post
  * Get authors
  * Get categories
  * Get tags
  * Get posts
  * Get a single post

## Usage

```php
# Your wordpress website is at: http://wp-website.com
$endpoint = "http://wp-website.com/xmlrpc.php";

# The logger instance
$wpLog = new \Illuminate\Log\Writer(new Monolog\Logger('wp-xmlrpc'));

# Save logs into file
$wpLog->useFiles('path-to-your-log-file');

# Create client instance
# The logger instance is optional
$wpClient = new \HieuLe\WordpressXmlrpcClient\WordpressClient($endpoint, 'username', 'password', $wpLog);
```

Create a post and get the created post id (http://codex.wordpress.org/XML-RPC_MetaWeblog_API#metaWeblog.newPost)
```php
$postId = $wpClient->createPost($title, $body, $category, $keywords, $customFields);
```

Create a category in this website (http://codex.wordpress.org/XML-RPC_WordPress_API/Categories_%26_Tags#wp.newCategory)
```php
$categoryId = $wpClient->addCategory($name, $slug, $parent_id, $description);
```

Edit a post with its post id (http://codex.wordpress.org/XML-RPC_MetaWeblog_API#metaWeblog.editPost)
```php
$result = $wpClient->editPost($idpost, $title, $body, $category, $keywords);
```

Get all authors in this website (http://codex.wordpress.org/XML-RPC_WordPress_API/Users#wp.getAuthors)
```php
$authors = $wpClient->getAuthors();
```

Get all categories in this website (http://codex.wordpress.org/XML-RPC_WordPress_API/Categories_%26_Tags#wp.getCategories)
```php
$categories = $wpClient->getCategories();
```

Get all tags in this website (http://codex.wordpress.org/XML-RPC_WordPress_API/Categories_%26_Tags#wp.getTags)
```php
$tags = $wpClient->getTags();
```

Get a post via its id (http://codex.wordpress.org/XML-RPC_MetaWeblog_API#metaWeblog.getPost)
```php
$post = $wpClient->getPost($id);
```

Get all post in this websites (http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPosts)
```php
$posts = $wpClient->getPosts();
```


With all method, if there is an error, the result will be `FALSE`, use the `===` operator to check whether an error occurs and get the error message with `getErrorMessage()` method.
