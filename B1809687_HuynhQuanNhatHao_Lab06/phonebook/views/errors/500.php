<?php $this->layout("layouts/default", ["title" => APPNAME]) ?>

<?php $this->start("page_specific_css") ?>
    <style>
        .error-template {padding: 40px 15px;text-align: center;}
        .error-actions {margin-top:15px;margin-bottom:15px;}
        .error-actions .btn { margin-right:10px; }    
    </style>
<?php $this->stop() ?>

<?php $this->start("page") ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>Internal Server Error <small><font face="Tahoma" color="red">Error 500</font></small></h1>
                <div class="error-details">
                    Sorry, an error has occured. Please try again later.
                </div>
                <div class="error-actions">
                    <a href="/" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>
                        Take Me Home </a>
                </div>
            </div>         
            <?php if (isset($trace)): ?>
                <div class="well"><h2>The application could not run because of the following error:</h2><?=$trace?></div>                          
            <?php endif ?>                
        </div>
    </div>
</div>
<?php $this->stop() ?>
