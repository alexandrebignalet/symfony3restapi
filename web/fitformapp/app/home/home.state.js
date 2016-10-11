(function() {
  'use strict';

  angular
    .module('fitformapp')
    .config(stateConfig);

  stateConfig.$inject = ['$stateProvider'];

  function stateConfig($stateProvider) {
    console.log('homestate')
    $stateProvider.state('home', {
      parent: 'app',
      url: '/',
      data: {
        authorities: []
      },
      views: {
        'content@': {
          templateUrl: 'app/home/home.html',
          controller: 'HomeController',
          controllerAs: 'vm'
        }
      }
    });
  }
})();
