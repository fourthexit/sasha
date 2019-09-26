function BadiliDash(){
    this.default_zoom = {minZoom: 4, maxZoom: 24};
    this.base_style = {
        // "color": "#ffedad",
        "color": "#fff",
        "weight": 0.7
    };

    this.backgroundColor1 = [
        'rgba(255, 99,  132, 0.2)', 'rgba(54,  162, 235, 0.2)', 'rgba(255, 206, 86,  0.2)', 'rgba(75,  192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64,  0.2)'
    ];

    this.backgroundColor2 = [
        'rgba(255,128,128, 0.8)', 'rgba(255,156,128, 0.8)', 'rgba(255,184,128, 0.8)', 'rgba(255,212,128, 0.8)',
        'rgba(255,241,128, 0.8)', 'rgba(241,255,128, 0.8)', 'rgba(213,255,128, 0.8)', 'rgba(184,255,128, 0.8)',
        'rgba(156,255,128, 0.8)', 'rgba(128,255,128, 0.8)', 'rgba(128,255,156, 0.8)', 'rgba(128,255,184, 0.8)',
        'rgba(128,255,212, 0.8)', 'rgba(128,255,241, 0.8)', 'rgba(128,241,255, 0.8)', 'rgba(128,212,255, 0.8)',
        'rgba(128,184,255, 0.8)', 'rgba(128,156,255, 0.8)', 'rgba(128,128,255, 0.8)', 'rgba(156,128,255, 0.8)',
        'rgba(184,128,255, 0.8)', 'rgba(212,128,255, 0.8)', 'rgba(241,128,255, 0.8)', 'rgba(255,128,241, 0.8)',
        'rgba(255,128,213, 0.8)', 'rgba(255,128,184, 0.8)', 'rgba(255,128,156, 0.8)',
    ];

    this.bgColors = [
        [
            'rgba(255, 99,  132, 0.2)', 'rgba(54,  162, 235, 0.2)', 'rgba(255, 206, 86,  0.2)', 'rgba(75,  192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64,  0.2)'
        ],
        [
            '#ff8080', '#ff9c80', '#ffb880', '#ffd480', '#fff180', '#f1ff80', '#d5ff80', '#b8ff80', '#9cff80','#80ff80',
            '#80ff9c', '#80ffb8', '#80ffd4', '#80fff1', '#80f1ff', '#80d4ff', '#80b8ff', '#809cff', '#8080ff', '#9c80ff',
            '#b880ff', '#d480ff', '#f180ff', '#ff80f1', '#ff80d5', '#ff80b8', '#ff809c',
        ]
    ];

    this.country_colors = {
        'target_country': '#edb90e',
        'research_training': '#439305',
        'support_platform': '#cdf7b9',
        'others': '#f7f7f7'
    };
    this.proper_types = {
        'target_country': 'Target Country',
        'research_training': 'Research Training',
        'support_platform': 'Support Platform',
    };
    // this.center_lat = '0';
    this.center_lon = '33.5085';
    this.default_color = '#f57f21';
    this.has_initiated_varieties = false;
    this.has_initiated_tracker = false;
    this.has_initiated_dvm = false;
    this.base_varieties_colors = ["#1f77b4", "#aec7e8", "#ff7f0e", "#ffbb78", "#2ca02c", "#98df8a", "#d62728", "#ff9896", "#9467bd", "#c5b0d5", "#8c564b", "#c49c94", "#e377c2", "#f7b6d2", "#7f7f7f", "#c7c7c7", "#bcbd22", "#dbdb8d"];
    this.base_varieties_colors = ["#1f77b4", "#aec7e8", "#ffbb78", "#2ca02c", "#98df8a", "#d62728", "#ff9896", "#c5b0d5", "#8c564b", "#c49c94", "#e377c2", "#f7b6d2", "#ff7f0e", "#9467bd", "#7f7f7f", "#c7c7c7", "#fffdd0", "#ffff1a"];

    (function($) {
        $(document).on('click', '.toggle-header', function(){
            $('.header-content, #theme-header').toggleClass('hidden').animate();
        });
        $(document).on('click', '.reset_graphs', function(){
            dash.changeLevel('all');
        });

        $(document).on('click', '#progress_tracker, #varieties_released, #dvm_distribution', function(){
            dash.changeView(this);
            if(this.id == 'progress_tracker'){
                dash.changeLevel('all');
            }
            else if(this.id == 'dvm_distribution'){
                dash.initiateDVMViz();
            }
            else if(this.id == 'varieties_released'){
                dash.varieties_colors = {};
                $.each(bi_dash_data.varieties.metrics, function(i, metric){
                    dash.varieties_colors[metric.metric_code] = {'color': dash.base_varieties_colors[i], 'name': metric.metric_name};
                });
                dash.initiateVarietiesReleased();
            }
        });
        $(document).on('click', '#all_metrics .dd-handle', function(){ 
            dash.showSelectedMetrics(this.id);
        });
    }(jQuery));
};

