<?php foreach($this->crash->serviceRequests as $serviceRequest) { ?>
    <div class="custom-control custom-radio">
        <input type="radio" id="service-<?php echo $serviceRequest->ID; ?>" name="service_request" class="custom-control-input" value="<?php echo $serviceRequest->ID; ?>">
        <label class="custom-control-label" for="service-<?php echo $serviceRequest->ID; ?>"><?php echo $serviceRequest->service->NAME; ?></label>
    </div>
<?php } ?>
