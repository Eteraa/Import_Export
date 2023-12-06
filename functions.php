<?php
 include "db_conn.php";
 if(isset($_POST["Import"])){
    
    if(!empty($_FILES['file']['name'])){
        
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            fgetcsv($csvFile);
            
            while(($line = fgetcsv($csvFile)) !== FALSE){

                $id   = $line[0];
                $p_name  = $line[1];
                $p_price  = $line[2];
                $p_description = $line[3];
                $create_date = $line[4];
                $update_date = $line[5];
                
                $query1 = "SELECT * FROM products WHERE id = '".$id."'";
                $result1 = $conn->query($query1);
                
                
                if($result1->num_rows > 0){
                    $row1 = $result1->fetch_assoc();
                    if ($row1['p_name'] != $p_name || $row1['p_price'] != $p_price || $row1['p_description'] != $p_description) {
                         $conn->query("UPDATE products SET p_name = '".$p_name."', p_price = '".$p_price."', p_description = '".$p_description."', update_date = NOW() WHERE id = '".$id."'");
                    }
                    else {
                        echo "";
                    }
                }else{
                    $conn->query("INSERT INTO products (id, p_name, p_price, p_description, create_date, update_date) VALUES ('".$id."', '".$p_name."', '".$p_price."','".$p_description."', NOW(), NOW())");
                }
            }

            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
        
    }
    header("Location: index.php".$qstring);
 }    
    else{
        $qstring = '?status=invalid_file';
    
    }   
  
  if(isset($_POST["Export"])){
     
    header('Content-Type: text/txt; charset=utf-8');  
    header('Content-Disposition: attachment; filename=data.csv');  
    $output = fopen("php://output", "w");  
    fputcsv($output, array('ID', 'P_Name', 'Price', 'P-Description'));  
    $query = "SELECT * from products ORDER BY id DESC";  
    $result = mysqli_query($conn, $query);  
    while($row = mysqli_fetch_assoc($result))  
    {  
         fputcsv($output, $row);  
    }  
    fclose($output);  
}     
 ?>