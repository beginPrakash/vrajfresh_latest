

<div class="row">
   <div class="col-sm-3">
      <label>Promotional Code : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['promotional_code'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Description: </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['description'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Valid From: </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php if(isset($ArrFieldData['start_from'])){ echo date('d-m-Y',strtotime($ArrFieldData['start_from'])); } ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label>Valid To: </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php if(isset($ArrFieldData['valid_upto'])){ echo date('d-m-Y',strtotime($ArrFieldData['valid_upto'])); } ?></label>
   </div>
</div>
<!--
<div class="row">
   <div class="col-sm-3">
      <label>Promotional Type: </label>
   </div>
   <?php $promotional_type = '';
    if($ArrFieldData['promotional_type'] == 'S'){
        $promotional_type = 'Single';
    }elseif($ArrFieldData['promotional_type'] == 'M'){
        $promotional_type = 'Multiple';
    }elseif($ArrFieldData['promotional_type'] == 'OT'){
        $promotional_type = 'Only One Time';
    }
    ?>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $promotional_type ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label>Valid To: </label>
   </div>
   <div class="col-sm-9">
   <?php
    $apply_to = '';
    if($ArrFieldData['apply_to'] == 'A'){
        $apply_to = 'All Customer';
    }elseif($ArrFieldData['apply_to'] == 'SC'){
        $apply_to = 'Specific Customer';
    }elseif($ArrFieldData['apply_to'] == 'SG'){
        $apply_to = 'Specific Group';
    }
   ?>
      <label  style="font-weight:400; line-height: 20px;"><?php echo $apply_to ?></label>
   </div>
</div>-->

<div class="row">
   <div class="col-sm-3">
      <label>Apply For: </label>
   </div>
   <div class="col-sm-9">
   <?php
    $apply_to_product = '';
    if($ArrFieldData['apply_to_product'] == 'A'){
        $apply_to_product = 'All Products';
    }elseif($ArrFieldData['apply_to_product'] == 'SP'){
        $apply_to_product = 'Specific Products';
    }
   ?>
      <label  style="font-weight:400; line-height: 20px;"><?php echo $apply_to_product ?></label>
   </div>
</div>


<div class="row">
   <div class="col-sm-3">
      <label>Is Active : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo ($ArrFieldData['is_active'] == '1')?'Yes':'No'; ?></label>
   </div>
</div>