BadiliDash.prototype.initiateMap = function(div, lat, lon, zoom, include_overlay = true){
    if (lat == undefined) {
        console.log('Zoom level is not set, using the default settings');
        dash.map = L.map(div, dash.default_zoom).setView([-0.055497, 23.862188], 4);
    }
    else {
        console.log('Settings are set');
        dash.map = L.map(div, dash.default_zoom).setView([lat, lon], zoom);
    }

    if(include_overlay){
        // L.TileLayer.MapQuestOpen.OSM().addTo(dash.map);
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="http://mapbox.com">Mapbox</a>',
            id: 'mapbox.streets',
            detectRetina: false
        }).addTo(dash.map);
    }
};

BadiliDash.prototype.clearLayers = function () {
    dash.map.eachLayer(function (layer) {
        if(layer._latlngs != undefined){
            dash.map.removeLayer(layer);
        }
    });
};

BadiliDash.prototype.initiateMapVisualization = function(div, center_lat, center_lon, zoom_level, show_bg=true, same_color=false){
    dash.initiateMap(div, center_lat, center_lon, zoom_level, show_bg);
    (function($) {
        $.each(bi_dash_data.geo_json, function(i, that){
            if(that.country_type != undefined){
                var cur_style = dash.base_style;
                if (dash.country_colors[that.country_type] != undefined){
                    if(same_color == true){
                        cur_style.color = dash.default_color;
                    }
                    else{
                        cur_style.color = dash.country_colors[that.country_type];
                    }
                    // $(sprintf('<li><a href="?country_view?code=%s"> %s</a></li>', that.iso_code, that.country_name)).appendTo('#countries_list');
                    var n_layer = L.geoJSON(JSON.parse(that.geometry), {style: cur_style, county_code: that.iso_code}).addTo(dash.map);

                    n_layer.on({click: function(){
                        clearTimeout(dash.cur_timeout);
                        dash.cur_timeout = setTimeout(function() { 
                            if(dash.cur_layer != undefined){
                                dash.cur_layer.setStyle({fillColor: dash.prevFillColor});
                            }
                            dash.prevFillColor = cur_style.color;
                            dash.changeLevel(that.iso_code); 
                            n_layer.setStyle({fillColor :'#8e5ea2'})
                            dash.cur_layer = n_layer;
                        }, 200);
                    }});

                    n_layer.on('mouseout', function(){
                        // clearTimeout(dash.cur_timeout);
                        // dash.cur_timeout = setTimeout(function() { dash.changeLevel('all'); }, 200);
                    });
                }
            }
        });
    }(jQuery));
};

BadiliDash.prototype.initiateProgressHighmaps = function(settings){
    var map_settings = {
        chart: {
            map: 'custom/africa'
        },
        credits: {
            enabled: false
        },
        title: {
            text: settings.map_title
        },
        colors: dash.backgroundColor1,
        plotOptions: {
            map: {
                allAreas: false,
                nullColor: 'rgba(0,0,0,0)'
            }
        },
        mapNavigation: {
            enabled: true,
            buttonOptions: {
                verticalAlign: 'bottom'
            },
            enableMouseWheelZoom: false
        },
        series: [settings.series][0]
    };
    Highcharts.mapChart('leaflet_map', map_settings);
};

