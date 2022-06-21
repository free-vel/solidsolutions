let treeModule = {
    'closedClass' : 'glyphicon-chevron-down',
    'openedClass' : 'glyphicon-chevron-right',
    openTree: function(e){
        let branch = $(e);
        if(branch.find('i:first').hasClass(treeModule.closedClass)){
            branch.find('i:first').click();
        }
    },
    addBranch: function(e, name, path){
        let branch = $(e);
        if(branch.has('ul').length === 0) {
            branch.append('<ul></ul>');
            treeModule.setOpenIcon(branch);
        }
        treeModule.openTree(branch);
        branch.find('ul:first').append('<li data-path="'+path+'"><a href="#">'+name+'</a></li>');
        treeModule.setPlusMinusIcons(branch.find('ul:first').find('li:last'));
        treeModule.setUpdateForTitle(branch.find('ul:first').find('li:last'));
    },
    removeBranch: function(e){
        let branch = $(e);
        let root_branch = branch.parent().parent();
        branch.remove();
        if(root_branch.find('ul:first').find('li').length === 0){
            root_branch.find('ul:first').remove();
            root_branch.find('i:first').remove();
        }
        if($('#tree_div').find('ul:first').length === 0){
            $('#create_root').show();
        }
    },
    renameBranch: function(e, name){
        let branch = $(e);
        branch.find('a:first').html(name);
    },
    setPlusMinusIcons: function(e) {
        let branch = $(e);
        branch.find('a:first').after(
        "<i class='indicator glyphicon glyphicon-plus-sign'></i><i class='indicator glyphicon glyphicon-minus-sign'></i>"
        );
        branch.find('i.glyphicon-plus-sign').on('click', function (e) {
        if (this == e.target) {
            controlModule.addPrepare($(this).parent());
        }
        });
        branch.find('i.glyphicon-minus-sign').on('click', function (e) {
            if (this == e.target) {
                controlModule.deletePrepare($(this).parent());
            }
        });
    },
    setOpenIcon: function(e){
        let branch = $(e);
        if(branch.has("ul").length) {
            branch.prepend("<i class='indicator glyphicon " + treeModule.closedClass + "'></i>");
            branch.addClass('branch');
            branch.find('i:first').on('click', function (e) {
                if (this == e.target) {
                    let icon = $(this);
                    icon.toggleClass(treeModule.openedClass + " " + treeModule.closedClass);
                    $(this).parent().children().children().toggle();
                }
            });
            branch.children().children().toggle();
        }
    },
    setUpdateForTitle: function(e){
        $(e).find('a:first').on('click', function () {
            controlModule.updatePrepare($(this).parent());
        });
    },
    init: function (e) {
        //initialize each of the top levels
        let tree = $(e);
        tree.addClass("tree");
        //set icons and onclick events
        tree.find('li').each(function () {
            treeModule.setOpenIcon($(this));
            treeModule.setPlusMinusIcons($(this));
            treeModule.setUpdateForTitle($(this));
        });


        //fire event from the dynamically added icon
        tree.find('.branch .indicator').each(function(){
            $(this).on('click', function () {
                $(this).closest('li').click();
            });
        });
    }
}
let popupModule = {
    init: function () {
        $('.show_popup').on('click', function () {
            popupModule.show($(this).attr('rel'));
        });
        $('.close').on('click', function () {
            popupModule.close();
            if(timerModule.timerId !== null){
                clearInterval(timerModule.timerId);
            }
        });
        $('.save_button').on('click', function () {
            let act = new Function("return "+$(this).data("action")+"($(this));");
            popupModule.close();
            popupModule.overlay();
            act();
        });

    },
    show: function (popup_id) {
        let popUp = $('#'+popup_id);
        popUp.css('margin-top', (-1/2)*(popUp.height())+'px');
        popUp.css('margin-left', -(popUp.width()/2)+'px');
        popUp.show();
        popupModule.overlay();
    },
    overlay: function () {
        $('.overlay_popup').toggle();
    },
    close: function () {
        $('.popup').hide();
        popupModule.overlay();
    }
}
let controlModule = {
    'branchTrigged': null,
    create_root: function (e) {
        let form = $('#modal-title').find('form:first');
        form.find('input[name=title]').val('root');
        $(e).hide();
        $('#tree_div').html('<ul class="tree"><li data-path="1"><a href="#">root</a></li><ul>');
        treeModule.init($('.tree'));
        controlModule.branchTrigged = $('#tree_div').find('li:first');
        popupModule.overlay();
        controlModule.addRoot();
    },
    addRoot: function () {
        $.post( "index.php", { action: "ajax", type: "addRoot", path: 1, title: "root"})
            .done(function( data ) {
                data = JSON.parse(data);
                if(data.error != 0){
                    console.log(data.text);
                    popupModule.overlay();
                    return;
                }
                popupModule.overlay();
            });
    },
    add: function () {
        let parent_path = $(controlModule.branchTrigged).data('path');
        let form = $('#modal-title').find('form:first');
        let text = form.find('input[name=title]').val();
        text = controlModule.validateTitle(text);
        if(text === ''){
            console.log('title error');
            popupModule.overlay();
            return;
        }
        $.post( "index.php", { action: "ajax", type: "add", path: parent_path, title: text})
            .done(function( data ) {
                data = JSON.parse(data);
                if(data.error != 0){
                    console.log(data.text);
                    popupModule.overlay();
                    return;
                }
                if(data.path != 1){
                    treeModule.addBranch(controlModule.branchTrigged, data.text, data.path);
                }
                popupModule.overlay();
            });
    },
    delete: function () {
        let parent_path = $(controlModule.branchTrigged).data('path');
        $.post( "index.php", { action: "ajax", type: "delete", path: parent_path})
            .done(function( data ) {
                data = JSON.parse(data);
                if(data.error != 0){
                    console.log(data.text);
                    popupModule.overlay();
                    return;
                }
                treeModule.removeBranch(controlModule.branchTrigged);
                popupModule.overlay();
            });
    },
    update: function () {
        let parent_path = $(controlModule.branchTrigged).data('path');
        let form = $('#modal-title').find('form:first');
        let text = form.find('input[name=title]').val();
        text = controlModule.validateTitle(text);
        if(text === ''){
            console.log('title error');
            popupModule.overlay();
            return;
        }
        $.post( "index.php", { action: "ajax", type: "update", path: parent_path, title: text})
            .done(function( data ) {
                data = JSON.parse(data);
                if(data.error != 0){
                    console.log(data.text);
                    popupModule.overlay();
                    return;
                }
                treeModule.renameBranch(controlModule.branchTrigged, data.text);
                popupModule.overlay();
            });
    },
    addPrepare: function (e) {
        controlModule.branchTrigged = e;
        $('#modal-title').find('button.save_button').data('action', 'controlModule.add');
        $('#modal-title').find('input[name=title]').val('');
        popupModule.show('modal-title');
    },
    deletePrepare: function (e) {
        let timerDiv = $('#modal-delete div.counter-down');
        controlModule.branchTrigged = e;
        if($(e).data('path') == 1){
            popupModule.show('modal-delete');
            timerModule.startCountdown(timerDiv);
        }else{
            popupModule.overlay();
            controlModule.delete();
        }
    },
    updatePrepare: function (e) {
        controlModule.branchTrigged = e;
        $('#modal-title').find('button.save_button').data('action', 'controlModule.update');
        $('#modal-title').find('input[name=title]').val($(e).find('a:first').html());
        popupModule.show('modal-title');
    },
    validateTitle: function (text) {
        return text;
    },
}
let timerModule = {
    'timerId': null,
    'timerStart': 20,
    startCountdown: function(e){
        $(e).text(timerModule.timerStart);
        timerModule.timerId = setInterval(() => {
            let val = +$(e).text() - 1;
            $(e).text(val);
            if(val == 0){
                timerModule.stopCountdown();
            }
        }, 1000);
    },
    stopCountdown: function() {
        clearInterval(timerModule.timerId);
        popupModule.close();
        popupModule.overlay();
        controlModule.delete();
    }
}

