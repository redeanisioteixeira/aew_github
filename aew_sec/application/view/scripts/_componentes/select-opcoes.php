<?php foreach($this->objetos as $objeto=>$value):?>
    <?php if(!is_array($value)):?> 
        <option label="<?php echo $value;?>" value="<?php echo $objeto;?>"><?php echo $value;?></option>
    <?php endif;?>
    <?php if(is_array($value)):?>
        <optgroup label="<?php echo $objeto;?>">
            <?php foreach($value as $key => $v):?>
                <option label="<?php echo $v;?>" value="<?php echo $key;?>"><?php echo $v;?></option>
            <?php endforeach;?> 
        </optgroup>
    <?php endif;?>
<?php endforeach;?>
