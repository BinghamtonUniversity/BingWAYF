var group_form_fields = [
    {type:"hidden", name:"id"},
    {type:"text", name:"name", label:"Name",required:true},
    {type:"text", name:"slug", label:"Slug",required:true},
    {type:"textarea", name:"description", label:"Description",required:false},
];

app.get('/api/groups',function(data) {
    data = data.reverse();
    gdg = new GrapheneDataGrid({el:'#adminDataGrid',
    name: 'groups',
    search: false,columns: false,upload:false,download:false,title:'Groups',
    entries:[],
    sortBy: 'order',
    actions:app.data.actions,
    count:20,
    schema:group_form_fields, 
    data: data
    }).on("model:edited",function(grid_event) {
        app.put('/api/groups/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:created",function(grid_event) {
        app.post('/api/groups',grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        if (confirm('Are you sure? This will delete all membership associated with this group. This action can not be undone!')) {
            app.delete('/api/groups/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
                grid_event.model.undo();
            });
        } else {
            grid_event.model.undo();
        }
    }).on("model:manage_users",function(grid_event) {
        window.location = '/admin/groups/'+grid_event.model.attributes.id+'/users';
    });
});