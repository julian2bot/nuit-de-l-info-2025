<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Classement</title>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                   <th>Pseudo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>$user->name</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
