(function() {
  'use strict';

  angular
    .module('fitformapp')
    .config(stateConfig);

  stateConfig.$inject = ['$stateProvider'];

  function stateConfig($stateProvider) {
    $stateProvider.state('login', {
      parent: 'app',
      url: '/login',
      data: {
        authorities: []
      },
      views: {
        'content@': {
          templateUrl: 'app/components/login.html',
          controller: 'LoginController',
          controllerAs: 'vm'
        }
      }
    });
  }
})();
