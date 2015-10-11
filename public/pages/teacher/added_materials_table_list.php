<tr>
    <td><a href="?show_file"><?php echo $found['title']?></a></td>
    <td><?php echo $found['subject']?></td>
    <td><?php echo $found['grade']?></td>
    <td><?php echo $found['category']?></td>
    <td><?php echo $found['type']?></td>
    <td>
        <form method="post" action="">
            <input hidden="hidden" name="material_id" value="<?php echo $found['id']?>">
            <input class="form-control" name="Remove_Material" type="submit" value="Remove">
        </form>
    </td>
</tr>