<!DOCTYPE html>
<meta charset="utf-8">
<style type="text/css">

    .node {
        cursor: pointer;
    }

    .overlay{
        background-color:#EEE;
    }

    .node circle {
        fill: #fff;
        stroke: steelblue;
        stroke-width: 1.5px;
    }

    .node text {
        font-size:10px;
        font-family:sans-serif;
    }

    .link {
        fill: none;
        stroke: #ccc;
        stroke-width: 1.5px;
    }

    .templink {
        fill: none;
        stroke: red;
        stroke-width: 3px;
    }

    .ghostCircle.show{
        display:block;
    }

    .ghostCircle, .activeDrag .ghostCircle{
        display: none;
    }

</style>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://d3js.org/d3.v3.min.js"></script>
@stack('scripts')
<body>
    @yield('content')
</body>
</html>
