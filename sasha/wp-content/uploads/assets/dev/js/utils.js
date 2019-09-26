/**
 * Source: http://stackoverflow.com/a/38593735/2606887
 * //"22/03/2016 14:03:01".toDate("dd/mm/yyyy hh:ii:ss");
 * //"2016-03-29 18:30:00".toDate("yyyy-mm-dd hh:ii:ss");
 * // 2014:03:11 12:55:08
 *
 * 
 * @param {type} format
 * @returns {Date}
 */
String.prototype.toDate = function (format) {
    var normalized = this.replace(/[^a-zA-Z0-9]/g, '-');
    var normalizedFormat = format.toLowerCase().replace(/[^a-zA-Z0-9]/g, '-');
    var formatItems = normalizedFormat.split('-');
    var dateItems = normalized.split('-');

    var monthIndex = formatItems.indexOf("mm");
    var dayIndex = formatItems.indexOf("dd");
    var yearIndex = formatItems.indexOf("yyyy");
    var hourIndex = formatItems.indexOf("hh");
    var minutesIndex = formatItems.indexOf("ii");
    var secondsIndex = formatItems.indexOf("ss");

    var today = new Date();

    var year = yearIndex > -1 ? dateItems[yearIndex] : today.getFullYear();
    var month = monthIndex > -1 ? dateItems[monthIndex] - 1 : today.getMonth() - 1;
    var day = dayIndex > -1 ? dateItems[dayIndex] : today.getDate();

    var hour = hourIndex > -1 ? dateItems[hourIndex] : today.getHours();
    var minute = minutesIndex > -1 ? dateItems[minutesIndex] : today.getMinutes();
    var second = secondsIndex > -1 ? dateItems[secondsIndex] : today.getSeconds();

    return new Date(year, month, day, hour, minute, second);
};


var app = angular.module('myApp', ['ui.bootstrap']);
var $ = jQuery;//Just for simplicity

app.directive('usersautocomplete', function () {
    return function postLink(scope, iElement, iAttrs) {
        $(function () {
            $(iElement).autocomplete({
                maxResults: 10,
                source: function (request, response) {
                    var results = $.ui.autocomplete.filter(USER_DATA.users, request.term);
                    response(results.slice(0, this.options.maxResults));
                }
            });
        });
    };
});

