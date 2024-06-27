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
            {label: 'User', name:'user_id',type:'select',options:'/api/users',format:{label:"{{first_name}} {{last_name}}", value:"{{id}}"}},
            {label: 'Approved', name: 'approved',type:'switch',options:[{label:'Not Approved',value:false},{label:'Approved',value:true}]},
            {label: 'Last Login', name:'last_login',edit:false},
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
