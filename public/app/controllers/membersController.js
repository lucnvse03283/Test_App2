
app.directive('fileModel', ['$parse', function($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter =  model.assign;
            element.bind('change', function(){
            scope.$apply(function(){
                modelSetter(scope, element[0].files[0]);
            });
        });

        }
    }
    }
]);


app.controller('membersController', function($scope, $http, API_URL, toastr) {
    $scope.member=[];
    //retrieve list members
    $http({
        method: 'GET',
        url: API_URL + 'member'
        }).then(function successCallback(response) {
            $scope.members = response.data;
            console.log(response);
        }, function errorCallback(response) {
    });

    $scope.sortType     = 'id'; // set the default sort type
    $scope.sortReverse  = false;  // set the default sort order
    
    $scope.fileNameChaged = function(element)
    {
        var reader = new FileReader();
        reader.onload = function(e)
        {
            $scope.$apply(function()
            {
                $scope.imageSource = e.target.result;
            });
        }
        reader.readAsDataURL(element.files[0]);
    }

    $scope.uploadImageUpdate = function (files) {           
        var ext = files[0].name.match(/.*\.(.+)$/)[1];
        if(angular.lowercase(ext) =='jpg' || angular.lowercase(ext) =='jpeg' || angular.lowercase(ext) =='png'){
            if(files[0].size<10240000){

                $(".validate-photo-update").html("");
                if ($scope.frmMembers.$valid) {
                    $("#btn-save").removeAttr("disabled");
                }
                if ($scope.formMember.$valid) {
                    $("#btn-create").removeAttr("disabled");
                }
            } else{
                $(".validate-photo-update").html("Image max size 10MB!");
                $("#btn-save").attr("disabled","disabled");
                $("#btn-create").attr("disabled","disabled");
            } 
        } else{
            $(".validate-photo-update").html("Image Invalid!");
            $("#btn-save").attr("disabled","disabled");
         } 
    }
 
   //show form create and edit
    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.member.name="";
                $scope.member.address="";
                $scope.member.age="";
                $scope.member.image="";
                $('#modalCreate').modal('show');
                $("#modalCreate input textarea").val("");
                $('#image').val('');
                $(".validate-photo-update").html("");
                $scope.formMember.$setPristine();
                $scope.form_title = "Add New Member";
                break;
            case 'edit':
                $('#modalEdit').modal('show');
                $('#newImage').val('');
                $(".validate-photo-update").html("");
                $scope.form_title = "Member Infomation";
                $scope.id = id;
                

                $http({
                    method: 'GET',
                    url: API_URL + 'member/' +id
                }).then(function successCallback(response) {
                    //console.log(response.data);
                    $scope.editMember = response.data;
                }, function errorCallback(response) {
                    console.log(response);
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });

                // 
                break;
            default:
                break;
        }
        
    }


    // refresh page
    $scope.refresh = function(){
        $http({
            method : "GET",
            url : API_URL + 'member'
        }).then(function mySuccess(response) {
            $scope.members = response.data;
        }, function myError(response) {
        });
    }

    //save data in form
    $scope.save = function (modalstate, member) {
        var url_save = API_URL + "member/store";
        var file = $scope.member.image;
        var fd = new FormData();
        fd.append('image', file);
        fd.append("name", $scope.member.name);
        fd.append("address", $scope.member.address);
        fd.append("age", $scope.member.age);
        $http.post(url_save, fd, {
            withCredentials: true,
            headers: {'Content-Type': undefined },
            transformRequest: angular.identity
        }).then(function successCallback(response) {
            console.log(response.data);
            toastr.success('Member create Success.', 'Success Alert', {timeOut: 5000});
            $scope.member={};
            angular.element(document.querySelector('#modalCreate')).modal('hide');
            $('#image').val('');
            $scope.refresh();
        }, function errorCallback(response) {
            $scope.member={};
            $('#image').val('');
            angular.element(document.querySelector('#modalCreate')).modal('hide');
            toastr.warning('Member create Fail.', 'Success Alert', {timeOut: 5000});
            $scope.refresh();
        });

    }
    // update data in form
    $scope.update = function (modalstate, id) {
        var url_save = API_URL + "member/" +id +"/update";
        var file = $scope.editMember.image;
        if ($scope.editMember.newImage != null) {
            file = $scope.editMember.newImage;
        }
        var fd = new FormData();
        fd.append('image', file);
        fd.append("name", $scope.editMember.name);
        fd.append("address", $scope.editMember.address);
        fd.append("age", $scope.editMember.age);
        $http.post(url_save, fd, {
            withCredentials: true,
            headers: {'Content-Type': undefined },
            transformRequest: angular.identity
        }).then(function successCallback(response) {
            console.log(response.data);
            toastr.success('Member Update Success.', 'Success Alert', {timeOut: 5000});
            $scope.editMember={};
            $('#image').val('');
            angular.element(document.querySelector('#modalEdit')).modal('hide');
            $scope.refresh();
        }, function errorCallback(response) {
            //console.log(response);
            toastr.success('Member Update Fail.', 'Success Alert', {timeOut: 5000});
            $scope.editMember={};
            
        });

    }


    // delete member
    $scope.deleteMember=function(id){
    var isConfirmDelete = confirm('Are you sure you want this record?');
    if (isConfirmDelete) {
        $http({
        method:'POST',
        url: API_URL + 'member/' + id + '/destroy',
        data:$.param($scope.deleteMember),
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (response) {
            // console.log(response);
            $scope.refresh();
            $('#deleteModal').modal('hide');
            toastr.warning('You have been deleted a member.', 'Success Alert', {timeOut: 5000});
          }, function (response) {
            // console.log(response);
          });
        }
    }
    
});