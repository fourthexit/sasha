//app is defined in Utils
app.controller('chooseActionCtrl', ['$scope', function ($scope) {   
    $scope.choose_action = "create_post";

    $scope.$watch('choose_action', function () {
        switch ($scope.choose_action) {
            case 'upload_pdf':
            {
                $('#pdfUploadCtrl').removeClass('hidden');
                $('#imageVideoUploadCtrl').addClass('hidden');
                $('#createPostCtrl').addClass('hidden');
                $('#create-project').addClass('hidden');
                break;
            }
            case 'upload_image':
            {
                $('#pdfUploadCtrl').addClass('hidden');
                $('#imageVideoUploadCtrl').removeClass('hidden');
                $('#create-project').addClass('hidden');
                $('#createPostCtrl').addClass('hidden');
                break;
            }
            case 'create_post':
            {
                $('#pdfUploadCtrl').addClass('hidden');
                $('#imageVideoUploadCtrl').addClass('hidden');
                $('#create-project').addClass('hidden');
                $('#createPostCtrl').removeClass('hidden');
                
                break;
            }
             case 'create_project':
            {
                $('#pdfUploadCtrl').addClass('hidden');
                $('#imageVideoUploadCtrl').addClass('hidden');
                $('#createPostCtrl').addClass('hidden');
                $('#create-project').removeClass('hidden');
                break;
            }
        }
    });
}]);