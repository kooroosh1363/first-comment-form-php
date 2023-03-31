









<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/comm-form.css">
    <title>comment form</title>
</head>

<body>


    <main>
            <h2 class="center">comment form</h2>
            <div id="error" class="err">
                <ul>
                    <li><span class="error">name is required</span></li>
                    <li><span class="error">name is required</span></li>
                </ul>
            </div>
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
                <div class="form-control">
                    <input type="submit" value="submit" class="submits">
                </div>
            </form>
    </main>

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
                        <td>ali</td>
                        <td>raad@gmail.com</td>
                        <td>raad.com</td>
                        <td>male</td>
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

</body>

</html>