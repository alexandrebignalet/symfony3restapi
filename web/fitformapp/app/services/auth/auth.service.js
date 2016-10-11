(function () {
    'use strict';

    angular
      .module('fitformapp')
      .factory('Auth', Auth);

    Auth.$inject = ['$rootScope', '$state', '$sessionStorage', '$q', 'Principal', 'AuthServerProvider', 'User'];

    function Auth () {
        var service = {
          authorize: authorize,
          createUser: createUser,
          login: login,
          logout: logout,
          getPreviousState: getPreviousState,
          loginWithToken: loginWithToken,
          resetPreviousState: resetPreviousState,
          storePreviousState: storePreviousState,
          updateUser: updateUser
        }

        return service;

        function authorize (force) {
            var authReturn = Principal.identity(force).then(authThen);

            return authReturn;

            function authThen () {
                var isAuthenticated = Principal.isAuthenticated();

                if (isAuthenticated && $rootScope.toState.parent === 'user' && ($rootScope.toState.name === 'login' || $rootScope.toState.name === 'register')) {
                    $state.go('home');
                }

                if (isAuthenticated && !$rootScope.fromState.name && getPreviousState()) {
                    var previousState = getPreviousState();
                    resetPreviousState();
                    $state.go(previousState.name, previousState.params);
                }

                if ($rootScope.toState.data.authorities && $rootScope.toState.data.authorities.length > 0 && !Principal.hasAnyAuthority($rootScope.toState.data.authorities)) {
                    if (isAuthenticated) {
                        $state.go('home');
                    }
                    else {
                        storePreviousState($rootScope.toState.name, $rootScope.toStateParams);
                        $state.go('login');
                    }
                }
            }
        }

        function createUser (user, callback) {
            var cb = callback || angular.noop;

            return User.create(user, function () {
                return cb(user);
            },
            function (err) {
                this.logout();
                return cb(err);
            }).bind(this).$promise;
        }

        function login (credentials, callback) {
            var cb = callback || angular.noop;
            var deferred = $q.defer();

            AuthServerProvider.login(credentials)
              .then(loginThen)
              .catch(function (err) {
                  this.logout();
                  deferred.reject(err);
                  return cb(err);
              });

            function loginThen (data) {
                Principal.identity(true).then(function(user) {
                    deferred.resolve(data);
                });

                return cb();
            }
        }

        function loginWithToken (jwt, rememberMe) {
              return AuthServerProvider.loginWithToken(jwt, rememberMe);
        }

        function logout () {
            AuthServerProvider.logout();
            Principal.authenticate(null);
        }

        function updateUser (user, callback) {
            var cb = callback || angular.noop;

            return User.update(user,
                function () {
                    return cb(user);
                },
                function (err) {
                    return cb(err);
                }).bind(this).$promise;
        }

        function getPreviousState () {
            var previousState = $sessionStorage.previousState;
            return previousState;
        }

        function resetPreviousState () {
            delete $sessionStorage.previousState;
        }

        function storePreviousState (previousStateName, previousStateParams) {
            var previousState = { "name": previousStateName, "params": previousStateParams };
            $sessionStorage.previousState = previousState;
        }
    }
})();
