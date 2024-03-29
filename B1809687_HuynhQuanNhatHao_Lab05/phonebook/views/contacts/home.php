<?php $this->layout("layouts/default", ["title" => APPNAME]) ?>

<?php $this->start("page_specific_css") ?>
    <link href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">
<?php $this->stop() ?>

<?php $this->start("page") ?>

<!-- Kiểm tra session alert thêm contact -->
<?php
    if (!empty($_SESSION['alert'])) {
        $message = $_SESSION['alert'];
        echo '<script>alert("'. $message .'");</script>';
        $_SESSION['alert'] = '';
    }
?>

<div class="container">
    <section id="inner" class="inner-section section">
            <!-- SECTION HEADING -->
            <h2 class="section-heading text-center wow fadeIn" data-wow-duration="1s">Contacts</h2>
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <p class="wow fadeIn" data-wow-duration="2s">View your all contacs here.</p>
                </div>
            </div>

            <div class="inner-wrapper row">
                <div class="col-md-12">
                
                    <!-- FLASH MESSAGES HERE -->

                    <a href="/contacts/add" class="btn btn-primary" style="margin-bottom: 30px;">
                        <i class="fa fa-plus"></i> New Contact</a>

                    <!-- Table Starts Here -->
                    <table id="contacts" class="table table-bordered table-responsive table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Date Created</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($contacts as $contact): ?>
                            <tr>
                                <td><?=$this->e($contact->name)?></td>
                                <td><?=$this->e($contact->phone)?></td>
                                <td><?=$this->e(date("d-m-Y", strtotime($contact->created_at)))?></td>
                                <td><?=$this->e($contact->notes)?></td>
                                <td><a href="/contacts/edit/<?=$this->e($contact->id)?>" 
                                class="btn btn-xs btn-warning">
                                <i alt="Edit" class="fa fa-pencil"> Edit</i></a>
                                <form class="delete" action="/contacts/delete/<?=$this->e($contact->id)?>" 
                                        method="POST" style="display: inline;">
                                    <input type="hidden" name="_csrf_token" value="<?=\App\Csrf::getToken()?>">
                                    <button type="submit" class="btn btn-xs btn-danger" name="delete-contact">
           	                            <i alt="Delete" class="fa fa-trash"> Delete</i>
                                    </button>
                                </form></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                    <!-- Table Ends Here -->
                    <div id="delete-confirm" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close"
                                        data-dismiss="modal">&times;
                                    </button>
                                    <h4 class="modal-title">Confirmation</h4>
                                </div> 
                                <div class="modal-body">Do you want to delete this contact?</div>
                                <div class="modal-footer">
                                    <button type="button" data-dismiss="modal" class="btn btn-danger" id="delete">
                                        Delete
                                    </button>
                                    <button type="button" data-dismiss="modal" class="btn btn-default">
                                        Cancel
                                    </button>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
<?php $this->stop() ?>

<?php $this->start("page_specific_js") ?>
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  
    <script>
        $(document).ready(function(){
            new WOW().init();
            $('button[name="delete-contact"]').on('click', function(e){
                var $form=$(this).closest('form');
                e.preventDefault();
                $('#delete-confirm').modal({ backdrop: 'static', keyboard: false })
                .one('click', '#delete', function() {
                    $form.trigger('submit');
                });
            }); 
        });
    </script>
<?php $this->stop() ?>
