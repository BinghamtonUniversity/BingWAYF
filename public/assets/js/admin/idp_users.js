app.get('/api/idps/'+app.data.id+'/users',function(all_idp_users) {
    all_idp_users = _.map(all_idp_users,function(item) {
        item.attributes = _.map(item.attributes,function(value,key) {
            return {key:key,value:value};
        });
        return item;
    })
    gdg = new GrapheneDataGrid({
        el:'#adminDataGrid',
        name: 'idp_users',
        search: false,columns: false,upload:false,download:false,title:'IDP Users',
        entries:[],
        actions:app.data.actions,
        count:20,
        schema:[
            {label: 'ID', name:'id', type:'hidden'},
            {label: 'IDP', name:'idp_id',type:'hidden'},
            {label: 'User', name:'user_id',type:'select',options:'/api/users',format:{label:"{{first_name}} {{last_name}}", value:"{{id}}"}},
            {label: 'Unique ID', name:'unique_id'},
            {label: 'Attributes', name:'attributes',template:"{{#attributes.attributes}}<b>{{key}}:</b> {{value}}<br>{{/attributes.attributes}}"},
            {label: 'Last Login', name:'last_login'},
        ], 
        data: _.reverse(all_idp_users)
    });
})
