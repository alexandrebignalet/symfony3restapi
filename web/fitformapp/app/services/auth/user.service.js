(function () {
    'use strict';

    angular
      .module('fitformapp')
      .factory('User', User);

  User.$inject = ['$http', 'XHR_BASE_URL'];

    function User ($http, XHR_BASE_URL) {
        /*var service = $resource(XHR_BASE_URL+'/users/me', {}, {
          'get': { method: 'GET', params: {}, isArray: false,
              interceptor: {
                response: function (response) {
                    //expose response
                    return response;
                }
              }
          }
        });*/

      var service = {
          get: get,
          getCurrent: getCurrent,
          create: create,
          update: update,
          delete: deleteUser
      };

      return service;

      function get (idUser) {
        return $http({
          method: 'GET',
          url: XHR_BASE_URL+'/users/'+idUser
        });
      }

      function getCurrent () {
        return $http({
          method: 'GET',
          url: XHR_BASE_URL+'/users/me'
        });
      }

      function create (data) {
        return $http({
          method: 'POST',
          url: XHR_BASE_URL+'/users/',
          data: data
        });
      }

      function update (idUser, data) {
        return $http({
          method: 'PATCH',
          url: XHR_BASE_URL+'/users/'+idUser,
          data: data
        });
      }

      function deleteUser (idUser) {
        return $http({
          method: 'DELETE',
          url: XHR_BASE_URL+'/users/'+idUser
        });
      }
    }
})();
