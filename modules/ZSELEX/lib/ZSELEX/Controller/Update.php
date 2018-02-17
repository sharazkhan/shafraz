<?php

class ZSELEX_Controller_Update extends ZSELEX_Controller_Base_Admin {

    function updateProfileFields() {
        $cityExist = $this->countField('ship_city');
        if (!$cityExist) {
            // echo "exists"; exit;
            $validation = 'a:5:{s:8:"required";s:1:"0";s:6:"viewby";s:1:"0";s:11:"displaytype";s:1:"0";s:11:"listoptions";s:0:"";s:4:"note";s:0:"";}';
            $insert = $this->insertProfileField('city', '1', $validation, 'ship_city');
        }
        $zipExist = $this->countField('ship_zipcode');
        if (!$zipExist) {
            $validation = 'a:5:{s:8:"required";s:1:"0";s:6:"viewby";s:1:"0";s:11:"displaytype";s:1:"0";s:11:"listoptions";s:0:"";s:4:"note";s:0:"";}';
            $insert = $this->insertProfileField('zipcode', '1', $validation, 'ship_zipcode');
        }
        $fnameExist = $this->countField('ship_first_name');
        if (!$fnameExist) {
            $validation = 'a:5:{s:8:"required";s:1:"0";s:6:"viewby";s:1:"0";s:11:"displaytype";s:1:"0";s:11:"listoptions";s:0:"";s:4:"note";s:0:"";}';
            $insert = $this->insertProfileField('First Name', '1', $validation, 'ship_first_name');
        }
        $lnameExist = $this->countField('ship_last_name');
        if (!$lnameExist) {
            $validation = 'a:5:{s:8:"required";s:1:"0";s:6:"viewby";s:1:"0";s:11:"displaytype";s:1:"0";s:11:"listoptions";s:0:"";s:4:"note";s:0:"";}';
            $insert = $this->insertProfileField('Last Name', '1', $validation, 'ship_last_name');
        }

        $addrExist = $this->countField('ship_address');
        if (!$addrExist) {
            $validation = 'a:5:{s:8:"required";s:1:"0";s:6:"viewby";s:1:"0";s:11:"displaytype";s:1:"0";s:11:"listoptions";s:0:"";s:4:"note";s:0:"";}';
            $insert = $this->insertProfileField('Last Name', '1', $validation, 'ship_address');
        }

        $addrExist = $this->countField('ship_phone');
        if (!$addrExist) {
            $validation = 'a:5:{s:8:"required";s:1:"0";s:6:"viewby";s:1:"0";s:11:"displaytype";s:1:"0";s:11:"listoptions";s:0:"";s:4:"note";s:0:"";}';
            $insert = $this->insertProfileField('phone', '1', $validation, 'ship_phone');
        }
        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    }

    function countField($attribute) {
        $statement = Doctrine_Manager::getInstance()->connection();
        $sql = "SELECT COUNT(*) as count FROM user_property 
                WHERE attributename='" . $attribute . "'";
        $query = $statement->execute($sql);
        $result = $query->fetch();
        return $result;
    }

    function insertProfileField($label, $dtype = 1, $validation, $attributename) {
        $sql = "INSERT INTO user_property (label , dtype , validation , attributename) VALUES('" . $label . "' , '1' , '" . $validation . "' , '" . $attributename . "')";
        $query = $statement->execute($sql);
        return $query;
    }

    function analyzeTables() {
        // $sqls[] = "ANALYZE TABLE zselex_advertise";
        // $sqls[] = "ANALYZE TABLE zselex_advertise";
        $statement = Doctrine_Manager::getInstance()->connection();
        $sql = "SHOW TABLES";
        $query = $statement->execute($sql);
        $result = $query->fetchAll();
        // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        foreach ($result as $key => $value) {
            // echo $value[0] . '<br>';
            $table = $value [0];
            $sql = "ANALYZE TABLE $table";
            $query = $statement->execute($sql);
        }
        // exit;
        return $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    }

    function updateProductImageDeimensions() {
        $products = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getAll(array(
            'entity' => 'ZSELEX_Entity_Product',
            'fields'=>array('a.prd_image' , 'b.shop_id' , 'c.user_id'),
            'joins'=>array('LEFT JOIN a.shop b' , 'LEFT JOIN b.shop_owners c')
        ));
        
        echo "<pre>"; print_r($products);  echo "</pre>"; exit;
    }

}

?>