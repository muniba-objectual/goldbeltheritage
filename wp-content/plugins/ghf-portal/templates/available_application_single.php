
<div class="application-single">
    <div class="application-icon-image">
        <?php if( ! $isActive ){ ?>
        <span class="form-status-box">Paused</span>
        <?php } ?>
        <img src="<?php echo GHF_IMAGES; ?>/file-icon-left.png" alt="">
        <span class="form-date-box"><?php echo date('m-d-Y',strtotime($form_date)); ?></span>
    </div>
    <div class="application-desc">
        <h4><?php echo $form_name; ?><span style="float:right;font-size:12px;margin-top: 2%;"></span> </h4>
        <!-- <p><?php echo $form_desc; ?></p> -->
        <?php echo $form_url; ?>
    </div>
</div>