<div class="" ng-controller="chooseActionCtrl">
    <div class="form-group file-upload-choices">
        <label for="choise" class="choices-label">Upload Type <i class="fa fa-upload fa-2x"></i></label><br/>

        <ul class="file-upload-options">
            <li class="selection-block">
                <input type="radio" ng-model="choose_action" value="create_post" id="create_post_opt">
                <label for="create_post_opt"> News </label>
                <div class="check"><div class="inside"></div></div>
            </li>
            <li class="selection-block">
                <input type="radio" ng-model="choose_action" value="upload_pdf" id="upload_pdf_opt">
                <label for="upload_pdf_opt">Document</label>
                <div class="check"><div class="inside"></div></div>
            </li>
            <li class="selection-block">
                <input type="radio" ng-model="choose_action" value="upload_image" id="upload_image_opt">
                <label for="upload_image_opt">Video</label>
                <div class="check"><div class="inside"></div></div>
            </li>
            <li class="selection-block">
                <input type="radio" ng-model="choose_action" value="create_project" id="create_project_opt">
                <label for="create_project_opt">Project</label>
                <div class="check"><div class="inside"></div></div>
            </li>
            <li class="selection-block">
                <input type="radio" ng-model="choose_action" value="create_event" id="create_event_opt">
                <label for="create_event_opt">Event</label>
                <div class="check"><div class="inside"></div></div>
            </li>
            <li class="selection-block">
                <input type="radio" ng-model="choose_action" value="create_link" id="create_link_opt">
                <label for="create_link_opt">Link</label>
                <div class="check"><div class="inside"></div></div>
            </li>
        </ul>
    </div>
</div>
