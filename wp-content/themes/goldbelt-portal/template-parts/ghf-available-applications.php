<?php 
// echo '<pre>';
// print_r($application);
// echo '</pre>';
?>

<div class="card application-card-single mb-3">
  <div class="row g-0">
    <div class="col-md-4">
      <div class="card-img">
      <img src="<?php echo GHF_IMAGES ?>/file-icon-left.png" class="img-fluid rounded-start" alt="...">
      </div>
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <h5 class="card-title"><?php echo $application['title'] ?></h5>
        <p class="card-text"><small class="text-muted">January 20, 2020</small></p>
    </div>
    <a href="?view_form=<?php echo $application['id'] ?>" class="apply-now">Apply Now</a>
</div>
</div>
</div>