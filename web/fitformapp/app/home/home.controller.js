(function() {
  'use strict';

  angular
    .module('fitformapp')
    .controller('HomeController', HomeController);

  HomeController.$inject = ['$scope', 'Principal', '$state'];

  function HomeController ($scope, Principal, $state) {
      var vm = this;
  console.log('HomeIsHere')
      vm.account = null;
      vm.isAuthenticated = null;
      vm.login = login;
      vm.register = register;
      $scope.$on('authenticationSuccess', function() {
          getUser();
      });

      getUser();

      function getUser() {
          Principal.identity().then(function(user) {
            vm.user = user;
            vm.isAuthenticated = Principal.isAuthenticated;
          });
      }

      function register () {
          $state.go('register');
      }

      function login () {
          $state.go('login');
      }
  }
})();