BadiliDash.prototype.updateInfoDiv = function(data){
};

BadiliDash.prototype.beneficiariesReached = function(cur_year, country){
    (function($) {
        // change the title
        var title = (country == 'all') ? 'Overall Progress' : sprintf('%s Progress', bi_dash_data.metrics[country].country_name);
        // $('#section-title').html(title);
        var data = bi_dash_data.metrics[country];
        
        // var target = (data.target.all != undefined) ? data.target.all : data.target;
        var target = (data.target.all != undefined) ? 10000000 : data.target;
        var progress = parseFloat((dash.f_data[dash.f_data.length-1]/target)*100).toFixed(1);
        var add_title = (country == 'all') ? 'total' : bi_dash_data.metrics[country].country_name;
        var this_viz = sprintf('\
            <h5><strong>%s%%</strong> of vine beneficiaries reached in %s</h5><br />\
            <div class="progress progress-bar-default">\
                <div style="width: %s%%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="%s" role="progressbar" class="progress-bar">\
                    <span class="sr-only">%s%% Complete (success)</span>\
                </div>\
            </div>', progress, add_title, progress, progress, progress, progress);
        
        $('#ben_reached').html(this_viz);
        $('#ben_reached_title').html(sprintf('Progress in reaching %s beneficiaries', dash.numberWithCommas(target)));
        $('#ben_reached_title_small').html('As of Sept '+cur_year);

        try{
            if(dash.ben_reached_progress != undefined){
                dash.ben_reached_progress.destroy();
            }
            // line chart
            var ctx = $('#ben_reached_progress');
            dash.ben_reached_progress = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dash.f_labels,
                    datasets: [{
                        label: 'Beneficiaries',
                        data: dash.f_data,
                        borderColor: "#f88c00",
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(value, index, values) {
                                    return dash.numberWithCommas(value);
                                }
                            }
                        }]
                    },
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            fontColor: 'rgb(255, 99, 132)'
                        }
                    }
                }
            });
            var t = dash.numberWithCommas(bi_dash_data.metrics[country]['year_end_total']['all']);
            $('#ben_progress_title').html(sprintf('%s of Beneficiaries Reached', t));
            $('#ben_progress_title_small').html('As of Sept '+cur_year);
        }
        catch(err){
            alert(sprintf("I am missing progress data for '%s'. I can't create the beneficiaries trend graph", dash.cur_country));
            dash.ben_reached_progress.destroy();
        }

        // beneficiaries pie chart
        try{
            if(dash.beneficiaries_types != undefined){
                dash.beneficiaries_types.destroy();
            }
            var dtx = $('#beneficiaries_types');
            dtx.height = 850;
            dash.beneficiaries_types = new Chart(dtx, {
                type: 'pie',
                data: {
                    labels: dash.ben_labels,
                    datasets: [{
                        label: 'Beneficiaries',
                        data: dash.ben_data,
                        backgroundColor: ["#f88c00", "#8e5ea2", "#3cba9f"]
                    }]
                },
                options: {
                    legend: {
                        display: true,
                        labels: {
                            boxWidth: 20
                        }
                    },
                    pieceLabel: {
                      // render 'label', 'value', 'percentage', 'image' or custom function, default is 'percentage'
                      // mode 'label', 'value' or 'percentage', default is 'percentage'
                        mode: 'percentage',
                        // precision for percentage, default is 0
                        precision: 1,
                        // font size, default is defaultFontSize
                        fontSize: 12,
                        fontColor: '#fff'
                    },
                }
            });
            $('#ben_type_title').html('Direct vs Indirect Beneficiaries');
        }
        catch(err){
            alert(sprintf("I am missing progress data for '%s'. I can't create the beneficiaries type chart", dash.cur_country));
            dash.beneficiaries_types.destroy();
        }

        // organization column chart
        try{
            var projects_bar = $('#projects_bar');
            var categories = [], hh_reached = {name: 'All', data: [], pointWidth: 20, groupPadding: 0.15}, direct_ben = {name: 'Direct', data: [], pointWidth: 20, groupPadding: 0.15};
            var indirect_ben = {name: 'Indirect', data: [], pointWidth: 20, groupPadding: 0.15};
            $.each(bi_dash_data.metrics.org, function(i, that){
                categories.push(that.org_name);
                var dir_ben = (that.direct_ben == '0') ? 0 : that.direct_ben;
                var ind_ben = (that.indirect_ben == '0') ? 0 : that.indirect_ben;
                var hh = (that.hh_reached == '0') ? 0 : that.hh_reached;

                hh_reached.data.push({y:hh, real: dash.numberWithCommas(that.hh_reached)});
                direct_ben.data.push({y:dir_ben, real: dash.numberWithCommas(that.direct_ben)});
                indirect_ben.data.push({y:ind_ben, real: dash.numberWithCommas(that.indirect_ben)});
            });
            
            Highcharts.chart('projects_bar', {
                chart: {
                    type: 'column'
                },
                credits: {
                    text: 'Sweetpotato for Profit and Health Initiative (SPHI)',
                    href: 'http://sweetpotatoknowledge.org'
                },
                title: {
                    text: 'Beneficiary Reach by Organisation'
                },
                subtitle: {
                    text: 'For the year June 2016 - July 2017'
                },
                xAxis: {
                    categories: categories,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Beneficiaries Reached'
                    },
                    labels: {
                        enabled: true,
                        formatter: function() {
                            return dash.numberWithCommas(parseInt(this.value));
                        }
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.real}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true,
                            crop: false,
                            overflow: 'none'
                        },
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [hh_reached, direct_ben, indirect_ben], 
                colors: ["#f88c00", "#8e5ea2", "#3cba9f"]
            });
        }
        catch(err){
            console.log(err);
            alert(err);
        }
    }(jQuery));
        // $('#ben_type_title_small').html('As of '+ cur_year);
};

