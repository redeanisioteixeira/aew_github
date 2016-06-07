<?php echo $this->doctype(); ?>
<html lang="pt-BR">
<head>
<?php echo $this->headMeta(); ?>
<?php echo $this->headTitle(); ?>
<?php echo $this->headScript(); ?>
<?php echo $this->headLink(); ?>
</head>
    <body>
		
		<div class="container">
			<?php echo $this->layout()->content;?>
		</div>
   </body>
</html>