//app is defined in Utils
app.controller('topicsController', ['$scope', function ($scope) {
    $scope.topics = [];
    

    /*$scope.$watchCollection('final_topics', function (newNames, oldNames) {
        console.log($scope.final_topics);
    });*/

    $(document).on('change', '#badili_topics input[name="topics[]"]', function () {
        var topic_folders = JSON.parse($(this).attr('data-topic-folders'));
        var is_checked = $(this).is(':checked');
        var title = $(this).attr('data-topic-title');
        var id = $(this).attr('data-topic-id');
        if (is_checked) {
            $scope.topics.push({
                title: title,
                id: id,
                folders: topic_folders ? topic_folders[id] : []
            });
        } else {
            $scope.topics = $scope.topics.filter(function (foundItem) {
                return foundItem.id !== id;
            });
        }
        $scope.$apply();
    });
    
    $(document).on('change', '#topics_list input[name="final_selected_topics[]"]', function () {
        var is_checked = $(this).is(':checked');
        var id = $(this).attr('id');
        
        if (is_checked) {
            $scope.final_topics.push(id);
        } else {
            $scope.final_topics = $scope.final_topics.filter(function (foundItem) {
                return foundItem !== id;
            });
        }
        $scope.$apply();
    });
}]);