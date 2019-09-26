app.controller('createEventCtrl', function ($scope,$rootScope) {

    //enable jquery validate for this form now that its data is loaded
    $scope.enablePostFormValidate();
    console.log('Initiating the controllers');

    $scope.createEvent = function () {
        $scope.post_content = $('#create_event_ifr').contents().find("body").html();
        console.log('saving an event');
        console.log($scope);
        return;

        if ($("#EventDetailsForm").valid()) {
            var data = {
                action: "create_my_event",
                post_title: $scope.post_title,
                post_content: $scope.post_content,
                topics: JSON.stringify($rootScope.final_topics),
                topics_tax: JSON.stringify($rootScope.topics_tax),
            };

            $scope.send_ajax_request(data, function (response) {
                if (response.status == "success") {
                    $scope.post_title = "";
                    $scope.post_content = "";
                    $('#create_event_ifr').contents().find("body").html("");
                    $scope.safeApply();

                    window.location.href = response.url;
                }
            }, function (error) {
                console.log("Error", error);
            });
        }

    };
});