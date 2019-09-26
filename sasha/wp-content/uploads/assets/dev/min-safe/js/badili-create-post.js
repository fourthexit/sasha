//app is defined in Utils
app.controller('createPostCtrl', ['$scope', '$rootScope', function ($scope,$rootScope) {

    //enable jquery validate for this form now that its data is loaded 
    $scope.enablePostFormValidate();
    $scope.createPost = function () {

        $scope.post_content = $('#create_post_ifr').contents().find("body").html();

        if ($("#PostDetailsForm").valid()) {
            var data = {
                action: "create_my_post",
                post_title: $scope.post_title,
                post_content: $scope.post_content,
                topics: JSON.stringify($rootScope.final_topics),
                topics_tax: JSON.stringify($rootScope.topics_tax),
            };
            $scope.send_ajax_request(data, function (response) {
                if (response.status == "success") {
                    $scope.post_title = "";
                    $scope.post_content = "";
                    $('#create_post_ifr').contents().find("body").html("");

                    $scope.safeApply();

                    window.location.href = response.url;
                }
            }, function (error) {
                console.log("Error", error);
            });
        }

    };

}]);