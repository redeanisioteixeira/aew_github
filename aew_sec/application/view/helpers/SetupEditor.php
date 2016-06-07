<?php
class Zend_View_Helper_SetupEditor extends Zend_View_Helper_Abstract
{
    public function setupEditor($textareaId)
    {
        return "<script type=\"text/javascript\"> CKEDITOR.replace('".$textareaId."', { toolbar : 'Basic' });
        </script>";
    }
}
?>
