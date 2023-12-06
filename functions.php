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
                
                $prevQuery = "SELECT id FROM products WHERE id = '".$line[0]."'";
                $prevResult = $conn->query($prevQuery);
                
                
                if($prevResult->num_rows > 0){
                    $conn->query("UPDATE products SET p_name = '".$p_name."', p_price = '".$p_price."', p_description = '".$p_description."' WHERE id = '".$id."'");
                }else{
                    $conn->query("INSERT INTO products (id, p_name, p_price, p_description) VALUES ('".$id."', '".$p_name."', '".$p_price."','".$p_description."')");
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