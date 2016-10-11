(function() {
    'use strict';

    angular
      .module('fitformapp')
      .controller('NavbarController', NavbarController);

    NavbarController.$inject = ['$state', 'Auth', 'Principal', 'LoginService'];

    function NavbarController ($state, Auth, Principal, LoginService) {
        var vm = this;
console.log('NavbarIsHere')
        vm.isNavbarCollapsed = true;
        vm.isAuthenticated = Principal.isAuthenticated;
        vm.login = login;
        vm.logout = logout;
        vm.toggleNavbar = toggleNavbar;
        vm.collapseNavbar = collapseNavbar;
        vm.$state = $state;

        function login () {
            collapseNavbar();
            $state.go('login');
        }

        function logout () {
            collapseNavbar();
            Auth.logout();
            $state.go('login');
        }

        function toggleNavbar () {
            vm.isNavbarCollapsed = !vm.isNavbarCollapsed;
        }

        function collapseNavbar() {
            vm.isNavbarCollapsed = true;
        }
    }
})();
