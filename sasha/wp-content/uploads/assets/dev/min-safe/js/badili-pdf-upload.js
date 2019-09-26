//app is defined in Utils
app.controller('pdfUploadCtrl', ['$scope', '$rootScope', function ($scope,$rootScope) {
    //the authors
    $scope.author_list = [];
    $scope.author_list.push($scope.guid());

    //the contributors
    $scope.contributor_list = [];
    $scope.contributor_list.push($scope.guid());

    $scope.clearForm = function () {
        //clear the form
        $scope.abstract = "";
        //$('#pdf-select').val("");
        $scope.action = "";
        $scope.author = "";
        $scope.contributors = "";
        $scope.creation_date = new Date();
        $scope.filedata = "";
        $scope.filename = "";
        $scope.filesize = "";//for display purposes
        $scope.filesizebytes = "";
        $scope.filetype = "";
        $scope.keywords = "";
        $scope.language = 'en';
        $scope.publisher = "";
        $scope.rights = "";
        $scope.subject = "";
        $scope.title = "";
        $scope.number_of_pages = "";

        $scope.safeApply();
    };

    /**
     * Do the actual Pdf file upload
     * 
     * 
     * @returns {undefined}
     */
    $scope.uploadFile = function () {
        if ($("#PdfDetailsForm").valid()) {

            //get all the authors
            $scope.authors = [];
            jQuery('[name="pdf_authors"').each(function () {
                var author = $(this).val();
                if (author !== "") {
                    $scope.authors.push(author);
                }
            });

            //get all the contributors
            $scope.contributors = [];
            jQuery('[name="pdf_contributors"').each(function () {
                var contributor = $(this).val();
                if (contributor !== "") {
                    $scope.contributors.push(contributor);
                }
            });

            var data = {
                action: "upload_document",
                title: $scope.title,
                abstract: $scope.abstract,
                authors: JSON.stringify($scope.authors),
                file_biblio: $scope.file_biblio,
                file_identifier: $scope.file_identifier,
                creation_date: moment(new Date($scope.creation_date)).format('YYYY-MM-DD'), //publication_date
                subject: $scope.subject,
                topics: JSON.stringify($rootScope.final_topics),
                topics_tax: JSON.stringify($rootScope.topics_tax),
                publisher: $scope.publisher,
                language: $scope.language,
                rights: $scope.rights,
                contributors: JSON.stringify($scope.contributors),
                keywords: $scope.keywords,
                filesize: $scope.filesizebytes, //send it in bytes
                filename: $scope.filename,
                filetype: $scope.filetype,
                filedata: $scope.filedata,
                number_of_pages: $scope.number_of_pages
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
            case 'pdf-select':
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

                                //clear the form
                                $scope.clearForm();

                                $('#number_of_pages').closest('.form-group').addClass('hidden');

                                if (/image\/*/.test(file.type)) {
                                    //other dates are availalable but maybe useless?? 
                                    //"2014:03:11 12:55:08".toDate("yyyy-mm-dd hh:ii:ss");
                                    $scope.creation_date = response.meta.DateTimeOriginal ? $scope.get_value(response.meta.DateTimeOriginal).toDate("yyyy-mm-dd hh:ii:ss") : "";
                                    $('[name="pdf_authors"]').val($scope.get_value(response.meta.Author));
                                    //$scope.author = $scope.get_value(response.meta.Author);
                                    $scope.title = $scope.get_value(response.meta.Title);
                                    $scope.subject = $scope.get_value(response.meta.Subject);

                                } else if (/application\/pdf/.test(file.type)) {
                                    $scope.creation_date = response.meta.CreationDate ? new Date($scope.get_value(response.meta.CreationDate)) : "";
                                    $scope.number_of_pages = $scope.get_value(response.meta.Pages);
                                    $scope.keywords = $scope.get_value(response.meta["AAPL:Keywords"]);

                                    //$scope.author = $scope.get_value(response.meta.Author);
                                    $('[name="pdf_authors"]').val($scope.get_value(response.meta.Author));
                                    $scope.title = $scope.get_value(response.meta.Title);
                                    $scope.subject = $scope.get_value(response.meta.Subject);
                                    $('#number_of_pages').closest('.form-group').removeClass('hidden');
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

    $(document).on("click", "#add_pdf_author", function () {
        $scope.author_list.push($scope.guid());
        $scope.safeApply();
    });

    $(document).on("click", "#add_pdf_contributor", function () {
        $scope.contributor_list.push($scope.guid());
        $scope.safeApply();
    });

    /**
     * Remove an author from the list
     * 
     * @param {type} author_name
     * @returns {undefined}
     */
    $scope.removeAuthor = function (author_name) {
        $scope.author_list = $scope.author_list.filter(function (foundAuthor) {
            return foundAuthor !== author_name;
        });
    };

    /**
     * Remove a contributor from the list
     * 
     * @param {type} contributor_name
     * @returns {undefined}
     */
    $scope.removeContributor = function (contributor_name) {
        $scope.contributor_list = $scope.contributor_list.filter(function (foundContributor) {
            return foundContributor !== contributor_name;
        });
    };

}]);