<?php

$display_form = ($_SERVER['REQUEST_METHOD'] == 'GET') ? true : false;
$erros =[];

var_dump($_SERVER['REQUEST_METHOD']);// IN LINE CODE lazem nist faghat baraye taiin vazeiyate hast ke ya get hast va ya post 



if($display_form != true){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $website = $_POST['website'];
    $comment = $_POST['comment'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $law = $_POST['law'];
    

    if($name == ''){
        $eroros[] = 'please enter a name. ';

    }

    if(count($errors) > 0){
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
    <link rel="stylesheet" href="comm-form.css">
    <title>comment form</title>
</head>

<body>

    <?php if ($display_form == true) : ?>

        <main>
            <h2 class="center">comment form</h2>
            <?php if(count($errors) > 0): ?>
                <div id="error" class="err">
                    <ul>
                        <li><span class="error">name is required</span></li>
                        <li><span class="error">name is required</span></li>
                    </ul>
                </div>
            <?php endif ?>
            <form method="post">
                <div class="form-control">
                    <label for="name">name :</label>
                    <input type="text" id="name" name="name" class="names">
                </div>
                <div class="form-control">
                    <label for="email">e-mail :</label>
                    <input type="text" id="email" name="email" class="emails">
                </div>
                <div class="form-control">
                    <label for="website">website :</label>
                    <input type="text" id="website" name="website" class="websites">
                </div>
                <div class="form-control">
                    <label for="comment">comment : </label>
                    <textarea name="comment" id="comment" cols="40" rows="5" class="comments"></textarea>
                </div>
                <div class="form-control">
                    <label for="status">status :</label>
                    <select name="status" id="status" class="status">
                        <option value="important">important</option>
                        <option value="medium">medium</option>
                        <option value="low">low</option>
                    </select>
                </div>
                <div class="form-control">
                    <label for="gender">gender :</label>
                    <input type="radio" name="gender" value="female"> female
                    <input type="radio" name="gender" value="male"> male
                </div>
                <div class="form-control">
                    <input type="checkbox" id="law" name="law" value="law" class="laws">I accept
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