BadiliDash.prototype.formatLineData = function(data){
    (function($) {
        var sorted = [];
        for(var key in data) {
            sorted[sorted.length] = key;
        }
        sorted.sort();
        dash.f_data = [];
        dash.f_labels = [];
        var cur_value = 0;
        $.each(sorted, function(i, t_key){
            if(t_key != 'all'){
                cur_value += parseInt(data[t_key]);
                dash.f_data[dash.f_data.length] = cur_value;
                dash.f_labels[dash.f_labels.length] = t_key;
            }
        });
        
    }(jQuery));
};

BadiliDash.prototype.addVisualization = function(data, title, all_years){
    (function($) {
        $.each(all_years, function(i, cur_year){
            if(data.no_hhs[cur_year] != undefined){
                var target = (data.target['all'] != undefined) ? data.target : data.target['all'];
                var progress = parseFloat((data.no_hhs[cur_year]/target)*100).toFixed(1);
                var this_viz = sprintf('\
                    <h5>In <strong>%s %s%%</strong> beneficiaries reached</h5>\
                    <div class="progress progress-bar-default">\
                        <div style="width: %s%%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="%s" role="progressbar" class="progress-bar">\
                            <span class="sr-only">%s%% Complete (success)</span>\
                        </div>\
                    </div>', cur_year, progress, progress, progress, progress);
                
                $('#ben_reached').html(this_viz); 
            }
        })

        $.each(all_years, function(i, cur_year){
            if(data.no_hhs[cur_year] != undefined){
                var hh_viz = '<div class="ibox float-e-margins section_header">\
                    <div class="ibox-title">\
                        <h5>Households</h5>\
                    </div>\
                    <div class="ibox-content">\
                        \
                    </div>\
                </div>';
                $('#hhs_viz').html(hh_viz); 
            }
        });
        // $('.progress_year').html(sprintf('<strong>As of June %s </strong>' % year));
        $('#side_title').html(title);
    }(jQuery));
};

