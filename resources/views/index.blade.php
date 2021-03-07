<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div ng-cloak ng-app="App" ng-controller="HomeController as vm" class="container">
    <button ng-click="vm.refresh()" class=" pul-right btn btn-primary">Refresh</button>

    <table class="table">
        <thead>
        <tr>
            <th>
                #ID
            </th>
            <th>
                Name
            </th>
            <th>
                Description
            </th>
            <th>
                Link
            </th>
            <th>
                More
            </th>
        </tr>
        </thead>
        <tbody>

        <tr ng-repeat=" restaurant in vm.allRestaurants">
            <td class="id">
                @{{restaurant.id}}
            </td>
            <td class="name">
                @{{restaurant.name}}
            </td>
            <td class="description">
                @{{restaurant.description}}

            </td>
            <td class="url">
                <a href=" @{{restaurant.url}}">click</a>
            </td>
            <td>
                <a target="_blank" href="@{{'/restaurants/'+restaurant.id}}" class="btn btn-info">Info</a>
            </td>
        </tr>

        </tbody>
    </table>

</div>

</body>
<script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.8/angular.js"></script>
<script>
    var __socket_host ="{{env("SOCKET_HOST")}}";
</script>
<script>
    /**
     * Created by george on 18-May-17.
     */
    var appModule = angular.module('App', [], function ($interpolateProvider) {

    }).value('loadingService', {
        loadingCount: 0,
        isLoading: function () {
            return this.loadingCount > 0;
        },
        requested: function () {
            this.loadingCount += 1;
        },
        responded: function () {
            this.loadingCount -= 1;
        }, failed: function () {
            this.loadingCount -= 1;
        }
    }).factory('loadingInterceptor', function (loadingService) {
        return {
            request: function (config) {
                loadingService.requested();
                return config;
            },
            response: function (response) {
                loadingService.responded();
                return response;
            },
        };
    });


    (function () {
        //Config
        appModule.config([configuration]);

        function configuration() {

        }

        //Run
        appModule.run([run]);

        function run() {

        }

        //Controller
        appModule.controller("HomeController", ['$scope', '$http', 'loadingService', HomeController]);
        appModule.config(['$httpProvider', function ($httpProvider) {
            $httpProvider.interceptors.push('loadingInterceptor');
        }]);


        function HomeController($scope, $http, loadingService) {
            var self = this;

            self.loadingService = loadingService;
            self.mapping = {};
            self.allRestaurants = {};
            self.refresh = function () {
                $http.get('/restaurants').then(function (response) {
                    self.allRestaurants = response.data;
                });
            };
            //self.init();
            var socket = io(__socket_host);


            socket.on('restaurant-updated', function (message) {
                var restaurant =JSON.parse(message);
               $scope.$apply(function(){
                   self.allRestaurants[restaurant.id] =restaurant;
                   console.log(self.allRestaurants);
               }) ;
            });
        }
    })();


</script>
</html>
