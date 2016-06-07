<?php foreach ($this->blogs as $blog): 
    $this->blog = $blog;
    echo $this->render('blog/blog.php');
?>
<?php endforeach; ?>