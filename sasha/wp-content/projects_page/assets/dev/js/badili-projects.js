//app is defined in Utils
app.controller('projectsCtrl', function ($scope, $timeout, $interval) {
    $scope.project_status = 'All';//display all projects by default
    $scope.current_project = null;
    $scope.filteredProjects = [];
    $scope.current_index = null;

    $scope.$watch('project_status', function () {
        //for some reason the images reload only after scroll
        $timeout(function () {
            window.scrollBy(0, 1);
            window.scrollBy(0, -1);
        }, 1);

        $('#selection-buttons button[data-project-group]').removeClass('btn-success');
        $('#selection-buttons button[data-project-group="' + $scope.project_status + '"]').addClass('btn-success');
    });

    $scope.statusFilter = function (item) {
        if ($scope.project_status === 'All') {
            return true;
        } else {
            return item.status === $scope.project_status;
        }
    };

    /**
     * 
     * @param {type} project   The currect project
     * @param {type} index     The index of the project in the filtered list
     * @returns {undefined}
     */
    $scope.showProjectDetails = function (project, index) {
        $scope.current_project = project;
        $scope.current_index = index;
    };

    $scope.projects = PROJECTS.projects;
    $(document).on('click', '#selection-buttons button', function () {

        var selection = $(this).attr('data-project-group');
        $scope.project_status = selection;

        $scope.$apply();

    });
});