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
    
    /*begining of sidebar css*/
    #nav {
    border:3px solid #3e4547;

    box-shadow:2px 2px 8px #000000;
    border-radius:3px;
    -moz-border-radius:3px;
    -webkit-border-radius:3px;
    }
    #nav, #nav ul {
        list-style:none;
        padding:0;
        width:200px;
    }
    #nav ul {
        position:relative;
        z-index:-1;
    }
    #nav li {
        position:relative;
        z-index:100;
    }
    #nav ul li {
        margin-top:-23px;

        -moz-transition:  0.4s linear 0.4s;
        -ms-transition: 0.4s linear 0.4s;
        -o-transition: 0.4s linear 0.4s;
        -webkit-transition: 0.4s linear 0.4s;
        transition: 0.4s linear 0.4s;
    }
    #nav li a {
        background-color:#d4d5d8;
        color:#000;
        display:block;
        font-size:12px;
        font-weight:bold;
        line-height:28px;
        outline:0;
        padding-left:15px;
        text-decoration:none;
    }
    #nav li a.sub {
        background:#d4d5d8 url("../images/down.gif") no-repeat;
    }
    #nav li a + img {
        cursor:pointer;
        display:none;
        height:28px;
        left:0;
        position:absolute;
        top:0;
        width:200px;
    }
    #nav li a img {
        border-width:0px;
        height:24px;
        line-height:28px;
        margin-right:8px;
        vertical-align:middle;
        width:24px;
    }
    #nav li a:hover {
        background-color:#bcbdc1;
    }
    #nav ul li a {
        background-color:#eee;
        border-bottom:1px solid #ccc;
        color:#000;
        font-size:11px;
        line-height:22px;
    }
    #nav ul li a:hover {
        background-color:#ddd;
        color:#444;
    }
    #nav ul li a img {
        background: url("../images/bulb.png") no-repeat;
        border-width:0px;
        height:16px;
        line-height:22px;
        margin-right:5px;
        vertical-align:middle;
        width:16px;
    }
    #nav ul li:nth-child(odd) a img {
        background:url("../images/bulb2.png") no-repeat;
    }
    #nav a.sub:focus {
        background:#bcbdc1;
        outline:0;
    }
    #nav a:focus ~ ul li {
        margin-top:0;

        -moz-transition:  0.4s linear;
        -ms-transition: 0.4s linear;
        -o-transition: 0.4s linears;
        -webkit-transition: 0.4s linears;
        transition: 0.4s linear;
    }
    #nav a:focus + img, #nav a:active + img {
        display:block;
    }
    #nav a.sub:active {
        background:#bcbdc1;
        outline:0;
    }
    #nav a:active ~ ul li {
        margin-top:0;
    }
    #nav ul:hover li {
        margin-top:0;
    }
</style>

<div class="content">
    <div class="post-listing tabs-content">
        <div class="post-inner tabs-view-custom">
            <div id='all_projects' ng-controller="projectsCtrl" class="ng-scope">
                <div class="row  text-center selection-buttons no-margin" id='selection-buttons'>
                    <div class="col-md-4"><button type="button" data-project-group='All' class="btn btn-block filter-btn">All</button></div>
                    <div class="col-md-4"><button type="button" data-project-group='Ongoing' class="btn btn-block filter-btn">Ongoing</button></div>
                    <div class="col-md-4"><button type="button" data-project-group='Complete' class="btn btn-block filter-btn">Complete</button></div>
                </div>
                
                
                
                <div class='row no-margin'>
                    <div ng-show="$index % 4 == 0" class="no-margin"></div>
                        <!-- <div class="col-md-2 col-sm-2 col-xs-2 text-center no-padding project-box" id="selection-buttons" style="max-width:150px; float:left;">
                            <ul id="nav">
                                <li ><div id="selection-buttons" class="text-center selection-buttons no-margin">
                                    <a href="#" class="sub" tabindex="1"><img src="images/t2.png" />Project Status</a>
                                    <a href="#" data-project-group='All'><img src="images/all.png" />All</a>
                                    <a href="#" data-project-group='Ongoing' class="filter-btn"><img src="images/ongoing.png" />Ongoing</a>
                                    <a href="#" data-project-group='Complete'><img src="images/complete.png" />Complete</a>
                                    </div>
                                    
                                        <li class="filter-btn"><a href="#" data-project-group='All'><img src="images/all.png" />All</a></li>
                                        <li ><a href="#" data-project-group='Ongoing' class="btn btn-block filter-btn"><img src="images/ongoing.png" />Ongoing</a></li>
                                        <li><a href="#" data-project-group='Complete'><img src="images/complete.png" />Complete</a></li>
                                   
                                 </li> 



                                <li><a href="#" class="sub" tabindex="1"><img src="images/location.png" />Regions</a>
                                    <ul>
                                        <li><a href="#">Link 6</a></li>
                                        <li><a href="#">Link 7</a></li>
                                        <li><a href="#">Link 8</a></li>
                                        <li><a href="#">Link 9</a></li>
                                        <li><a href="#">Link 10</a></li>
                                    </ul>
                                </li>


                                <li><a href="#" class="sub" tabindex="1"><img src="images/intervention.png" />Areas of Intervention</a>
                                    <ul>
                                        <li><a href="#">Link 6</a></li>
                                        <li><a href="#">Link 7</a></li>
                                        <li><a href="#">Link 8</a></li>
                                        <li><a href="#">Link 9</a></li>
                                        <li><a href="#"> Link 10</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div> -->
                    
                        <div ng-repeat="project in (filteredProjects = (projects| filter: statusFilter))">
                            <div ng-show="$index % 4 == 0" class="no-margin"></div>

                                <div class="col-md-10 col-sm-10 col-xs-910text-center no-padding project-box" style="max-width:1100px; float:right; ">
                                       <!--  <div class="project-img-wrapper">
                                            <a data-toggle="modal" data-target="#projectDetails" ng-click="showProjectDetails(project, $index)">
                                                <div class="project-img-container">
                                                    <img width="150" height="150" ng-src="{{project.thumbnail}}" />
                                                </div>
                                                <span class="fa overlay-icon"></span>
                                            </a>
                                        </div> -->
                                    
                                        <div class="project-post-title">
                                            <h3><a data-toggle="modal" data-target="#projectDetails" ng-click="showProjectDetails(project, $index)">
                                                {{project.post_title}}
                                            </a></h3>

                                        </div> 
                                        <ul class="project-post-meta">
                                            <li>
                                                <i class="fa fa-building-o"></i> Collaborating Organizations: <span> {{project.collaborating_orgs}}</span>
                                            </li> 
                                            <li>
                                                <i class="fa fa-tags"></i> Status: <span> {{project.status}}</span>
                                            </li>
                                            <li>
                                               <i class="fa fa-globe"></i> Countries: <span>{{project.countries}} </span>
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

                                  <!--  </div> This is the Project-wraper div.
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
                                        <div class="left-change nav-btn"  ng-show="current_index > 0"><a href="javascript:;" ng-click="current_index = current_index - 1; current_project = filteredProjects[current_index];"> <i class="fa fa-angle-left" aria-hidden="true"></i> </a></div>
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
                </div>                
            </div>
        </div>
    </div>
</div>

<aside class="sidebar green-title-box ">
    <div class="theiaStickySidebars ">
        <?php dynamic_sidebar('Add New');?>
    </div><!-- .theiaStickySidebar /-->
</aside>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

