<style type="text/css">
    .x-panel-header-default {
        background-color: #50647b;
        border: 1px solid #50647b;
    }
    .x-panel-body-default {
        border-color: #50647b;
    }
</style>
<div class="content-view">
  <div class="container">
    <div class="card">
        <div class="card-block m-t-2">
            <!-- <table id="example" class="display table table-bordered datatable m-t-2" style="width:100%">
                <thead>
                    <tr>
                        <th>Building Name</th>
                        <th>Unit ID</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table> -->
            <div id="javascriptrender"></div>
        </div>
    </div>
  </div>
</div>
<script>
    var role = "<?php echo $user_info['role']?>";
    var building_id = "<?php echo $user_info['building_id']?>";
</script>