<!DOCTYPE html>
<html lang="en">
<head>
    <title>Title of the document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/tree.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script src="js/tree.js"></script>
    <script>
        $( document ).ready(function() {
            popupModule.init();
            treeModule.init($('.tree'));
            $('#create_root').on('click', function () {
                controlModule.create_root(this);
            });
        });
    </script>
    <?php if($treeHtml != ''):?>
    <script>
        $( document ).ready(function() {
            $('#create_root').hide();
        });
    </script>
    <?php endif;?>

</head>

<body>
<div class="container" style="margin-top:30px;">
    <div class="content">
        <button class="btn btn-primary" id="create_root">Create Root</button>
    </div>
    <div class="row">
        <div class="" id="tree_div">
            <?php
                echo $treeHtml;
            ?>
        </div>
    </div>

    <div class="overlay_popup"></div>
    <div class="popup" id="modal-delete">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    <input type="hidden" name="action" value="delete">
                    <div class="modal-body">
                        <p>Are you really really sure?</p>
                    </div>
                    <div class="modal-footer">
                        <div class="counter-box">
                            <i class="fa fa-group"></i>
                            <div class="counter counter-down">3275</div>
                        </div>
                        <button type="button" class="btn btn-primary save_button" data-action="timerModule.stopCountdown">DELETE</button>
                        <button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="popup" id="modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title Branch</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    <input type="hidden" name="action" value="add">
                    <div class="modal-body">
                        <p><input type="text" name="title"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary save_button" data-action="controlModule.add">Save changes</button>
                        <button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</div>

</body>

</html>



