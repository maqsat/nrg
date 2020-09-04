@extends('layouts.hierarchy')

@section('content')
    <body></body>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    {{-- /hierarchy/{{ Auth::user()->id }}--}}

    <script>
        var treeData = {!! $data !!};

        var viewerWidth = $(document).width();
        var viewerHeight = $(document).height();
        // Set dimensions and margins for diagram
        var margin = {top: 80, bottom: 80},
            width = 600,
            height = 400 - margin.top - margin.bottom;

        // append the svg object to the body of the page
        // appends a 'group' element to 'svg'
        // moves the 'group' element to the top left margin
        // var zoom = d3.zoom().scale(.9);


        var transform = d3.zoomIdentity.translate(80,50).scale(.1);
        // var transform = d3.zoomIdentity.translate(50,margin.top).scale(.1);
        var zoom = d3.zoom().on("zoom", handleZoom);

        var svg = d3.select("body").append("svg")
            .attr("width", viewerWidth)
            .attr("height", viewerHeight)//height + margin.top + margin.bottom)
            .attr("viewBox", "0 0 180 180")
            .attr("preserveAspectRatio", "xMidYMid meet")
            .call(d3.zoom().on("zoom", function () {
                svg.attr("transform", d3.event.transform)
            }))
            .append("g")
            .attr("class", "zoomable")
            .attr("transform", transform);


        function handleZoom() {
            if (zoomable) {
                zoomable.attr("transform", d3.event.transform);
            }
        }

        // var svg = d3.select("body").append("svg")
        //     .attr("width", viewerWidth)
        //     .attr("height", viewerHeight)//height + margin.top + margin.bottom)
        //     .attr("viewBox","0 0 180 180")
        //     .attr("preserveAspectRatio","xMidYMid meet")
        //     .call(zoom)
        //     .call(d3.zoom().on("zoom", function () {
        //         svg.attr("transform", d3.event.transform)
        //     }))
        //     .append("g")
        //     .call(zoom)
        //     .attr("transform", "translate(0," + margin.top + ")" + " scale(.5,.5)");

        var i = 0,
            duration = 750,
            root;

        var nodeWidth = 300;
        var nodeHeight = 75;
        var horizontalSeparationBetweenNodes = 16;
        var verticalSeparationBetweenNodes = 128;

        var treemap = d3.tree()
            .nodeSize([nodeWidth + horizontalSeparationBetweenNodes, nodeHeight + verticalSeparationBetweenNodes])
            .separation(function (a, b) {
                return a.parent == b.parent ? 1 : 1.25;
            });


        // Declares a tree layout and assigns the size
        // var treemap = d3.tree().nodeSize([70, 40]).separation(function(a, b) {
        //     return a.parent == b.parent ? 1 : 1.25;
        // });
        // var treemap = d3.tree().nodeSize([height, width]);
        // var treemap = d3.tree().nodeSize([height, width]).size([width, height]);

        // Assigns parent, children, height, depth
        root = d3.hierarchy(treeData, function (d) {
            console.log(d.children);
            return d.children;
        });

        root.x0 = width / 2;
        root.y0 = 0;


        update(root);

        // Collapse the node and all it's children
        function collapse(d) {
            if (d.children) {
                d._children = d.children;
                d._children.forEach(collapse);
                d.children = null;
            }
        }

        // Update
        function update(source) {
            // Assigns the x and y position for the nodes
            var treeData = treemap(root);

            // Compute the new tree layout.
            var nodes = treeData.descendants(),
                links = treeData.descendants().slice(1);

            // Normalize for fixed-depth
            nodes.forEach(function (d) {
                d.y = d.depth * 100
            });

            // **************** Nodes Section ****************

            // Update the nodes...
            var node = svg.selectAll('g.node')
                .data(nodes, function (d) {
                    return d.id || (d.id = ++i);
                });

            // Enter any new nodes at the parent's previous position.
            var nodeEnter = node.enter().append('g')
                .attr('class', 'node')
                .attr("transform", function (d) {
                    return "translate(" + source.x0 + "," + source.y0 + ")";
                })
                .on('click', click);

            // Add Circle for the nodes
            nodeEnter.append('circle')
                .attr('class', 'node')
                .attr('r', 1e-6)
                .style("fill", function (d) {
                    return d._children ? "lightsteelblue" : "#fff";
                });

            // Add labels for the nodes
            nodeEnter.append('text')
                .attr("dy", ".35em")
                .attr("x", function (d) {
                    return d.children || d._children ? -13 : 13;
                })
                .attr("text-anchor", function (d) {
                    return d.children || d._children ? "end" : "start";
                })
                .text(function (d) {
                    return d.data.value;
                });

            // Update
            var nodeUpdate = nodeEnter.merge(node);

            // Transition to the proper position for the nodes
            nodeUpdate.transition()
                .duration(duration)
                .attr("transform", function (d) {
                    return "translate(" + d.x + "," + d.y + ")";
                });

            // Update the node attributes and style
            nodeUpdate.select('circle.node')
                .attr('r', 10)
                .style("fill", function (d) {
                    return d._children ? "lightsteelblue" : "#fff";
                })
                .attr('cursor', 'pointer');

            // Remove any exiting nodes
            nodeExit = node.exit().transition()
                .duration(duration)
                .attr("transform", function (d) {
                    return "translate(" + source.x + "," + source.y + ")";
                })
                .remove();

            // On exit reduce the node circles size to 0
            nodeExit.select('circle')
                .attr('r', 1e-6);

            // On exit reduce the opacity of text lables
            nodeExit.select('text')
                .style('fill-opacity', 1e-6)

            // **************** Links Section ****************

            // Update the links...
            var link = svg.selectAll('path.link')
                .data(links, function (d) {
                    return d.id;
                });

            // Enter any new links at the parent's previous position
            var linkEnter = link.enter().insert('path', "g")
                .attr("class", "link")
                .attr('d', function (d) {
                    var o = {x: source.x0, y: source.y0};
                    return diagonal(o, o);
                });

            // Update
            var linkUpdate = linkEnter.merge(link);

            // Transition back to the parent element position
            linkUpdate.transition()
                .duration(duration)
                .attr('d', function (d) {
                    return diagonal(d, d.parent)
                });

            // Remove any existing links
            var linkExit = link.exit().transition()
                .duration(duration)
                .attr('d', function (d) {
                    var o = {x: source.x, y: source.y};
                })
                .remove();

            // Store the old positions for transition.
            nodes.forEach(function (d) {
                d.x0 = d.x;
                d.y0 = d.y;
            });

            // Create a curved (diagonal) path from parent to the child nodes
            function diagonal(s, d) {
                path = `M ${s.x} ${s.y}
        C ${(s.x + d.x) / 2} ${s.y},
          ${(s.x + d.x) / 2} ${d.y},
          ${d.x} ${d.y}`

                return path;
            }

            // Toggle children on click
            function click(d) {
                if (d.children) {
                    d._children = d.children;
                    d.children = null;
                }
                else {
                    d.children = d._children;
                    d._children = null;
                }
                update(d);
            }
        }
    </script>
@endpush
