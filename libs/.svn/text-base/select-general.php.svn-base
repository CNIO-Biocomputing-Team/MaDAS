<select name="<?=$name?>" id="<?=$name?>" class="<?=$class?>">
    <option value="">Please select...</option>
    <?
        foreach ($array as $k=>$v){
            echo '<option value="'.$v.'" ';
            if (($v == $value) or ($k == $value))
                echo ' selected="selected" ';
            echo '>'.$k.'</option>'."\n";
        }
    ?>
</select>
