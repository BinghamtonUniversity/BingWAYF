app.get('/api/applications/'+app.data.id+'/users',function(all_application_users) {
    gdg = new GrapheneDataGrid({
        el:'#adminDataGrid',
        name: 'application_users',
        search: false,columns: false,upload:false,download:false,title:'User Applications',
        entries:[],
        actions:app.data.actions,
        count:20,
        schema:[
            {label: 'ID', name:'id',type:'hidden'},
            {label: 'Application', name:'application_id',type:'hidden',value:app.data.id},
            {label: 'User', name:'user_id',type:'user',template:"{{attributes.user.first_name}} {{attributes.user.last_name}}"},
            {label: 'Approved', name: 'approved',type:'switch',options:[{label:'Not Approved',value:false},{label:'Approved',value:true}]},
            {label: 'Admin Permissions', name: 'admin',type:'switch',options:[{label:'Normal User',value:false},{label:'Application Administrator',value:true}]},
            {label: 'Last Login', name:'last_login',edit:false, visible:false},
        ], 
        data: _.reverse(all_application_users)
    }).on("model:edited",function(grid_event) {
        app.put('/api/users/'+grid_event.model.attributes.user_id+'/applications/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:created",function(grid_event) {
        app.post('/api/users/'+grid_event.model.attributes.user_id+'/applications',grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        app.delete('/api/users/'+grid_event.model.attributes.user_id+'/applications/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    });
})
