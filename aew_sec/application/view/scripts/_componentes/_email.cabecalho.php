<?php
$url_portal = str_replace('local.','',$this->baseUrl());
$url_portal = str_replace('desenv.','',$url_portal);
?>

<tr style="background-color: #DFDFDF">
    <td colspan="4" valign="top">
        <font style="background-image: url(<?php echo $url_portal;?>/assets/img/logo.png);background-repeat:no-repeat;padding: 5px 38px 10px;font-family:Arial;font-size:18px;color:#777">
            <b>Ambiente Educacional Web</b>
        </font>
    </td>
</tr>

<?php if($this->pageTitle != ""):?>
    <tr style="background-color:#6EA3D7">
        <td colspan="4" style="text-align:center;border-top:1px solid #FAFAFA;padding:5px">
            <span style="font-family:Arial;font-weight:bold;font-size:18px;color:#FAFAFA;letter-spacing:5px;"><?php echo $this->pageTitle;?></span>
        </td>
    </tr>
<?php endif;?>