<?php

     function arrayRefVals($array) {
          $refVals = array();
          foreach($array as $key => $value) {
               $refVals[$key] = &$array[$key];
          }   
          return $refVals;
     }   

     function numSqlLines($numLines, $sqlLine) {
          $sql = " "; 
          for($i=0; $i<$numLines; $i++) {
               $sql .= " " . $sqlLine . " ";
          }   
          return $sql;
     }   

     function numType($numTypes, $type) {
          $types = ""; 
          for($i=0; $i<$numTypes; $i++) {
               $types .= $type;
          }   
          return $types;
     }   

     function bindNumParams($stmt, $types, $params) {
          if(!(call_user_func_array(array($stmt, 'bind_param'), array_merge(array($types), arrayRefVals($params))))) {
               echo "<p>Bind failed: (" . $stmt->errno . ") " . $stmt->error . "</p>";
          }   
          return $stmt;
     }   

     function prepareStmt($mysqli, $sql) {
          if(!($stmt = $mysqli->prepare($sql))) {
               echo "<p>Prepare failed: (" . $stmt->errno . ") " . $stmt->error . "</p>";
          }   
          return $stmt;
     }   

     function executeStmt($stmt) {
          if(!$stmt->execute()) {
               echo "<p>Execute failed: (" . $stmt->connect_errno . ") " . $stmt->connect_error . "</p>";
          }   
          return $stmt;
     }

?>
