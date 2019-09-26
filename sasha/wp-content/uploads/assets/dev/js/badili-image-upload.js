app.controller('imageVideoUploadCtrl', function ($scope,$rootScope) {
    
    
    /**
     * Do the actual Pdf file upload
     * 
     * 
     * @returns {undefined}
     */
    $scope.uploadFile = function () {
        if ($("#ImageDetailsForm").valid()) {
            var data = {
                action: "upload_document",
                title: $scope.title,
                abstract: $scope.abstract,
                credits: $scope.credits,
                language: $scope.language,
                filesize: $scope.filesizebytes, //send it in bytes
                filename: $scope.filename,
                filetype: $scope.filetype,
                filedata: $scope.filedata,
                topics: JSON.stringify($rootScope.final_topics),
                topics_tax: JSON.stringify($rootScope.topics_tax),
            };

            $scope.send_ajax_request(data, function (response) {
                if (response.status == "success") {

                    //open the post!
                    window.location.href = response.url;
                }
            }, function (error) {
                console.log("Error", error);
            });
        }
    };

    /**
     * Check for file selection for assyncronous upload
     */
    $(document).on("change", "input[type=file]", function () {
        var buttonId = $(this).attr("id");
        var file = $(this)[0].files[0];

        switch (buttonId) {
            case 'image-select':
            {
                $scope.uploadTheFile(file, function (status, filedata, message) {
                    if (status == "success") {
                        var data = {
                            action: "get_document_meta",
                            filename: file.name,
                            filedata: filedata,
                            filetype: file.type
                        }
                        $scope.send_ajax_request(data, function (response) {
                            if (response.status == "success") {


                                $('#number_of_pages').closest('.form-group').addClass('hidden');

                                if (/image\/*/.test(file.type)) {
                                    //other dates are availalable but maybe useless?? 
                                    //"2014:03:11 12:55:08".toDate("yyyy-mm-dd hh:ii:ss");
                                    $scope.creation_date = response.meta.DateTimeOriginal ? $scope.get_value(response.meta.DateTimeOriginal).toDate("yyyy-mm-dd hh:ii:ss") : "";
                                    $('[name="image_authors"]').val($scope.get_value(response.meta.Author));
                                    //$scope.author = $scope.get_value(response.meta.Author);
                                    $scope.title = $scope.get_value(response.meta.Title);
                                }


                                $scope.filedata = filedata;
                                $scope.filename = file.name;
                                $scope.filetype = file.type;

                                //todo: ssave the value in bytes
                                $scope.filesize = $scope.bytesToMbs(file.size);
                                $scope.filesizebytes = file.size;
                                $scope.safeApply();
                            }
                        }, function (error) {
                            console.log("Error", error);
                        });
                    } else if (status == "failure") {
                        console.log("Failure", "Upload failed: " + message);
                    }
                });
            }
        }

    });
});