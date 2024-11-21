app.get('/api/groups/'+app.data.id+'/users',function(data) {
    data = data.reverse();
    gdg = new GrapheneDataGrid({el:'#adminDataGrid',
    name: 'groups_users',
    search: false,columns: false,upload:false,download:false,title:'Identities',
    entries:[],
    actions:app.data.actions,
    count:20,
    schema:[
        {type:"hidden", name:"id"},
        {label: 'User', name:'user_id',type:'user',template:"{{attributes.user.first_name}} {{attributes.user.last_name}}"},
    ], data: data
}).on("model:created",function(grid_event) {
    toastr.info('Processing... Please Wait')
    app.post('/api/users/'+grid_event.model.attributes.user_id+'/groups/',{group_id:app.data.id},function(data) {
        grid_event.model.update(data)
    },function(data) {
        grid_event.model.undo();
    });
}).on("model:deleted",function(grid_event) {
    toastr.info('Processing... Please Wait')
    app.delete('/api/users/'+grid_event.model.attributes.user_id+'/groups/'+app.data.id,{},
        function(data) {},
        function(data) {
        grid_event.model.undo();
    });
}).on('click',function(event) {
        window.location = '/users/'+event.model.attributes.id
    });
});

// Built-In Events:
//'edit','model:edit','model:edited','model:create','model:created','model:delete','model:deleted'


