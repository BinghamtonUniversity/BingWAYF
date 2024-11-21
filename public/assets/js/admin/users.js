app.get('/api/users',function(all_users) {
    gdg = new GrapheneDataGrid({
        el:'#adminDataGrid',
        name: 'users',
        search: false,columns: false,upload:false,download:false,title:'Users',
        entries:[],
        actions:app.data.actions,
        count:20,
        schema:[
            {label: 'ID', name:'id', edit:false},
            {label:'First Name', name: 'first_name'},
            {label:'Last Name', name: 'last_name'},
            {label:'Email', name: 'email'},
            {label: 'Admin', name: 'admin',type:'switch',options:[{label:'Normal User',value:false},{label:'Administrator',value:true}]},
        ], 
        data: _.reverse(all_users)
    }).on("model:view_idps",function(grid_event) {
        window.location = '/admin/users/'+grid_event.model.attributes.id+'/idps';
    }).on("model:manage_applications",function(grid_event) {
        window.location = '/admin/users/'+grid_event.model.attributes.id+'/applications';
    }).on("model:manage_groups",function(grid_event) {
        window.location = '/admin/users/'+grid_event.model.attributes.id+'/groups';
    }).on("model:edited",function(grid_event) {
        app.put('/api/users/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:created",function(grid_event) {
        app.post('/api/users',grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        app.delete('/api/users/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    }).on("model:impersonate",function(grid_event) {
        app.post('/api/users/'+grid_event.model.attributes.id+'/impersonate',{},function(data) {
            window.location = '/';
        },function(data) {});
    });
});