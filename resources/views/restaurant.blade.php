<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <!-- Styles -->
</head>
<body>
<div class="container">

    <table class="table">
        <thead>
        <tr>
            <th>
                Name
            </th>
            <th>
                Description
            </th>

            <th>
                Link
            </th>

        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                {{$restaurant->name}}
            </td>
            <td>
                {{$restaurant->description}}
            </td>
            <td>
                <a href="{{$restaurant->url}}">Click</a>
            </td>
        </tr>

        </tbody>
    </table>
</div>

</body>
<script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>

</html>
