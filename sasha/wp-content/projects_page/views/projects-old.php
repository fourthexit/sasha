<?php
/**
 * The template for displaying projects page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Sweetpotato_Knowledge
 * @since Sweetpotato Knowledge 1.0
 */
get_header();
tie_breadcrumbs();
?>
<style>
    .left-change,.right-change {
        width: 40px;
        height: 39px;
        font-size: 25px;
        display: inline-block;
        text-align: center;
        border: 1px solid #eee;
        border-bottom: none;
        border-top: none;
    }
    .center-change{
        width: 100px;
        height: 39px;
        font-size: 25px;
        display: inline-block;
        text-align: center;
        border: 1px solid #eee;
        border-bottom: none;
        border-top: none;
    }
    #projectDetails{
        top:80px;
    }
</style>
<div class="content">
    

    <div class="post-listing tabs-content">

        <div class="post-inner tabs-view-custom">

            <div id='all_projects' ng-controller="projectsCtrl">
                <div class="row  text-center selection-buttons no-margin" id='selection-buttons'>
                    <div class="col-md-4"><button type="button" data-project-group='All' class="btn btn-block filter-btn">All</button></div>
                    <div class="col-md-4"><button type="button" data-project-group='Ongoing' class="btn btn-block filter-btn">Ongoing</button></div>
                    <div class="col-md-4"><button type="button" data-project-group='Complete' class="btn btn-block filter-btn">Complete</button></div>
                </div>

                <div class='row no-margin'>
                    <div ng-repeat="project in (filteredProjects = (projects| filter: statusFilter))">
                        <div ng-show="$index % 4 == 0" class="no-margin"></div>

                        <div class="col-md-3 col-sm-4 col-xs-6 text-center no-padding project-box">
                            <div class="project-wrapper">


                                    <div class="project-img-wrapper">

                                        <a data-toggle="modal" data-target="#projectDetails" ng-click="showProjectDetails(project, $index)">
                                            <div class="project-img-container">
                                                <img width="150" height="150" ng-src="{{project.thumbnail}}" />
                                            </div>
                                            <span class="fa overlay-icon"></span>
                                        </a>
                                    </div>



                                <!--{{project.post_title | limitTo: 60}}{{project.post_title.length > 60 ? '...' : ''}}-->
                                <div class="project-post-title">
                                        {{project.post_title}}
                                </div>

                                <ul class="project-post-meta">
                                    <li>
                                        <i class="fa fa-tags"></i> Status: <span> {{project.status}}</span>
                                    </li>
                                    <li>
                                    <!--{{project.project_region | limitTo:18}}{{project.project_region.length > 18 ? '...' : ''}}-->
                                       <i class="fa fa-globe"></i> Region: <span>{{project.project_region}} </span>
                                    </li>
                                </ul>

                                <div class="project-buttons">
                                    <div class="pull-left">
                                        <a  data-toggle="modal" data-target="#projectDetails" ng-click="showProjectDetails(project, $index)" class="project-link" href='javascript:;'><?php _eti('Preview ') ?> <i class="fa fa-file-text"></i></a>
                                    </div>
                                    <div class="pull-right">
                                        <a class="project-link" ng-href="{{project.permalink}}"><?php _eti('Read More') ?> <i class="fa fa-angle-double-right"></i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row no-margin">
                    <!-- Modal -->
                    <div id="projectDetails" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg sasha-modal">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <div style="display: inline-block; float: left;">
                                        <div class="left-change nav-btn"  ng-show="current_index > 0"><a href="javascript:;" ng-click="current_index = current_index - 1; current_project = filteredProjects[current_index];"> <i class="fa fa-angle-left" aria-hidden="true"></i></a></div>
                                        <div class="center-change" style="font-size: 12px;"> <span ng-bind="current_index + 1"></span> of <span ng-bind="filteredProjects.length"></span></div>
                                        <div class="right-change nav-btn" ng-show="current_index < filteredProjects.length - 1"><a href="javascript:;" ng-click="current_index = current_index + 1; current_project = filteredProjects[current_index];"  ><i class="fa fa-angle-right" aria-hidden="true"></i></a></div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img ng-src="{{current_project.thumbnail_full}}" />
                                        </div>
                                        <div class="col-md-8">
                                            <h4 class="modal-title project-title" ng-bind="current_project.post_title"></h4>


                                                <table class="table table-condensed ">

                                                    <tbody>
                                                      <tr ng-repeat="(key,meta_item) in current_project.meta">
                                                        <td ng-bind-html="key" width="30%" class="key-text"></td>
                                                        <td ng-bind-html="meta_item" class="meta-text"></td>
                                                      </tr>

                                                    </tbody>
                                                </table>

                                            <a class="more-link" ng-href="{{current_project.permalink}}"><?php _eti('Read More &raquo;') ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

