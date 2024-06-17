gdg = new GrapheneDataGrid({
    el:'#adminDataGrid',
    name: 'users',
    search: false,columns: false,upload:false,download:false,title:'Users',
    entries:[],
    actions:app.data.actions,
    count:20,
    schema:[
        {label: 'ID', name:'id', type:'hidden'},
        {label:'First Name', name: 'first_name'},
        {label:'Last Name', name: 'last_name'},
        {label:'Email', name: 'email'},
    ], 
    data: app.data.records
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
});
