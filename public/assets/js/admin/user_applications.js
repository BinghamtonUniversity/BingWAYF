app.get('/api/users/'+app.data.id+'/applications',function(all_user_applications) {
    gdg = new GrapheneDataGrid({
        el:'#adminDataGrid',
        name: 'user_applications',
        search: false,columns: false,upload:false,download:false,title:'User Applications',
        entries:[],
        actions:app.data.actions,
        count:20,
        schema:[
            {label: 'ID', name:'id',type:'hidden'},
            {label: 'User ID', name:'user_idp',type:'hidden'},
            {label: 'Application', name:'application_id',type:'select',options:'/api/applications',format:{label:"{{name}}", value:"{{id}}"}},
            {label: 'Approved', name: 'approved',type:'switch',options:[{label:'Not Approved',value:false},{label:'Approved',value:true}]},
            {label: 'Last Login', name:'last_login',edit:false},
        ], 
        data: _.reverse(all_user_applications)
    }).on("model:edited",function(grid_event) {
        app.put('/api/users/'+app.data.id+'/applications/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:created",function(grid_event) {
        app.post('/api/users/'+app.data.id+'/applications',grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        app.delete('/api/users/'+app.data.id+'/applications/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    });
})
