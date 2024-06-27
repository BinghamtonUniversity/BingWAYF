app.get('/api/oauth_clients',function(all_clients) {
    gdg = new GrapheneDataGrid({
        el:'#adminDataGrid',
        name: 'oauth_clients',
        search: false,columns: false,upload:false,download:false,title:'OAuth Clients',
        entries:[],
        actions:app.data.actions,
        count:20,
        schema:[
            {label: 'ID', name:'id', edit:false},
            {label:'Name', name: 'name'},
            {label:'Secret', name: 'secret',edit:false},
            {label:'Redirect', name: 'redirect'},
        ], 
        data: _.reverse(all_clients)
    }).on("model:edited",function(grid_event) {
        app.put('/api/oauth_clients/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:created",function(grid_event) {
        app.post('/api/oauth_clients',grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        app.delete('/api/oauth_clients/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    });
})
