gdg = new GrapheneDataGrid({
    el:'#adminDataGrid',
    name: 'idps',
    search: false,columns: false,upload:false,download:false,title:'IDPs',
    entries:[],
    actions:app.data.actions,
    count:20,
    schema:[
        {label: 'ID', name:'id', type:'hidden'},
        {label:'Entity ID', name: 'entityId'},
        {label:'SSO Login URL', name: 'singleSignOnServiceUrl'},
        {label:'SSO Logout URL', name: 'singleLogoutServiceUrl'},
        {label:'x509 Certificate', name: 'x509cert'},
        {label:'Enabled', name: 'enabled'},
        {label:'Debug', name: 'debug'},
    ], 
    data: app.data.records
}).on("model:edited",function(grid_event) {
    app.put('/api/idps/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
        grid_event.model.update(data)
    },function(data) {
        grid_event.model.undo();
    });
}).on("model:created",function(grid_event) {
    app.post('/api/idps',grid_event.model.attributes,function(data) {
        grid_event.model.update(data)
    },function(data) {
        grid_event.model.undo();
    });
}).on("model:deleted",function(grid_event) {
    app.delete('/api/idps/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
        grid_event.model.undo();
    });
});
