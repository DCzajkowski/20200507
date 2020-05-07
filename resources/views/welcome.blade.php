<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>
        <div id="app">
            <form action="#" method="#">
                <label for="search">
                    <input type="text" name="search" id="search">
                </label>

                <search-input item-key="name_and_email" field-name="email" url="/api/users"></search-input>

                <button type="submit">Search...</button>
            </form>

            <br>

            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus quia, nulla! Maiores et perferendis eaque, exercitationem praesentium nihil.
        </div>

        <script src="/js/app.js"></script>
    </body>
</html>
