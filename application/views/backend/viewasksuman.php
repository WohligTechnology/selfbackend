<div class="row">
<div class="col s12">
<div class="row">
<div class="col s12 drawchintantable">
<?php $this->chintantable->createsearch("Ask Suman");?>
<table class="highlight responsive-table">
<thead>
<tr>
<th data-field="id">ID</th>
<th data-field="category">Category</th>
<th data-field="name">Name</th>
<th data-field="email">Email Id</th>


</tr>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>
<?php $this->chintantable->createpagination();?>
</div>
</div>
<script>
function drawtable(resultrow) {
     if (resultrow.status == 1) {
                resultrow.status = "Disable";
            } else if (resultrow.status == 2) {
                resultrow.status = "Enable";
            }
return "<tr><td>" + resultrow.id + "</td><td>" + resultrow.category + "</td><td>" + resultrow.name + "</td><td>" + resultrow.email + "</td><td><a class='btn btn-primary btn-xs waves-effect waves-light blue darken-4 z-depth-0 less-pad' href='<?php echo site_url('site/editasksuman?id=');?>"+resultrow.id+"'><i class='fa fa-pencil propericon'></i></a></td></tr>";
}
generatejquery("<?php echo $base_url;?>");
</script>
