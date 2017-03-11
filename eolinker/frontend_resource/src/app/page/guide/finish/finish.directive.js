(function() {
    /* 引导页完成安装页面指令 */
    angular.module('eolinker').directive('finish', ['$timeout', "$window", function($timeout, $window, EVENT) {
        return {
            restrict: 'AE',
            templateUrl: 'app/page/guide/finish/finish.html',
            // replace:true,
            controller: finishCtroller,
            controllerAs: 'finishCtrl'
        }
    }])

    finishCtroller.$inject = ['$scope', '$timeout', 'Api', '$state', 'CODE', 'md5', '$cookies', '$uibModal', '$window', '$rootScope'];

    function finishCtroller($scope, $timeout, Api, $state, CODE, md5, $cookies, $uibModal, $window, $rootScope) {
        var code = CODE.SUCCESS;

        function init() {// 初始化页面
            if (window.localStorage['INSTALLINFO']) {// 移除缓存中的安装信息
                window.localStorage.removeItem('INSTALLINFO');
            }
        }
        init();
        
    }

})();
