app.get('/api/users/'+app.data.id+'/groups',function(data) {
    gdg = new GrapheneDataGrid({el:'#adminDataGrid',
    name: 'users_groups',
    search: false,columns: false,upload:false,download:false,title:'Identities',
    entries:[],
    actions:app.data.actions,
    count:20,
    schema:[
        {type:"hidden", name:"id"},
        {name:"group_id","label":"Group",type:"select",options:"/api/groups",format:{label:"{{name}}", value:"{{id}}"}},
    ], data: data
    }).on("model:created",function(grid_event) {
        toastr.info('Processing... Please Wait')
        app.post('/api/users/'+app.data.id+'/groups/',grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        toastr.info('Processing... Please Wait')
        app.delete('/api/users/'+app.data.id+'/groups/'+grid_event.model.attributes.group_id,{},
            function(data) {},
            function(data) {
            grid_event.model.undo();
        });
    })
});

