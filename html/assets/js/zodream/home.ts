/// <reference path="../../../../typings/requirejs/require.d.ts" />
/// <reference path="../../../../typings/jquery/jquery.d.ts" />
require.config({
    baseUrl: "/assets/js/",
    paths: {
        jquery: "jquery/jquery-2.2.3.min",
        bootstrap: "bootstrap/bootstrap.min",
        knockout: "knockout/knockout-3.4.0",
        admin: "zodream/admin",
        home: "zodream/home",
    }
});

require(['jquery', 'bootstrap']);