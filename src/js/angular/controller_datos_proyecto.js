/*app_analisis.controller('datos_proyecto', function() {
 $scope.datos = [];
 $scope.datos.nombre = '';
 $scope.datos.pais = '';
 $scope.datos.comunidad = '';
 $scope.datos.ciudad = '';
 $scope.datos.poblacion = '';
 $scope.datos.tipologia = '';
 $scope.datos.fecha = '';
 });*/

app_analisis.controller('datos_proyecto', function($scope) {
    $scope.datos = {};
    $scope.datos.nombre = '';
    $scope.datos.pais = '';
    $scope.datos.comunidad = '';
    $scope.datos.ciudad = '';
    $scope.datos.poblacion = '';
    $scope.datos.tipologia = '';
    $scope.datos.fecha = '';
    $scope.datos.file = '';

    $scope.submit = function(e) {
        console.log($scope.datos);

    };
   
});
