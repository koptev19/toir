<?php  $this->view("components/tabs"); ?>
<div class="tab-content border border-top-0">
    <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="services-tab">
        <div class="d-flex align-items-stretch">
            <div class="p-4 border-end" style="width:280px;">
                <h5 class="">Службы</h5>
                <div id='servicesContent' class='my-4 pb-3'>
                    <table class='table table-hover table-sm table-borderless'>
                    <?php foreach($services as $service) { ?>
                        <tr>
                            <td><a href="service.php?ACTION=edit&ID=<?php echo $service->ID; ?>"><?php echo $service->NAME; ?></a></td>
                        </tr>
                    <?php } ?>
                    </table>            
                </div>
                <a href='service.php?ACTION=create'>Добавить службу</a>
            </div>
            <div class="p-4 w-100">
