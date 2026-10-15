<?php
require 'crud_bootstrap.php';
$id=valid_id($_GET['id']??$_POST['id']??null);$pid=profile_id($conn);$error='';$skill_name='';
if(!$id){http_response_code(400);exit('Invalid record ID');}
$stmt=mysqli_prepare($conn, 'SELECT skill_name FROM skill WHERE id = ? AND profile_id = ?');
if($stmt){
    mysqli_stmt_bind_param($stmt, 'ii', $id, $pid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $sname);
    if(mysqli_stmt_fetch($stmt)){ $skill_name = $sname; }
    mysqli_stmt_close($stmt);
}
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 // TODO 3: Fill DELETE SQL with id AND profile_id conditions.
 $sql='DELETE FROM skill WHERE id = ? AND profile_id = ?';
 $stmt=mysqli_prepare($conn,$sql);
 if(!$stmt)$error='SQL not ready: review TODO 3.';
 else{mysqli_stmt_bind_param($stmt,'ii',$id,$pid);if(mysqli_stmt_execute($stmt)){mysqli_stmt_close($stmt);header('Location: skills.php?success=deleted');exit;} $error='Delete failed.';mysqli_stmt_close($stmt);}
}
page_start('DELETE | Confirm Skill Removal');
if($error)echo '<p class="err">'.h($error).'</p>';
?>
<p>Delete skill <strong>"<?php echo h($skill_name ?: ('record #' . $id)); ?>"</strong>?</p><form method="post"><?php csrf_field(); ?><input type="hidden" name="id" value="<?php echo h($id); ?>"><button type="submit" class="danger">Yes, delete this skill</button> <a href="skills.php">Cancel</a></form><?php page_end(); ?>
