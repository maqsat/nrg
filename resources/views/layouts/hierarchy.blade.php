<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>Иерархия</title>
    <style>
        svg{
            display: block;
            margin: auto;
        }

        .node circle{
            fill: #fff;
            stroke: steelblue;
            stroke-width: 3px;
        }

        .node text{ font: 12px sans-serif; }

        .link{
            fill: none;
            stroke: #ccc;
            stroke-width: 2px;
        }
    </style>
</head>
<body>
<!-- partial:index.partial.html -->
@yield('content')
<!-- partial -->
<script src='/js/d3.min.js'></script>
@stack('scripts')
</body>
</html>

