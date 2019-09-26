<div class="row hidden" ng-controller="createPostCtrl" id="createPostCtrl">
    <div class="col-md-12">
        <h1 class="type-header">Create a post</h1>

        <form ng-submit="createPost()" id="PostDetailsForm">
            <fieldset>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Title: </label>
                        <input type="text" class="form-control" name="post_title" ng-model="post_title"><br>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Content: </label>
                        <?php
                        wp_editor("", "create_post", array(
                            'editor_class' => "form-control bordered-textarea"
                        ));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <input type="submit" class="btn btn-primary"  value="Submit"/>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <h3>Topic Folders</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <div ng-repeat="topic in topics" class='row' id='topics_list'>
                            <div class="col-md-12">
                                <h4 ng-bind="topic.title"></h4>
                                <div ng-repeat="folder in topic.folders">
                                    <label>
                                        <input type="checkbox" name ='final_selected_topics[]' value="{{folder.id}}" id='{{folder.id}}'/>
                                        {{folder.title}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>