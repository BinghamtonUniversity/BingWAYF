app.get('/api/idps',function(all_idps) {
    gdg = new GrapheneDataGrid({
        el:'#adminDataGrid',
        name: 'idps',
        search: false,columns: false,upload:false,download:false,title:'IDPs',
        entries:[],
        actions:app.data.actions,
        count:20,
        schema:[
            {label: 'ID', name:'id', edit:false},
            {label:'Name', name: 'name'},
            {label:'Entity ID', name: 'entityId'},
            {label:'SSO Login URL', name: 'singleSignOnServiceUrl'},
            // {label:'SSO Logout URL', name: 'singleLogoutServiceUrl'},
            {label:'Enabled', name: 'enabled',type:'switch',options:[{name:'Disabled',value:false},{name:'Enabled',value:true}],template:"{{#attributes.enabled}}Enabled{{/attributes.enabled}}{{^attributes.enabled}}Disabled{{/attributes.enabled}}"},
            {label:'Debug', name: 'debug',type:'switch',options:[{name:'Disabled',value:false},{name:'Enabled',value:true}],template:"{{#attributes.debug}}Debug Enabled{{/attributes.debug}}{{^attributes.debug}}Debug Disabled{{/attributes.debug}}"},
            {label:'Order', name: 'order'},
        ], 
        data: _.reverse(all_idps)
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
})
