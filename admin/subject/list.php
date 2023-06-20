<?php 

    include('../admin_enrollment_header.php');
    // $createUrl = directoryPath . "create.php";
    $templateUrl = directoryPath . "template.php";

    // echo "im in subject enroll";

    if(!AdminUser::IsAuthenticated()){
        // header("Location: /dcbt/adminLogin.php");
        // exit();
    }
?>


<div class="row col-md-12">
            <div class="col-md-10 offset-md-1">
            <h3 class="text-center">Subject List</h3>
            <div class="row justify-content-end">
                <a class="mb-2" href="<?php echo $templateUrl?>">
                    <button class="btn btn btn-success">Add New</button>
                </a>    
            </div>
            <table  class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                <thead>
                    <tr class="text-center"> 
                        <th rowspan="2">Id</th>
                        <th rowspan="2">Code</th>
                        <th rowspan="2">Description</th>
                        <th rowspan="2">Pre Requisite</th>
                        <th rowspan="2">Type</th>  
                        <th rowspan="2">Unit</th>
                        <th rowspan="2">Action</th>
                    </tr>	
                </thead> 	
                <tbody>
                    <?php 

                        $query = $con->query("SELECT * FROM subject_template
                            WHERE course_level=0
                            ");

                        $query->execute();

                        if($query->rowCount() > 0){
                        
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {


                                $url = "template_edit.php?id=".$row['subject_template_id']."";
                                echo "
                                    <tr class='text-center'>
                                        <td>".$row['subject_template_id']."</td>
                                        <td>".$row['subject_code']."</td>
                                        <td>".$row['subject_title']."</td>
                                        <td>".$row['pre_requisite_title']."</td>
                                        <td>".$row['subject_type']."</td>
                                        <td>".$row['unit']."</td>
                                        <td>
                                            <a href='$url'>
                                                <button class='btn btn-sm btn-primary'>
                                                    <i class='fas fa-edit'></i>
                                                </button> 
                                            </a>

                                        </td>
                                    </tr>
                                ";
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
</div>