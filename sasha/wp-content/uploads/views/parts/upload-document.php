<div class="" ng-controller="pdfUploadCtrl" id="pdfUploadCtrl">

    <h1 class="type-header">Upload a file</h1>
    <form ng-submit="uploadFile()" id="PdfDetailsForm" class="">
        <fieldset>

                <div class="form-group" >
                    <label>Select a file:</label>
                    <input type="file" class="form-control" id="pdf-select"/><br/>
                    <uib-progressbar ng-show="upload_progress > 0 && upload_progress < 100" class="progress-striped active" value="upload_progress" type="warning">
                        <span ng-bind="upload_progress + '%'"></span>
                    </uib-progressbar>
                </div>

                <div class="form-group">
                    <label>Title:</label>
                    <input type="text" name="title" class="form-control" id='pdf_title' ng-model="title"><br>
                </div>

                <div class="form-group">
                    <label for="abstract">Abstract:</label><br/><small><i><?php _e('(A short description about the file you are uploading)','sweetpotatoknowledge');?></i></small>
                    <textarea  class="form-control" rows="5" id="abstract" ng-model="abstract"></textarea>
                </div>


                <div class="form-group padding-top">
                    <h3>Authorship <small><i><?php _e('(The lead authors of the resource you are uploading)','sweetpotatoknowledge');?></i></small></h3>
                </div>

                <div class="form-group">
                    <div class="row" ng-repeat="author_name in author_list">
                        <div class="col-md-1 col-sm-1" ng-bind="$index + 1"></div>
                        <div class="col-md-9 col-sm-8">
                            <div class="form-group">
                                <input type="text" placeholder="Type the author's name" name="pdf_authors"  class="form-control" usersautocomplete/><br>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <a class="btn btn-danger btn-xs " ng-show="{{$index && $index > 0}}" href="javascript:;" ng-click="removeAuthor(author_name)"><i class="fa fa-trash"></i> Remove</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="border-top margin-top padding-top">
                                <button type="button" id="add_pdf_author" class="btn btn-primary btn-xs btn-green"><i class="fa fa-plus"></i> Add Author</button>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label>Publication Date: </label><br/><small><i><?php _e('(The date when this resource was first published. This is not the date of uploading this resource on the portal)','sweetpotatoknowledge');?></i></small>
                        <input type="date"  name="creation_date" class="form-control half-control"  id='pdf_creation_date' ng-model="creation_date"/><br>
                    </div>
                    <div class="col-md-12">
                        <label>How to Cite:</label><br/><small><i><?php _e('(Your preferred citation for this resource)','sweetpotatoknowledge');?></i></small>
                        <textarea rows="3" name="file_biblio" class="form-control" id="pdf_file_biblio" ng-model="file_biblio"></textarea><br>
                    </div>

                    <div class="col-md-6">
                        <label>Identifier:</label>
                        <input type="text" name="file_identifier" class="form-control" id='pdf_file_identifier'  ng-model="file_identifier"><br>
                    </div>
                </div>
            </div>

                <div class="form-group padding-top">
                    <h3>Contributors <small><i><?php _e('(Co-authors of the document being uploaded, including the person uploading the resource if different from the author)','sweetpotatoknowledge');?></i></small></h3>
                </div>

                <div class="form-group ">
                    <label>Contributors:</label>

                    <div class="row" ng-repeat="contributor_name in contributor_list">
                        <div class="col-md-1 col-sm-1" ng-bind="$index + 1"></div>
                        <div class="col-md-9 col-sm-8">
                            <div class="form-group">
                                <input type="text" placeholder="Type the contributor's name" name="pdf_contributors"  class="form-control" usersautocomplete/><br>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <a class="btn btn-danger btn-xs" ng-show="{{$index && $index > 0}}" href="javascript:;" ng-click="removeContributor(contributor_name)"><i class="fa fa-trash"></i> Remove</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="border-top margin-top padding-top">
                                <button type="button" id="add_pdf_contributor" class="btn btn-primary btn-xs btn-green"><i class="fa fa-plus"></i> Add Contributor</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <h3>Key information</h3>
                </div>


                <div class="form-group">
                    <div class="row">

                        <div class="col-md-12">
                            <label>Subject:</label><br/><small><i><?php _e('(keyword, key phrase, or classification codes. Recommended best practice is to use a controlled vocabulary)','sweetpotatoknowledge');?></i></small>
                            <input type="text" name="subject" class="form-control"   id='pdf_subject' ng-model="subject"/><br>
                        </div>

                        <div class="col-md-12">
                            <label>Publisher:</label><br/><small><i><?php _e('(The entity responsible for making the resource available.)','sweetpotatoknowledge');?></i></small>
                            <input type="text" name="publisher" class="form-control"  id='pdf_publisher' ng-model="publisher"/><br>
                        </div>

                        <div class="col-md-6">
                            <label>Language:</label>
                            <select name="language" class="form-control" id='pdf_language' ng-model="language">
                                <option value="en">English</option>
                                <option value="fr">French</option>
                                <option value="sw">Swahili</option>
                                <option value="pt">Portuguese</option>
                                <option value="es">Spanish</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Rights:</label>
                            <input type="text" name="rights" class="form-control"  id='pdf_rights' ng-model="rights"/><br>
                        </div>


                        <div class="col-md-6">
                            <label>Key words:</label>
                            <input type="text" name="keywords" class="form-control"   id='pdf_keywords' ng-model="keywords"/><br>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <h3>File Information</h3>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label>File type: </label>
                            <input type="text" class="form-control" name="filetype" id="pdf_filetype" ng-model="filetype" ><br>
                        </div>

                        <div class="col-md-6">
                            <label>File Name:</label>
                            <input type="text" class="form-control" name="filename" id="pdf_filename" ng-model="filename" ><br>
                        </div>

                        <div class="col-md-6">
                            <label>No of pages:</label>
                            <input type="number" name="number_of_pages" class="form-control"   id='pdf_number_of_pages' ng-model="number_of_pages"/><br>
                        </div>

                        <div class="col-md-6">
                            <label>File Size:</label>
                            <input type="text" name="filesize"  class="form-control"   id='pdf_filesize' ng-model="filesize"/><br>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <h3>Topic Folders</h3>
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
                <div class="form-group">
                    <input type="submit" class="btn btn-primary"  value="Submit">
                </div>
        </fieldset>
    </form>
</div>