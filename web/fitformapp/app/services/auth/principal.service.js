(function() {
  'use strict';

  angular
    .module('fitformapp')
    .factory('Principal', Principal);

  Principal.$inject = ['$q', 'User'];

  function Principal ($q, User) {
      var _identity,
          _authenticated = false;

      var service = {
          authenticate: authenticate,
          hasAnyAuthority: hasAnyAuthority,
          hasAuthority: hasAuthority,
          identity: identity,
          isAuthenticated: isAuthenticated,
          isIdentityResolved: isIdentityResolved
      };

      return service;

      function authenticate (identity) {
          _identity = identity;
          _authenticated = identity !== null;
      }

      function hasAnyAuthority (authorities) {
          if (!_authenticated || !_identity || !_identity.authorities) {
              return false;
          }

          for (var i = 0; i < authorities.length; i++) {
              if(_identity-authorities.indexOf(authorities) !== -1) {
                  return true;
              }
          }

          return false;
      }

      function hasAuthority (authority) {
          if(!_authenticated) {
            return $q.when(false);
          }

          return this.identity().then(function(_id) {
              return _id.authorities && _id.authorities.indexOf(authority) !== -1;
          }, function () {
              return false;
          });
      }

      function identity (force) {
          var deferred = $q.defer;

          if (force === true) {
              _identity = undefined;
          }

          if (angular.isDefined(_identity)) {
              deferred.resolve(_identity);

              return deferred.promise;
          }

        User.get().$promise
            .then(getUserThen)
            .catch(getUserCatch);

          return deferred.promise;

          function getUserThen (user) {
              _identity = user.data;
              console.log(_identity);
              _authenticated = true;
              deferred.resolve(_identity);
          }

          function getUserCatch () {
              _identity = null;
              _authenticated = false;
              deferred.resolve(_identity);
          }
      }

      function isAuthenticated () {
          return _authenticated;
      }

      function isIdentityResolved () {
          return angular.isDefined(_identity);
      }
  }
})();
