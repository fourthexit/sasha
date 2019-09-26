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
                    {expand: true, flatten: true, src: ['node_modules/angular/angular.min.js'], dest: 'dist/lib/'},
                    {expand: true, flatten: true, src: ['node_modules/angular-sanitize/angular-sanitize.min.js'], dest: 'dist/lib/'}
                ]
            }
        }, ngAnnotate: {
            options: {
                singleQuotes: true
            },
            app: {
                files: {
                    'dev/min-safe/js/badili-projects-utils.js': ['dev/js/badili-projects-utils.js'],
                    'dev/min-safe/js/badili-projects.js': ['dev/js/badili-projects.js']
                }
            }
        }, concat: {
            options: {
                separator: ';'
            },
            dist: {
                src: [
                    'dev/min-safe/js/badili-projects-utils.js',
                    'dev/min-safe/js/badili-projects.js'
                ],
                dest: 'dev/min-safe/js/badili-projects-combined.js'
            }
        }, uglify: {
            my_target: {
                files: {
                    'dist/js/badili-projects-combined.min.js': ['dev/min-safe/js/badili-projects-combined.js']
                }
            }
        }, watch: {
            js: {
                files: ['dev/js/**/*.js'],
                tasks: ['ngAnnotate', 'concat', 'uglify']
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-ng-annotate');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask("default", ['clean', 'copy', 'ngAnnotate', 'concat', 'uglify', 'watch']);
};