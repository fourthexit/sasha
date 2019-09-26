//app is defined in Utils
app.controller('chooseActionCtrl', function ($scope) {
    $scope.choose_action = "create_post";

    $scope.$watch('choose_action', function () {
        console.log($scope.choose_action);
        switch ($scope.choose_action) {
            case 'upload_pdf':
            {
                $('#pdfUploadCtrl').removeClass('hidden');
                $('#imageVideoUploadCtrl').addClass('hidden');
                $('#createPostCtrl').addClass('hidden');
                $('#createEventCtrl').addClass('hidden');
                $('#create-project').addClass('hidden');
                break;
            }
            case 'upload_image':
            {
                $('#pdfUploadCtrl').addClass('hidden');
                $('#imageVideoUploadCtrl').removeClass('hidden');
                $('#create-project').addClass('hidden');
                $('#createEventCtrl').addClass('hidden');
                $('#createPostCtrl').addClass('hidden');
                break;
            }
            case 'create_post':
            {
                $('#pdfUploadCtrl').addClass('hidden');
                $('#imageVideoUploadCtrl').addClass('hidden');
                $('#create-project').addClass('hidden');
                $('#createEventCtrl').addClass('hidden');
                $('#createPostCtrl').removeClass('hidden');

                break;
            }
             case 'create_project':
            {
                $('#pdfUploadCtrl').addClass('hidden');
                $('#imageVideoUploadCtrl').addClass('hidden');
                $('#createPostCtrl').addClass('hidden');
                $('#createEventCtrl').addClass('hidden');
                $('#create-project').removeClass('hidden');
                break;
            }
             case 'create_event':
            {
                $('#pdfUploadCtrl').addClass('hidden');
                $('#imageVideoUploadCtrl').addClass('hidden');
                $('#createPostCtrl').addClass('hidden');
                $('#createEventCtrl').removeClass('hidden');
                break;
            }
        }
    });
});