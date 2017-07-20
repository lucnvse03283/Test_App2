
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
    $scope.editMember=[];
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
    
    
    //show form create and edit
    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.member.name="";
                $scope.member.address="";
                $scope.member.age="";
                $scope.member.image="";
                $('#image').val('');
                
                $scope.formMember.$setPristine();
                $("#modalCreate input textarea").val("");
                $('#modalCreate').modal('show');          
                $scope.form_title = "Add New Member";
                break;
            case 'edit':
                $scope.editMember.newImage ="";
                $('#modalEdit').modal('show');
                $('#newImage').val('');
                $scope.form_title = "Member Infomation";
                $scope.id = id;
                
                $http({
                    method: 'GET',
                    url: API_URL + 'member/' +id
                }).then(function successCallback(response) {
                    console.log(response.data);
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
        if ($scope.formMember.$valid) {
            var url_save = API_URL + "member/store";
            var file = $scope.member.image;
        
            var fd = new FormData();
            fd.append("image", file);
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
                console.log(response);
                $scope.member={};
                $('#image').val('');
                angular.element(document.querySelector('#modalCreate')).modal('hide');
                toastr.warning('Member create Fail.', 'Warning Alert', {timeOut: 5000});
                $scope.refresh();
            });
        } else {
            $scope.isEmptyName = $scope.formMember.name.$error.required;
            $scope.isEmptyAddress = $scope.formMember.address.$error.required;
            $scope.isEmptyAge = $scope.formMember.age.$error.required;
        }

        
    }

    // update data in form
    $scope.update = function (modalstate, id) {
        if ($scope.frmMembers.$valid) {
            var url_save = API_URL + "member/" +id +"/update";
            var file = $scope.editMember.image;

            if ($scope.editMember.newImage != null) {
                file = $scope.editMember.newImage;
            } 
            var fd = new FormData();
            fd.append("image", file);
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
                toastr.warning('Member Update Fail.', 'Warning Alert', {timeOut: 5000});
                $scope.editMember={};
                
            });
        } else {
            $scope.isEmptyName = $scope.frmMembers.name.$error.required;
            $scope.isEmptyAddress = $scope.frmMembers.address.$error.required;
            $scope.isEmptyAge = $scope.frmMembers.age.$error.required;
        }
        

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
                toastr.warning('You have been deleted a member.', 'Warning Alert', {timeOut: 5000});
            }, function (response) {
                // console.log(response);
            });
        }
    }
    
});