app.controller('parentCtrl', function ($scope, $rootScope) {
    $scope.safeApply = function (fn) {
        var phase = this.$root.$$phase;
        if (phase == '$apply' || phase == '$digest') {
            if (fn)
                fn();
        } else {
            this.$apply(fn);
        }
    };

    /**
     * The progress bar
     */
    $scope.upload_progress = 0;

    /**
     * Max file upload size: dependend on the server 
     * This should the match the server restrictions
     * 
     * @type Number
     */
    MAXFILE_SIZE = USER_DATA.max_upload_size;
    /**
     * validation for the PDF upload form
     * 
     * @returns {undefined}
     */
    $scope.enablePDFFormValidate = function () {
        $("#PdfDetailsForm").validate({
            // Do not ignore hidden fields
            ignore: [],
            rules: {
                title: {
                    required: true,
                    minlength: 2
                }, 'pdf-select': {
                    required: true
                }, creation_date: {
                    required: false
                }, subject: {
                    required: false
                }, publisher: {
                    required: false
                }, file_biblio: {
                    required: false
                }, file_identifier: {
                    required: false
                }, rights: {
                    required: false
                }, keywords: {
                    required: false
                }, filetype: {
                    required: true
                }, filename: {
                    required: true
                }, number_of_pages: {
                    required: true
                }, filesize: {
                    required: true
                }
            }
        });
    };

    /**
     * validation for the PDF upload form
     * 
     * @returns {undefined}
     */
    $scope.enableImageFormValidate = function () {
        $("#ImageDetailsForm").validate({
            // Do not ignore hidden fields
            ignore: [],
            rules: {
                title: {
                    required: true,
                    minlength: 2
                }, 'image-select': {
                    required: true
                }, abstract: {
                    required: false
                }, credits: {
                    required: false
                }, filetype: {
                    required: true
                }, filename: {
                    required: true
                }, filesize: {
                    required: true
                }
            }
        });
    };
    /**
     * validation for the POST creation form
     * 
     * @returns {undefined}
     */
    $scope.enablePostFormValidate = function () {
        $("#PostDetailsForm").validate({
            // Do not ignore hidden fields
            ignore: [],
            rules: {
                post_title: {
                    required: true,
                    minlength: 2
                }
            }
        });
    };
    /**
     * validation for the LINK creation form
     * 
     * @returns {undefined}
     */
    $scope.enableLinkFormValidate = function () {
        $("#LinkDetailsForm").validate({
            // Do not ignore hidden fields
            ignore: [],
            rules: {
                post_title: {
                    required: true,
                    minlength: 2
                },
                link_url: {
                    required: true
                }
                
            }
        });
    };
    /**
     * Generic method for sending ajax requests
     * 
     * 
     * @param {type} data
     * @param {type} success_callback
     * @param {type} error_callback
     * @returns {undefined}
     */
    $scope.send_ajax_request = function (data, success_callback, error_callback) {
        $.ajax({
            url: USER_DATA.site_url + "/wp-admin/admin-ajax.php",
            data: data,
            dataType: 'json',
            method: "POST",
            beforeSend: function (xhr) {
                $scope.showProgressModal();
            },
            success: function (data) {
                $scope.hideProgressModal();
                success_callback && success_callback(data);
            },
            error: function (error) {
                $scope.hideProgressModal();

                alert("An error occured");
                error_callback && error_callback(error);
            }
        });
    };
    /**
     * Show bootstrap progress bar
     * @returns {undefined}
     */
    $scope.showProgressModal = function () {
        $scope.upload_progress = 90;
        $scope.safeApply();
    };
    /**
     * Hide bootstrap progress bar
     * @returns {undefined}
     */
    $scope.hideProgressModal = function () {
        $scope.upload_progress = 0;
        $scope.safeApply();
    };
    /**
     * Convert bytes to Mbs/Kbs
     * 
     * 
     * @param {type} sizeInBytes
     * @returns {Number}
     */
    $scope.bytesToMbs = function (sizeInBytes) {

        if (0 < sizeInBytes && sizeInBytes < 1024) {
            return sizeInBytes + " b";
        } else if (1024 < sizeInBytes && sizeInBytes < (1024 * 1024)) {
            var sizeInKB = (sizeInBytes / (1024)).toFixed(2);
            return sizeInKB + " kb";
        } else {
            var sizeInMB = (sizeInBytes / (1024 * 1024)).toFixed(2);
            return sizeInMB + " MB";
        }

    };
    /**
     * Sometimes the array of meta data has sub arrays instead of just values
     * 
     * @param {type} item
     * @returns {unresolved}
     */
    $scope.get_value = function (item) {
        if (Array.isArray(item)) {
            return item.join();
        } else {
            return item;
        }
    };
    /**
     * The code will upload the file to javascript using HTML5 File API
     * 
     * @param {type} file   The javscript file object
     * @param {type} accept The rejex for the type of files to accept
     * @param {type} callback
     * @returns {undefined}
     */
    $scope.uploadTheFile = function (file, callback) {
        if (file.size < MAXFILE_SIZE) {
            var reader = new FileReader();

            reader.onloadstart = function () {
                $scope.showProgressModal();
            };
            reader.onprogress = function (data) {
                //show progress
                if (data.lengthComputable) {
                    $scope.upload_progress = parseInt(((data.loaded / data.total) * 100), 10);
                    $scope.safeApply();
                }
            };
            reader.onloadend = function (event) {

            };
            reader.onload = function (event) {
                $scope.hideProgressModal();
                callback && callback("success", event.target.result, file.name);
            };
            //FIXME onupload error??
            reader.readAsDataURL(file);
        } else {
            alert("Upload failed: File too large");
            callback && callback("failure", false, "file too large");
        }
    };

    $scope.guid = function () {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                    .toString(16)
                    .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
                s4() + '-' + s4() + s4() + s4();
    };
    //enable jquery validate for this form now that its data is loaded 
    $scope.enablePDFFormValidate();

    //enable jquery validate for this form now that its data is loaded 
    $scope.enableImageFormValidate();

    //default language for the pdfs
    $scope.language = 'en';


    $rootScope.final_topics = [];
    $rootScope.topics_tax = [];
    $rootScope.topics = [];

    //these events should be done here or else, they are called twice per change
    $(document).on('change', '#topics_list input[name="final_selected_topics[]"]', function () {
        var is_checked = $(this).is(':checked');
        var id = $(this).attr('id');

        if (is_checked) {
            $rootScope.final_topics.push(id);
        } else {
            $rootScope.final_topics = $rootScope.final_topics.filter(function (foundItem) {
                return foundItem !== id;
            });
        }
        $scope.safeApply();
    });


    /*$scope.$watchCollection('topics_tax', function (newNames, oldNames) {
     console.log($scope.topics_tax);
     });*/

    $(document).on('change', '#badili_topics input[name="topics[]"]', function (event) {
        var topic_folders = JSON.parse($(this).attr('data-topic-folders'));
        var is_checked = $(this).is(':checked');
        var title = $(this).attr('data-topic-title');
        var id = $(this).attr('data-topic-id');
        if (is_checked) {
            $rootScope.topics.push({
                title: title,
                id: id,
                folders: topic_folders ? topic_folders[id] : []
            });
            $rootScope.topics_tax.push(id);
        } else {
            $rootScope.topics = $rootScope.topics.filter(function (foundItem) {
                return foundItem.id !== id;
            });
            $rootScope.topics_tax = $rootScope.topics_tax.filter(function (foundItem) {
                return foundItem !== id;
            });
        }
        $scope.safeApply();
    });
});