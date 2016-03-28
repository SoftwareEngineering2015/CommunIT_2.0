var routerApp = angular.module('routerApp',  ['ui.router']);

routerApp.config(function($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise('/myhome');

    $stateProvider

        .state('myhome', {
            url: '/myhome',
            views: {
                '': { templateUrl: './views/partials/partial-myhome.html'
               },
                'ownedcommunities@myhome': {
                  templateUrl: './views/partials/partial-ownedcommunities.html'
                },
                'joinedcommunities@myhome': {
                    templateUrl: './views/partials/partial-joinedcommunities.html'
                }
            }
        });



});
