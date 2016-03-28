var routerApp = angular.module('routerApp',  ['ui.router']);
/*
var routerApp = angular.module("routerApp", ['ui.router']).run(function ($rootScope, $state, AuthService) {
  $rootScope.$on("$stateChangeStart", function(event, toState, toParams, fromState, fromParams){
    if (toState.authenticate && !AuthService.isAuthenticated()){
      // User isn’t authenticated
      $state.transitionTo("login");
      event.preventDefault();
    }
  });
});
*/
routerApp.config(function($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise('/landing');

    $stateProvider

        .state('landing', {
            url: '/landing',
            views: {
                '': { templateUrl: './views/partials/partial-landing.html' },
                'columnOne@landing': {
                  templateUrl: './views/partials/partial-login.html',
                  controller: 'loginController'
                },
                'columnTwo@landing': {
                    templateUrl: './views/partials/partial-createaccount.html',
                    controller: 'createaccountController'
                }
            }
        })
        .state('disclaimer', {
            url: '/disclaimer',
            templateUrl: './views/partials/partial-disclaimer.html'
        })
});
