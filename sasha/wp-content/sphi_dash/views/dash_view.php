<?php
/**
 * The template for displaying the dashboard page
 *
 * This is the template that displays all pages by default.
 *
 * @package Badili SPHI Dashboard
 * @subpackage Sweetpotato_Knowledge
 * @since Sweetpotato Knowledge 1.0
 */
get_header();
// tie_breadcrumbs();
?>
<div class="dash-content top-navigation" id='wrapper'>
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg navbar_row">
            <nav class="navbar navbar-static-top" role="navigation">
                <div class="navbar-header">
                    <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                        <i class="fa fa-reorder"></i>
                    </button>
                    <a href="#" class="navbar-brand" id='progress_tracker'>Progress Tracker</a>
                    <a href="#" class="navbar-brand" id='varieties_released'>Variety Release</a>
                    <a href="#" class="navbar-brand" id='dvm_distribution'>SP Vines Availability</a>
                </div>
                <div class="navbar-collapse collapse" id="navbar">
                    <li class='pull-right toggle-header'>
                        <a href="#" class="btn btn-outline btn-warning">
                            <i class="fa fa-toggle-up"></i> Toggle Header
                        </a>
                    </li>
                </div>
            </nav>
        </div>
        <div class="wrapper wrapper-content" id='progress_tracker_div'>
            <div class="row">
                <div class="col-lg-8">
                    <div class="ibox-content">
                        <div class='row'>
                            <div class="alert alert-danger alert-dismissable hidden">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                Error Message
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5 m-b-xs">
                                <!-- div data-toggle="buttons" class="btn-group">
                                    <label class="btn btn-sm btn-white active"> <input type="radio" id="option2" name="options"> Countries </label>
                                    <label class="btn btn-sm btn-white"> <input type="radio" id="option1" name="options"> Progress </label>
                                </div -->
                                <a href="#" class="btn btn-outline btn-danger reset_graphs">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                            </div>
                            <div class="col-sm-4 m-b-xs text-center text-warning">
                                <!-- h3 id='map_title'>SPHI Participating Countries</h3 -->
                            </div>
                            <div class="col-sm-3">
                                <!-- div class="input-group">
                                    <input type="text" placeholder="Search" id='global_search' class="input-sm form-control"> 
                                        <span class="input-group-btn"> <button type="button" class="btn btn-sm btn-primary"> Go!</button> </span>
                                </div -->
                            </div>
                        </div>
                        <div id="leaflet_map"></div>
                    </div>
                </div>

                <div class="col-lg-4 main_left">
                    <div class="row">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title ben_reach_title">
                                <h5 id='ben_reached_title' class='post-box-title'>&nbsp</h5>
                                <small class='pull-right' id='ben_reached_title_small'></small>
                            </div>
                            <div class="ibox-content ben_reach">
                                <div class="" id='ben_reached'></div>
                            </div>
                        </div>
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5 id='ben_progress_title'>&nbsp;</h5>
                                <small class='pull-right' id='ben_progress_title_small'></small>
                            </div>
                            <div class="ibox-content">
                                <div class="" id='ben_reached'></div>
                                <canvas id="ben_reached_progress" width="400" height="387"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bottom_pane">
                <div class="col-lg-8">
                    <div class="ibox-content">
                        <div id='projects_bar'></div>
                    </div>
                </div>
                <div class="col-lg-4 bottom_left">
                    <div class="ibox-content">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title section_header">
                                <h5 id='ben_type_title'>&nbsp;</h5>
                                <small class='pull-right' id='ben_type_title_small'></small>
                            </div>
                            <div class="ibox-content">
                                <canvas id="beneficiaries_types" width="100" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='wrapper wrapper-content hidden' id='varieties_released_div'>
            <div class="row">
                <div class="col-lg-3">
                    <div class="ibox-title">
                        <h5 id='all_metrics_title'>Varieties Traits</h5>
                    </div>
                    <div class="ibox-content" id='all_metrics'></div>
                </div>

                <div class="col-lg-4">
                    <div class='row'>
                        <div class="ibox-title">
                            <h5>Number of varieties per major trait as at Sept. 2017</h5>
                        </div>
                        <div class="ibox-content" id='bubble_charts'></div>
                    </div>
                    <!-- div class='row'>
                        <div class="ibox-title">
                            <h5>OFSP vs NON OFSP</h5>
                        </div>
                        <div class="ibox-content" id='ofsp_non_ofsp'></div>
                    </div -->
                </div>

                <div class="col-lg-5 maps">
                    <div class="ibox-title">
                        <h5 id='varieties_map_title'>Number of varieties per country as at Sept. 2017</h5>
                    </div>
                    <div class="ibox-content">
                        <div id="varieties_map"></div>
                    </div>
                </div>
            </div>
            <div class='row'>
                <br />
                <div class="col-lg-12" id='variety_release'></div>
            </div>
        </div>
        <div class='wrapper wrapper-content hidden' id='dvm_distribution_div'>
            <div class="row">
                <div class="col-lg-8 maps">
                    <div class="ibox-title">
                        <h5 id='dvm_map_title'>Distribution of Vine Multipliers</h5>
                    </div>
                    <div class="ibox-content">
                        <div id="dvm_map"></div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="ibox-content">
                        <div id='dvm_by_country'></div>
                    </div>

                    <div class="ibox-content">
                        <div id='dvm_by_age_gender'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    // get_footer();
?>

