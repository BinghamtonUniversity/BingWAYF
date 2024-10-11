app.get('/api/applications',function(all_applications) {
    gdg = new GrapheneDataGrid({
        el:'#adminDataGrid',
        name: 'applications',
        search: false,columns: false,upload:false,download:false,title:'Applications',
        entries:[],
        actions:app.data.actions,
        count:20,
        schema:[
            {label: 'ID', name:'id', type:'hidden'},
            {label:'Name', name: 'name'},
            {label:'URL', name: 'url'},
            {label:'Logo', name: 'logo'},
            {label:'Description', name: 'description'},
            {label:'Auth Type', name: 'auth_type',type:'select',options:[{label:'OpenId',value:'openid'},{label:'OAuth',value:'oauth'},{label:'CAS',value:'cas'},{label:'SAML2',value:'saml2'}]},
            {label:'Auth Client ID', name: 'auth_client_id',edit:false},
            {label:'Public', name: 'public',type:'switch',options:[{name:'Private',value:false},{name:'Public',value:true}],template:"{{#attributes.public}}Public{{/attributes.public}}{{^attributes.public}}Private{{/attributes.public}}"},
        ], 
        data: _.reverse(all_applications)
    }).on("model:manage_users",function(grid_event) {
        window.location = '/admin/applications/'+grid_event.model.attributes.id+'/users';
    }).on("model:edited",function(grid_event) {
        app.put('/api/applications/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:created",function(grid_event) {
        app.post('/api/applications',grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        app.delete('/api/applications/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    });
})
