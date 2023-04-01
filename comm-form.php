<?php

$display_form = ($_SERVER['REQUEST_METHOD'] == 'GET') ? true : false;
$errors = [];

var_dump($_SERVER['REQUEST_METHOD']); // IN LINE CODE lazem nist faghat baraye taiin vazeiyate hast ke ya get hast va ya post 



if ($display_form != true) {
    $name = $_POST['name'];
    $email = $_POST['email'];   
    $website = $_POST['website']; 
    $comment = $_POST['comment'];
    $gender = isset( $_POST['gender']) ? $_POST['gender'] : '';
    $status = $_POST['status'];
    $law = isset($_POST['law']) ? $_POST['law'] : '';

        
    if ($name == '' ) {
        //mitonim be jaye sharte bala az in ham estefade bokonim empty($name) dar email estefade kardim
        $errors[] = 'please enter a name. ';
    }elseif(strlen($name) < 3){
        $errors[] = 'the name must be more than 3 chars';
    }

    if(empty($email)){
        $errors[] = 'please enter your email'; 
    }elseif(! filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = 'email is invalid';
    }

    if(empty($website)){
        $errors[]= 'please enter your website';
    }elseif(! filter_var('http://'.$website, FILTER_VALIDATE_URL)){
        $errors[] ='website is inmvalid';
    }

    if($comment == ''){
        $errors[] = 'please enter your comment';
    }elseif( strlen($comment) < 5){
        $errors[] = 'your comment must be more than 5 chars';
    }

    if(! in_array($status, ['important' , 'medium', 'low'])){
        $errors[] = 'status is invalid.';
    }

    if(! in_array($gender , ['male' , 'female'])){
        $errors[] = 'gender is invalid';
    }

    if($law == ''){
        $errors[] = 'please check law';
    }

    if (count($errors) > 0) {
        $display_form = true;
    }
}
 
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/com-form.css">
    <title>comment form</title>
    <style>
       
    </style>
</head>

<body>

    <?php if ($display_form == true) : ?>

        <main>
            <h2 class="center">comment form</h2>
            <?php if (count($errors) > 0) : ?>
                <div id="error" class="err">
                    <ul>
                        <!-- <li><span class="error">name is required</span></li>
                        <?php foreach($errors as $error) : ?> -->
                            <li><span class="error"><?= $error ?></span></li>
                        <?php endforeach ?>
                    </ul>  
                </div>
            <?php endif ?>
            <form method="post">
                <div class="form-control">
                    <label for="name">name :</label>
                    <input type="text" id="name" name="name" class="names" value="<?= isset($name) ? $name : ''?>">
                </div>
                <div class="form-control">
                    <label for="email">e-mail :</label>
                    <input type="text" id="email" name="email" class="emails" value="<?= isset($email) ? $email : ''?>">
                </div>
                <div class="form-control">
                    <label for="website">website :</label>
                    <input type="text" id="website" name="website" class="websites" value="<?= isset($website) ? $website : ''?>">
                </div>
                <div class="form-control">
                    <label for="comment">comment : </label>
                    <textarea name="comment" id="comment" cols="40" rows="5" class="comments"><?= isset($comment) ? $comment : '' ?></textarea>
                </div>
                <div class="form-control">
                    <label for="status">status :</label>
                    <select name="status" id="status" class="status">
                        <option value="">please chose your status</option>
                        <option value="important" <?= (isset($status) and $status == 'important') ? 'selected' : '' ?>>important</option>
                        <option value="medium" <?= (isset($status) and $status == 'medium') ? 'selected' : '' ?> >medium</option>
                        <option value="low" <?= (isset($status) and $status == 'low') ? 'selected' : '' ?>>low</option>
                    </select>
                </div>
                <div class="form-control">
                    <label for="gender">gender :</label>
                    <input type="radio" name="gender" <?= (isset($gender) and $gender =='female') ? 'checked' : '' ?> value="female"> female
                    <input type="radio" name="gender" <?= (isset($gender) and $gender == 'male') ? 'checked' : '' ?> value="male"> male
                </div> 
                <div class="form-control">
                    <input type="checkbox" id="law" name="law" <?= (isset($law) and $law == 'law') ? 'checked' : '' ?> value="law" class="laws">I accept
                </div>
                <div class="sub">
                    <input type="submit" value="submit" class="submits">
                </div>
            </form>
        </main>
    <?php else : ?>

        <section>
            <table>
                <thead>
                    <tr>
                        <th>name</th>
                        <th>email</th>
                        <th>website</th>
                        <th>gender</th>
                        <th>status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>important</td>
                    </tr>
                </tbody>
                <thead>
                    <tr>
                        <th colspan="5">comment</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">this is a comment</td>
                    </tr>
                </tbody>
            </table>

        </section>
    <?php endif ?>

</body>

</html>