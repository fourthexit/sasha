module.exports = function (grunt) {

// Project configuration.
    grunt.initConfig({
        clean: {//remove the dist folder with every build
            build: {
                src: ['dist']
            }
        },
        copy: {//copy over the needed single files from bower/npm 
            main: {
                files: [
                    {expand: true, flatten: true, src: ['node_modules/moment/min/moment.min.js'], dest: 'dist/lib/'},
                    {expand: true, flatten: true, src: ['node_modules/angular/angular.min.js'], dest: 'dist/lib/'},
                    {expand: true, flatten: true, src: ['node_modules/angular-ui-bootstrap/dist/ui-bootstrap-tpls.js'], dest: 'dist/lib/'},
                    {expand: true, flatten: true, src: ['node_modules/jquery-validation/dist/jquery.validate.min.js'], dest: 'dist/lib/'},
                    {expand: true, flatten: true, src: ['node_modules/angular-xeditable/dist/js/xeditable.min.js'], dest: 'dist/lib/'},
                    {expand: true, flatten: true, src: ['node_modules/angular-xeditable/dist/css/xeditable.min.css'], dest: 'dist/lib/'},
                    {expand: true, cwd: 'node_modules', src: ['bootstrap/dist/**'], dest: 'dist/lib/'},
                    {expand: true, cwd: 'node_modules', src: ['bootstrap/dist/**'], dest: 'dist/lib/'},
                ]
            }
        }, sass: {// Task
            css: {// Target
                options: {// Target options
                    "sourcemap=none": '', // i do not wtc the source map
                    style: 'compressed'
                },
                files: {// Dictionary of files
                    'dist/css/badili-upload.min.css': 'dev/scss/badili-upload.scss',
                    'dist/css/badili-home.min.css': 'dev/scss/badili-home.scss'
                }
            }
        }, ngAnnotate: {
            options: {
                singleQuotes: true
            },
            app: {
                files: {
                    'dev/min-safe/js/badili-pdf-upload.js': ['dev/js/badili-pdf-upload.js'],
                    'dev/min-safe/js/badili-image-upload.js': ['dev/js/badili-image-upload.js'],
                    'dev/min-safe/js/badili-create-post.js': ['dev/js/badili-create-post.js'],
                    'dev/min-safe/js/choose-action.js': ['dev/js/choose-action.js'],
                    'dev/min-safe/js/utils.js': ['dev/js/utils.js'],
                    'dev/min-safe/js/badili_topics_widget.js': ['dev/js/badili_topics_widget.js']
                }
            }
        }, uglify: {
            my_target: {
                files: {
                    'dist/js/badili-pdf-upload.min.js': ['dev/min-safe/js/badili-pdf-upload.js'],
                    'dist/js/badili-image-upload.min.js': ['dev/min-safe/js/badili-image-upload.js'],
                    'dist/js/badili-create-post.min.js': ['dev/min-safe/js/badili-create-post.js'],
                    'dist/js/choose-action.min.js': ['dev/min-safe/js/choose-action.js'],
                    'dist/js/utils.min.js': ['dev/min-safe/js/utils.js'],
                    'dist/js/badili_topics_widget.min.js': ['dev/min-safe/js/badili_topics_widget.js']
                }
            }
        }, watch: {
            js: {
                files: ['dev/js/**/*.js'],
                tasks: ['ngAnnotate', 'uglify']
            },
            css: {
                files: ['dev/scss/**/*.scss'],
                tasks: ['sass']
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-ng-annotate');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask("default", ['clean', 'copy', 'sass', 'ngAnnotate', 'uglify', 'watch']);
};