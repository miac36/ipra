<div style="width: 100%; height: 100vh;">
    <div id="main" style="width: 100%; height: 100%;"></div>
</div>
<?= $toolbar_cfg ?>
<script type="text/javascript">
    var config = {
        layout: {
            name: 'layout',
            padding: 0,
            margin: 10,
            panels: [
                {type: 'top', size: '34', resizable: false},
                {type: 'main', minSize: 300}
            ]
        },

        ipra_edit: {
            name: 'ipra_edit',
            header: 'Редактирование',
            formURL: '/ajax/lpuipraeditform/<?=$typeid?>',
            recid: <?=(!empty($id))?$id:''?>,
            url: '/ajax/lpuipraeditformrecord',
            fields: [
                {field: 'typeid', type: 'select'},
                {field: 'evntid', type: 'select'},
                {field: 'dicid', type: 'select'},
                {field: 'name', type: 'text'},
                {field: 'dt_exc', type: 'date', options: {format: 'yyyy-mm-dd'}},
                {field: 'resid', type: 'radio'},
                {field: 'par1', type: 'text'},
                {field: 'par2', type: 'text'},
                {field: 'par3', type: 'text'},
                {field: 'result', type: 'text'},
                {field: 'note', type: 'text'}

            ],
            actions: {
                abort: function () {
                    history.back();
                },
                save: function () {
                    var obj = this;
                   if(!this.record.approved) {
                       this.save({}, function (data) {
                           if (data.status == 'error') {
                               console.log('ERROR: ' + data.message);
                               return;
                           }
                           history.back();
                       });
                   }else{
                       w2alert('ЗАПРЕЩЕНО: запись утверждена.');
                   }
                }
            }
        }
    };
    $('#main').w2layout(config.layout);
    w2ui.layout.content('top', $().w2toolbar(toolbar_cfg));
    w2ui.layout.content('main', $().w2form(config.ipra_edit));
</script>
