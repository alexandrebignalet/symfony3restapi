(function() {
  'use strict';

  angular
    .module('fitformapp', [
      'ngStorage',
      'tmh.dynamicLocale',
      'ngResource',
      'ngCookies',
      'ngAria',
      'ngCacheBuster',
      'ui.bootstrap',
      'ui.bootstrap.datetimepicker',
      'ui.router',
      'infinite-scroll',
      'angular-loading-bar'
    ])
    .run(run);

  run.$inject = ['stateHandler'];

  function run(stateHandler) {
    stateHandler.initialize();
  }
})();