BadiliDash.prototype.numberWithCommas = function(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

BadiliDash.prototype.changeLevel = function(level){
    var metrics = bi_dash_data.metrics[level];
    dash.cur_country = level;
    var country_name = bi_dash_data.metrics[level].country_name;
    try{
        dash.formatLineData(metrics.year_end_total);
    }
    catch(err){
        alert(sprintf("I am missing progress data for '%s'. I can't create the beneficiaries type chart", country_name));
    }
    try{
        dash.ben_labels = ['Direct', 'Indirect'];
        dash.ben_data = [metrics.direct_ben.all, metrics.indirect_ben.all];
    }
    catch(err){
        alert(sprintf("I am missing direct/indirect data for '%s'. I can't create the beneficiaries pie chart", country_name));
        dash.ben_data = [0, 0];
    }

    dash.beneficiariesReached('2017', level);
};

BadiliDash.prototype.changeView = function(that){
    var cur_view = that.id+'_div';
    var all_views = ['varieties_released_div', 'dvm_distribution_div', 'progress_tracker_div'];
    (function($) {
        var is_hidden = ($('#'+cur_view).hasClass('hidden'));
        if(is_hidden == false){
            return;
        }
        all_views.splice(all_views.indexOf(cur_view), 1);

        $('#'+cur_view).removeClass('hidden').animate();
        $.each(all_views, function(){
            $('#'+this).addClass('hidden').animate();
        });
    }(jQuery));
};

BadiliDash.prototype.initiateVarietiesReleased = function(){
    // load the metrics
    (function($) {
        if($('#bubble_charts svg').length != 0){
            return;
        }
        var all_metrics = '<div class="dd"><ol class="dd-list">';
        $.each(bi_dash_data.varieties.metrics, function(i, that){
            all_metrics += '<li class="dd-item" data-id="'+ i +'">\
                <div class="dd-handle" id="'+ that.metric_code +'"><span class="label" style="background-color: '+ dash.varieties_colors[that.metric_code]['color'] +'">&nbsp;&nbsp;</span>&nbsp;'+ that.metric_name +'</div>\
            </li>';
        });
        all_metrics += '</ol></div>';
        $('#all_metrics').html(all_metrics);

        var varieties_count = {};
        $.each(bi_dash_data.varieties.varieties, function(i, that){
            $.each(bi_dash_data.varieties.metrics, function(i, metric){
                if(varieties_count[metric.metric_code] == undefined){
                    varieties_count[metric.metric_code] = 0;
                }
                if(that[metric.metric_code] == '1'){
                    varieties_count[metric.metric_code] += 1;
                }
            });
        });
        var bubble_chart_data = [];
        $.each(varieties_count, function(metric, size){
            bubble_chart_data[bubble_chart_data.length] = {'name': metric, 'size': size}
        });
        var t_data = {'name': 'flare', 'children': bubble_chart_data};
        dash.initiateBubbleCharts(t_data);
        // dash.initiateMapVisualization('varieties_map', dash.center_lat, dash.center_lon, 3, true, true);
        var cur_countries = new Array();
        $.each(bi_dash_data.geo_json, function(j, that){
            if(that.country_type != undefined){
                cur_countries.push([that.short_code, 0.5, '#f57f21']);
            }
        });

        // add the varieties release progress
        Highcharts.chart('variety_release', {
            chart: { type: 'column' },
            credits: {
                text: 'Sweetpotato for Profit and Health Initiative (SPHI)',
                href: 'http://sweetpotatoknowledge.org'
            },
            title: {
                text: 'No of varieties by flesh color as at Sept 2017'
            },
            plotOptions: {
                dataLabels: {
                    enabled: true
                },
                series: {
                    borderColor: '#303030'
                },
                column: {
                    dataLabels: {
                        enabled: true,
                        crop: false,
                        overflow: 'none'
                    },
                    pointPadding: 0.2,
                    borderWidth: 1
                },
            },
            xAxis: {
                // type: 'category',
                categories: ['2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017'],
                crosshair: true
            },
            yAxis: {
                title: { text: '# released' },
                labels: {
                    formatter: function() {
                        return this.value;
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:#000000;padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            series: [{
                name: 'White or Cream', pointWidth: 20, groupPadding: 0.15,
                data: [1, 4, 2, 3, 8, 2, 1, 1, 1]
            }, {
                name: 'Yellow', pointWidth: 20, groupPadding: 0.15,
                data: [1, 0, 1, 1, 2, 0, 6, 0, 1]
            }, {
                name: 'Orange', pointWidth: 20, groupPadding: 0.15,
                data: [5, 2, 27, 2, 22, 9, 5, 5, 0]
            }, {
                name: 'Purple', pointWidth: 20, groupPadding: 0.15,
                data: [0, 0, 0, 0, 0, 0, 0, 3, 0]
            }],
            colors: ['#fffaf0', '#faff00', '#ff8c00', '#c300ff']
        });
        dash.initiateVarietiesHighmaps({'data': cur_countries, 'map_title': 'Countries with improved Sweetpotato varieties', 'show_tooltip': false});
    }(jQuery));
};

BadiliDash.prototype.initiateVarietiesHighmaps = function(settings){
    Highcharts.mapChart('varieties_map', {
        chart: {
            map: 'custom/africa'
        },
        credits: {
            text: 'Sweetpotato for Profit and Health Initiative (SPHI)',
            href: 'http://sweetpotatoknowledge.org'
        },
        title: {
            text: settings.map_title
        },
        colors: dash.backgroundColor1,
        color: settings.cur_color,
        mapNavigation: {
            enabled: true,
            buttonOptions: {
                verticalAlign: 'bottom'
            },
            enableMouseWheelZoom: false
        },
         legend: {
            labelFormatter: function () {
                var to_return = '';
                if (this.from == undefined){
                    to_return = '< '+this.to;
                }
                else if (this.to == undefined){
                    to_return = '> '+this.from;
                }
                else{
                    to_return = this.from +' - '+ this.to;
                }
                return to_return;
            }
        },
        colorAxis: {
            dataClasses: [
                { from: 1, to: 4 },
                { from: 4, to: 7 }, 
                { from: 7, to: 11 }, 
                { from: 11, to: 15 }, 
                { from: 15, to: 19 }, 
                { from: 19 }
            ]
            // dataClassColor: 'category'
            // minColor: settings.cur_color,
            // maxColor: settings.cur_color
        },
        tooltip:{
            enabled: settings.show_tooltip,
            formatter: function () {
                return '<b>Major Trait: ' + this.series.name + '</b><br>' +
                    'Country: ' + this.point.name + '<br>' +
                    'Released Varieties: ' + this.point.value;
            }
        },
        series: [{
            data: settings.data,
            name: settings.title,
            point: {
                events:{
                    click: function(){
                        alert(this.name);
                    }
                }
            },
            states: {
                hover: {
                    color: '#f57f21'
                },
                point:{
                    events:{
                        click: function(){ console.log(this); }
                    }
                }
            },
            dataLabels: {
                enabled: false,
                format: '{point.name}'
            },
            // nullColor: 'white',
            // color: settings.cur_color,
            // colorAxis: true
        }]
    });
};

BadiliDash.prototype.showSelectedMetrics = function(cur_metric){
    // clear the layers
    // dash.clearLayers();
    var layers_added = {}, all_geo_json = [];
    // now add the necessary layers
    (function($) {
        $.each(bi_dash_data.varieties.varieties, function(i, that){
            if(that[cur_metric] == '1'){
                if(layers_added[that.iso_code] != undefined){
                    layers_added[that.iso_code] += 1;
                    return true;
                }
                var cur_geo;
                $.each(bi_dash_data.geo_json, function(j, geo){
                    if(geo.iso_code == that.iso_code){
                        cur_geo = geo;
                        return false;
                    }
                });
                all_geo_json[all_geo_json.length] = cur_geo;
                layers_added[that.iso_code] = 1;
            }
        });

        var cur_countries = new Array();
        $.each(all_geo_json, function(i, cur_geo){
            cur_countries.push([cur_geo.short_code, layers_added[cur_geo.iso_code], '#f57f21']);
        });
        dash.initiateVarietiesHighmaps({
            'data': cur_countries, 
            'title': dash.varieties_colors[cur_metric]['name'],
            'cur_color': dash.varieties_colors[cur_metric]['color'],
            'map_title': sprintf('Countries with released %s varieties', dash.varieties_colors[cur_metric]['name']),
            show_tooltip: true
        });
    }(jQuery));
};

BadiliDash.prototype.initiateBubbleCharts = function(root){
    var diameter = 450, format = d3.format(",d"), color = d3.scale.category20c();
    var bubble = d3.layout.pack().sort(null).size([diameter, diameter]).padding(1.5);
    var svg = d3.select("#bubble_charts").append("svg").attr("width", diameter).attr("height", diameter).attr("class", "bubble");
        
    var tooltip = d3.select("#bubble_charts")
        .append("div")
        .style("position", "absolute")
        .style("z-index", "10")
        .style("visibility", "hidden")
        .style("color", "white")
        .style("padding", "8px")
        .style("background-color", "rgba(0, 0, 0, 0.75)")
        .style("border-radius", "6px")
        .style("font", "12px sans-serif")
        .text("tooltip");

      var node = svg.selectAll(".node")
          .data(bubble.nodes(classes(root))
          .filter(function(d) { return !d.children; }))
        .enter().append("g")
          .attr("class", "node")
          .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

      node.append("circle")
          .attr("r", function(d) { return d.r; })
          .style("fill", function(d) { console.log(d); return dash.varieties_colors[d.className]['color']; }).style('cursor', 'pointer')
          .on("mouseover", function(d) {
                tooltip.text( dash.varieties_colors[d.className]['name'] + ": " + format(d.value));
                tooltip.style("visibility", "visible").style('width', '12em');
          })
          .on("mousemove", function() {
              return tooltip.style("top", (d3.event.layerY-10)+"px").style("left",(d3.event.layerX+10)+"px");
          })
          .on("mouseout", function(){return tooltip.style("visibility", "hidden");})
          .on("click", function(d){ 
            dash.showSelectedMetrics(d.className);
          });

      node.append("text")
          .attr("dy", ".3em")
          .style("text-anchor", "middle")
          .style("pointer-events", "none")
          .text(function(d) { return d.value; });


    // Returns a flattened hierarchy containing all leaf nodes under the root.
    function classes(root) {
      var classes = [];

      function recurse(name, node) {
        if (node.children) node.children.forEach(function(child) { recurse(node.name, child); });
        else classes.push({packageName: name, className: node.name, value: node.size});
      }

      recurse(null, root);
      return {children: classes};
    }

    d3.select(self.frameElement).style("height", diameter + "px");
};

BadiliDash.prototype.initiateDVMViz = function(){
    dash.initiateDVMDistribution('dvm_map', dash.center_lat, dash.center_lon, 4, true);
    dash.initiateDVMCharts();
};

BadiliDash.prototype.initiateDVMDistribution = function(div, center_lat, center_lon, zoom_level, show_bg=true, same_color=false){
    dash.initiateMap(div, center_lat, center_lon, zoom_level, show_bg);
    (function($) {
        var markers = L.markerClusterGroup({
            showCoverageOnHover: false
        });
        $.each(bi_dash_data.metrics.dvm_dist, function(i, that){
            markers.addLayer(L.marker(L.latLng(that.latitude, that.longitude)));
        });
        dash.map.addLayer(markers);
    }(jQuery));
};

BadiliDash.prototype.initiateDVMCharts = function(){
    // Splice in transparent for the center circle
    Highcharts.getOptions().colors.splice(0, 0, 'transparent');
    var colorscale = d3.scale.category20b();
    var cur_colors = [];
    for(var i = 0; i < 20; i++){
        cur_colors.push(colorscale(i));
    }
    Highcharts.chart('dvm_by_country', {
        chart: {
            height: '100%'
        },
        credits: {
            text: 'Sweetpotato for Profit and Health Initiative (SPHI)',
            href: 'http://sweetpotatoknowledge.org'
        },
        title: {
            text: 'DVMs by Country by Category'
        },
        plotOptions: {
            series: {
                color: cur_colors
            }
        },

        series: [{
            type: "sunburst",
            data: bi_dash_data.metrics.dvm_sunburst,
            allowDrillToNode: true,
            color: cur_colors,
            cursor: 'pointer',
            // levelIsConstant: false,
            dataLabels: {
                /**
                 * A custom formatter that returns the name only if the inner arc
                 * is longer than a certain pixel size, so the shape has place for
                 * the label.
                 */
                formatter: function () {
                    var shape = this.point.node.shapeArgs;

                    var innerArcFraction = (shape.end - shape.start) / (2 * Math.PI);
                    var perimeter = 2 * Math.PI * shape.innerR;

                    var innerArcPixels = innerArcFraction * perimeter;

                    if (innerArcPixels > 16) {
                        return this.point.name;
                    }
                }
            },
            levels: [{
                level: 2,
                colorByPoint: true,
                dataLabels: {
                    rotationMode: 'parallel'
                }
            },
            {
                level: 3,
                colorVariation: {
                    key: 'brightness',
                    to: -0.5
                },
                dataLabels: {
                    rotationMode: 'series'
                }
            }, {
                level: 4,
                colorVariation: {
                    key: 'brightness',
                    to: 0.5
                }
            }]
        }],
        // colors: colorscale,
        tooltip: {
            headerFormat: "",
            pointFormat: 'The # of DVMs in <b>{point.name}</b> is <b>{point.value}</b>'
        }
    });

    Highcharts.chart('dvm_by_age_gender', {
        chart: {
            type: 'bar'
        },
        credits: {
            text: 'Sweetpotato for Profit and Health Initiative (SPHI)',
            href: 'http://sweetpotatoknowledge.org'
        },
        title: {
            text: 'DVM Disaggregation by Gender by Age'
        },
        xAxis: [{
            categories: bi_dash_data.metrics.dvm_by_age_gender.cat,
            reversed: false,
            labels: {
                step: 1
            }
        }, { // mirror axis on right side
            opposite: true,
            reversed: false,
            categories: bi_dash_data.metrics.dvm_by_age_gender.cat,
            linkedTo: 0,
            labels: {
                step: 1
            }
        }],
        yAxis: {
            title: {
                text: null
            },
            labels: {
                formatter: function () {
                    return Math.abs(this.value);
                }
            }
        },

        plotOptions: {
            series: {
                stacking: 'normal'
            }
        },

        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + ', age ' + this.point.category + ' Years</b><br/>' +
                    'Population: ' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
            }
        },

        series: [{
            name: 'Male',
            data: bi_dash_data.metrics.dvm_by_age_gender.male
        }, {
            name: 'Female',
            data: bi_dash_data.metrics.dvm_by_age_gender.female
        }],
        colors: ["#f88c00", "#8e5ea2", "#3cba9f"]
    });
};

