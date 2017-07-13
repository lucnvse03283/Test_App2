 
 <!DOCTYPE html>
<html lang="en" ng-app="memberRecords">
<head>
    <meta charset="utf-8">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
    <script src="https://code.angularjs.org/1.6.4/angular-route.js"></script>      
    
    <script src="{{ asset('/app/app.js') }}"></script>

    <script src="{{ asset('/app/orderBy.js') }}"></script>
    <script src="{{ asset('app/controllers/membersController.js') }}"></script>

    <script src="https://unpkg.com/angular-toastr/dist/angular-toastr.tpls.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/angular-toastr/dist/angular-toastr.css" />
</head>
  
    <body>

        <!-- Show table infomation member -->
        
        <div  ng-controller="membersController" class="top">
            <div class="row">
                <div class="col-xs-3 col-md-5"></div>
                <div class="col-xs-3 col-md-5"><h2>Member Infomation</h2></div>
            </div>
            <div class="row">
                <div class="col-xs-2 col-md-2"></div>
                <div class="col-xs-8 col-md-8">
                    <button id="btn-add" class="btn btn-primary " ng-click="toggle('add', 0)">Add New Member</button>
                </div>
            </div>
            <div class="row">
            <!-- Table-to-load-the-data Part -->
            
            <div class="col-xs-2 col-md-2"></div>
            <div class="col-xs-8 col-md-8">
                <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th href="#" ng-click="sortType = 'id'">ID</th>
                            <th href="#" ng-click="sortType = 'name'">
                            Name
                            <span ng-show="sortType == 'name'" class="fa fa-caret-down"></span>
                            </th>
                            <th href="#" ng-click="sortType = 'address'">
                            Address
                            <span ng-show="sortType == 'address'" class="fa fa-caret-down"></span>
                            </th>
                            <th href="#" ng-click="sortType = 'age'">Age</th>
                            <span ng-show="sortType == 'age'" class="fa fa-caret-down"></span>
                            <th>Photo</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="member in members | orderBy:sortType:sortReverse">
                            <td>@{{ member.id }}</td>
                            <td>@{{ member.name }}</td>
                            <td>@{{ member.address }}</td>
                            <td>@{{ member.age }}</td>
                            <td><img src="../public/images/@{{member.image}}"/></td>
                            <td>
                                <button class="btn btn-default btn-md btn-detail" ng-click="toggle('edit', member.id)">Edit</button>
                                <button class="btn btn-danger btn-md btn-delete" ng-click="deleteMember(member.id)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
            <!-- Modal Create member -->
            <div class="modal fade" id = "modalCreate" tabindex="1" role= "dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <div class="col-xs-5 col-md-4"></div>
                            <h4 class="modal-title" id="myModalLabel">@{{form_title}}</h4>
                        </div>

                        <div class="modal-body">
                            <form name = "formMember" class="form-horizontal" novalidate="" method="POST" role = "form" enctype="multipart/form-data">
                                <div class="form-group error">
                                    <label for="inputEmail3">Name</label>
                                    <div>
                                        <input type="text" class="form-control has-error" id="name" name="name" placeholder="Enter Name" 
                                        ng-model="member.name" ng-required="true" ng-maxlength=100>
                                        <span class="help-inline" 
                                        ng-show="formMember.name.$error.required && formMember.name.$touched && formMember.name.$dirty">Name field is required</span>
                                        <span class="help-inline" 
                                        ng-show="formMember.name.$dirty && formMember.name.$error.maxlength">Name field is maximum 100 characters</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for ="address">Address</label>
                                    <textarea class="form-control" name = "address" rows="2" placeholder="Enter Address" ng-model= "member.address" ng-required="true" ng-maxlength=300></textarea>
                                    <span class="help-inline" 
                                        ng-show="formMember.address.$error.required && formMember.address.$touched && formMember.address.$dirty">Address field is required
                                    </span>
                                    <span class="help-inline" 
                                        ng-show="formMember.address.$dirty && formMember.address.$error.maxlength">Address field is maximum 300 characters
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="age">Age</label>
                                    <input type="text" class="form-control" id = "age" name="age" placeholder="Enter Age" ng-model="member.age" ng-required="true" ng-pattern="/^\d{0,9}(\.\d{1,9})?$/" ng-maxlength=2>
                                    <span class="help-inline" 
                                        ng-show="formMember.age.$error.required && formMember.age.$touched && formMember.age.$dirty">Age field is required
                                    </span>
                                    <span class="help-inline" 
                                        ng-show="formMember.age.$dirty && formMember.age.$error.maxlength">Age field is maximum 2 digits
                                    </span>
                                    <span class="help-inline" 
                                        ng-show="formMember.age.$dirty && formMember.age.$error.pattern">Age must be a number
                                    </span>
                                </div>
                                <div>
                                    <label for="image">Photo</label>
                                    <input type="file" name="image" id = "image" accept="image/x-png,image/gif,image/jpeg" data-max-size="10240" file-model="member.image" ng-class="{' has-error': formMember.image.$dirty && formMember.image.$invalid} " accept="image/*" onchange="angular.element(this).scope().uploadImageUpdate(this.files)" >
                                     <span class="help-inline validate-photo-update" ></span>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-create" name="btn-create" ng-click="save(modalstate, id)" ng-disabled="formMember.$invalid" >Save changes</button>
                        </div>
                    </div>
                    
                </div>
                
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id = "modalEdit" tabindex="1" role= "dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <div class="col-xs-5 col-md-4"></div>
                            <h4 class="modal-title" id="myModalLabel">@{{form_title}}</h4>
                        </div>

                        <div class="modal-body">
                            <form name = "frmMembers" class="form-horizontal" novalidate="" method="POST" role = "form" enctype="multipart/form-data">
                                <div class="form-group error">
                                    <label for="inputEmail3">Name</label>
                                    <div>
                                        <input type="text" class="form-control has-error" id="name" name="name" placeholder="Enter Name" 
                                        ng-model="editMember.name" ng-required="true" ng-maxlength = 100>
                                        <span class="help-inline" 
                                        ng-show="frmMembers.name.$invalid && frmMembers.name.$touched">Name field is required</span>
                                        <span class="help-block" 
                                        ng-show="frmMembers.name.$dirty && frmMembers.name.$error.maxlength">Name field is maximum 100 characters</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for ="address">Address</label>
                                    <textarea class="form-control" name = "address" rows="2" placeholder="Enter Address" ng-model= "editMember.address" ng-maxlength = 300></textarea>
                                    <span class="help-inline" 
                                        ng-show="frmMembers.address.$invalid && frmMembers.address.$touched">Address field is required</span>
                                        <span class="help-inline" 
                                        ng-show="frmMembers.address.$dirty && frmMembers.address.$error.maxlength">Address field is maximum 300 characters</span>
                                </div>
                                <div class="form-group">
                                    <label for="age">Age</label>
                                    <input type="text" class="form-control" id = "age" name="age" placeholder="Enter Age" ng-model="editMember.age" ng-required="true" ng-pattern="/^\d{0,9}(\.\d{1,9})?$/" ng-maxlength=2>
                                    <span class="help-inline" 
                                        ng-show="frmMembers.age.$error.required && frmMembers.age.$touched && frmMembers.age.$dirty">Age field is required
                                    </span>
                                    <span class="help-inline" 
                                        ng-show="frmMembers.age.$dirty && frmMembers.age.$error.maxlength">Age field is maximum 2 digits
                                    </span>
                                    <span class="help-inline" 
                                        ng-show="frmMembers.age.$dirty && frmMembers.age.$error.pattern">Age must be a number
                                    </span>
                                </div>
                                <div class="form-group">
                                  <label class="control-label">Old Photo</label>
                                  <img src="../public/images/@{{editMember.image}}" ng-hide="editMember.image == null||editMember.image == ''" class="img-reponsive" width="150px">
                                </div>
                                <div class="form-group">
                                  <label class="control-label">New Photo</label>
                                  <input type="file" file-model="editMember.newImage" id="newImage" accept="image/*"   name="image"  ng-class="{' has-error': frmMembers.image.$dirty && frmMembers.image.$invalid} " accept="image/*" onchange="angular.element(this).scope().uploadImageUpdate(this.files)" >
                                    <span class="help-inline validate-photo-update" ></span>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" name = "btn-save" id="btn-save" ng-click="update(modalstate, id)" ng-disabled="frmMembers.$invalid" >Save changes</button>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            
            <!-- Delete Member -->
            <div class="modal fade " id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-danger" ng-click="deleteMember(idDelete)"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                </div>
              </div>
            </div>
          </div>


    </body>
</html>