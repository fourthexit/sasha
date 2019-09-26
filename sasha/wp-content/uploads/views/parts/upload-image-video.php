<div class="" ng-controller="imageVideoUploadCtrl" id="imageVideoUploadCtrl">
    <h1 class="type-header">Upload Video</h1>
    <form ng-submit="uploadFile()" id="ImageDetailsForm">
        <fieldset>

            <div class="form-group" >
                <label>Select a video file:</label>
                <input type="file" class="form-control" id="image-select"/><br/>
                <uib-progressbar ng-show="upload_progress > 0 && upload_progress < 100" class="progress-striped active" value="upload_progress" type="warning">
                    <span ng-bind="upload_progress + '%'"></span>
                </uib-progressbar>
            </div>

            <div class="form-group ">
                <label>Title:</label>
                <input type="text" name="title" class="form-control" id='image_title' ng-model="title"><br>
            </div>

            <div class="form-group ">
                <label>Caption:</label>
                <textarea placeholder="Describe what is happening in the uploaded media. e.g Christine at the Breeding workshop in Ghana explaining on how to culture sweetpotatoes"  class="form-control" name="abstract" rows="5"  ng-model="abstract"></textarea>
            </div>
            <div class="form-group">
                <label>Credits:</label>
                <textarea rows="3" name="credits"  class="form-control" ng-model="credits"></textarea><br>
            </div>

            <div class="form-group">
                <h3>File Information</h3>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>File type: </label>
                    <input type="text" class="form-control" name="filetype" id="image_filetype" ng-model="filetype" ><br>
                </div>

                <div class="form-group col-md-6">
                    <label>File Name:</label>
                    <input type="text" class="form-control" name="filename" id="image_filename" ng-model="filename" ><br>
                </div>
            </div>    

            <div class="row">
              
                <div class="form-group col-md-6">
                    <label>File Size:</label>
                    <input type="text" name="filesize"  class="form-control"   id='image_filesize' ng-model="filesize"/><br>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <h3>Topic Folders</h3>
                </div>
            </div>
            <div class="row">
                <!--<div class="form-group col-md-12" ng-controller="topicsController">
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
                </div>-->

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
            <div class="form-group">
                <input type="submit" class="btn btn-primary"  value="Submit">
            </div>
        </fieldset>
    </form>
</div>