var dash = new BadiliDash();

jQuery(document).ready(function($) {
    // hide the main nav bar
    $('#main-nav').addClass('hidden').animate();
    $('.header-content, #theme-header').toggleClass('hidden').animate();
    // $('#dvm_distribution').click();

    var cur_countries = [], layers_added = {}, all_geo_json = [], series = {}, cur_country_type = '';
    $.each(bi_dash_data.geo_json, function(i, cur_geo){
        cur_country_type = (cur_geo.country_type == undefined) ? 'Others' : dash.proper_types[cur_geo.country_type];
        if(series[cur_country_type] == undefined){
            series[cur_country_type] = {
                name: cur_country_type,
                data: [],
                tooltip: false,
                visible: true,
                allAreas: false,
                dataLabels: {
                    enabled: false,
                    // format: '{point.name}'
                }
            };
            if(cur_country_type != 'others'){
                series[cur_country_type]['tooltip'] = true;
                series[cur_country_type]['point'] = {
                    events:{
                        click: function(){
                            dash.changeLevel(this['hc-key']);
                        }
                    }
                };
                series[cur_country_type]['states'] = {
                    hover: {
                        color: '#f57f21'
                    },
                    point:{
                        events:{
                            click: function(){ console.log(this); }
                        }
                    }
                };
            }
        }
        series[cur_country_type]['data'].push([cur_geo.short_code, undefined, dash.country_colors[cur_country_type]]);
    });
    var clean_series = [];
    $.each(series, function(i, that){
        clean_series.push(that);
    });
    dash.initiateProgressHighmaps({'series': clean_series, 'title': 'SPHI Participating Countries', 'map_title': 'SPHI Participating Countries'});
    dash.changeLevel('all');
});