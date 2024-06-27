app.get('/api/users/'+app.data.id+'/idps',function(all_user_idps) {
    all_user_idps = _.map(all_user_idps,function(item) {
        item.attributes = _.map(item.attributes,function(value,key) {
            return {key:key,value:value};
        });
        return item;
    })
    gdg = new GrapheneDataGrid({
        el:'#adminDataGrid',
        name: 'user_idps',
        search: false,columns: false,upload:false,download:false,title:'User IDPs',
        entries:[],
        actions:app.data.actions,
        count:20,
        schema:[
            {label: 'ID', name:'id', type:'hidden'},
            {label: 'IDP', name:'idp_id',type:'select',options:'/api/idps',format:{label:"{{name}}", value:"{{id}}"}},
            {label: 'Unique ID', name:'unique_id'},
            {label: 'Attributes', name:'attributes',template:"{{#attributes.attributes}}<b>{{key}}:</b> {{value}}<br>{{/attributes.attributes}}"},
            {label: 'Last Login', name:'last_login'},
        ], 
        data: _.reverse(all_user_idps)
    })
})
