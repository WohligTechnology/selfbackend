<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class restapi_model extends CI_Model
{

    public function getsubscribe($email){
        $query1=$this->db->query("SELECT * FROM `subscribe` WHERE `email`='$email'");
        $num=$query1->num_rows();
        if($num>0)
        {
        return 0;
        }
        else{
        $this->db->query("INSERT INTO `subscribe`(`email`) VALUE('$email')");
        $id=$this->db->insert_id();
        if($id)
        return true;
        else
        return false;
        }
    }
    public function getallcategory(){
    $query=$this->db->query("SELECT `id`, `name`, `parent`, `status`, `order`, `image1`, `image2` FROM `category` WHERE `parent`=0")->result();
        return $query;
    }
     public function gethomecontent(){
    $query=$this->db->query("SELECT `id`, `name`, `link` as `url`, `target`, `status`, `image` as `src`, `template`, `class`, `text`, `centeralign` as `centerAlign` FROM `fynx_homeslide` WHERE `status`=1")->result();
        return $query;
    }
    public function getorderbyorderid($id){
    $query=$this->db->query("SELECT `transactionid` FROM `fynx_order` WHERE `id`='$id'")->row();
    $query->amount=$this->db->query("SELECT SUM(`finalprice`) as `amount` FROM `fynx_orderitem` WHERE `order`='$id'")->row();
        return $query;
    }
    public function getUserDetails($user){
    $query=$this->db->query("SELECT `id`, `name`, `password`, `email`, `accesslevel`, `timestamp`, `status`, `image`, `username`, `socialid`, `logintype`, `json`, `firstname`, `lastname`, `phone`, `billingaddress`, `billingcity`, `billingstate`, `billingcountry`, `billingcontact`, `billingpincode`, `shippingaddress`, `shippingcity`, `shippingcountry`, `shippingstate`, `shippingpincode`, `shippingname`, `shippingcontact`, `currency`, `credit`, `companyname`, `registrationno`, `vatnumber`, `country`, `fax`, `gender`, `facebook`, `google`, `twitter`, `street`, `address`, `pincode`, `state`, `dob`, `city`, `billingline1`, `billingline2`, `shippingline1`, `shippingline2`, `billingline3`, `shippingline3` FROM `user` WHERE `id`=$user")->row();
        return $query;
    }
    public function getSubCategoryProductHome($id){
    $query['subcategorynames']=$this->db->query("SELECT `homecategoryproduct`.`id`, `homecategoryproduct`.`subcategory`,`subcategory`.`name` FROM `homecategoryproduct` LEFT OUTER JOIN `subcategory` ON `subcategory`.`id`=`homecategoryproduct`.`subcategory` GROUP BY `homecategoryproduct`.`subcategory`")->result();
        $query1=$this->db->query("SELECT `product` FROM `homecategoryproduct` WHERE `subcategory`=$id")->result();
        $productids="(";
            foreach($query1 as $key=>$value){
//            $catid=$row->id;
                if($key==0)
                {
                    $productids.=$value->product;
                }
                else
                {
                    $productids.=",".$value->product;
                }
            }
            $productids.=")";
        if($productids=="()"){
        $productids="(0)";
        }
        $query['product']=$this->db->query("SELECT `id`, `name`, `sku`, `description`, `url`, `visibility`, `price`, `wholesaleprice`, `firstsaleprice`, `secondsaleprice`, `specialpriceto`, `specialpricefrom`, `metatitle`, `metadesc`, `metakeyword`, `quantity`, `status`, `modelnumber`, `brandcolor`, `eanorupc`, `eanorupcmeasuringunits`, `type`, `compatibledevice`, `compatiblewith`, `material`, `color`, `design`, `width`, `height`, `depth`, `portsize`, `packof`, `salespackage`, `keyfeatures`, `videourl`, `modelname`, `finish`, `weight`, `domesticwarranty`, `domesticwarrantymeasuringunits`, `internationalwarranty`, `internationalwarrantymeasuringunits`, `warrantysummary`, `warrantyservicetype`, `coveredinwarranty`, `notcoveredinwarranty`, `size`, `typename`, `subcategory`, `category` FROM `product` WHERE `id` IN $productids")->result();
        return $query;
    }

    public function getFilters($catid)
    {
       $query['color']=$this->db->query("SELECT DISTINCT  `fynx_color`.`id`,`fynx_color`.`name` FROM `fynx_product`
       LEFT OUTER JOIN `fynx_color` ON `fynx_color`.`id`=`fynx_product`.`color`
       WHERE `fynx_product`.`category`='$catid'")->result();

        $query['type']=$this->db->query("SELECT DISTINCT  `fynx_type`.`id`,`fynx_type`.`name` FROM `fynx_product`
       LEFT OUTER JOIN `fynx_type` ON `fynx_type`.`id`=`fynx_product`.`type`
       WHERE `fynx_product`.`category`='$catid' AND `fynx_type`.`status`=2")->result();

        $query['size']=$this->db->query("SELECT DISTINCT  `fynx_size`.`id`,`fynx_size`.`name` FROM `fynx_product`
       LEFT OUTER JOIN `fynx_size` ON `fynx_size`.`id`=`fynx_product`.`size`
       WHERE `fynx_product`.`category`='$catid' AND `fynx_size`.`status`=2")->result();

        $query['subcategory']=$this->db->query("SELECT DISTINCT  `fynx_subcategory`.`id`,`fynx_subcategory`.`name` FROM `fynx_product`
       LEFT OUTER JOIN `fynx_subcategory` ON `fynx_subcategory`.`id`=`fynx_product`.`subcategory`
       WHERE `fynx_product`.`category`='$catid' AND `fynx_subcategory`.`status`=2 AND `fynx_subcategory`.`category`='$catid'")->result();

      return $query;
    }
    public function removeFromWishlist($user, $product,$design){
        $query=$this->db->query(" DELETE FROM `fynx_wishlist` WHERE `user`='$user' AND `product`='$product' AND `design`='$design'");
        if($query){
        return 1;
        }
        else{
        return false;
        }
    }
    public function getAllSize(){
        $query=$this->db->query("SELECT `id`, `status`, `name` FROM `fynx_size` WHERE 1")->result();
        return $query;

    }

		public function getFiltersLater ($query) {
			$return = new stdClass();

			$query2 = " SELECT `id` FROM ($query) as `tab1` ";
//echo " SELECT DISTINCT `fynx_color`.`id`,`fynx_color`.`name` FROM `fynx_product` INNER JOIN `fynx_color` ON `fynx_product`.`color` = `fynx_color`.`id` WHERE `fynx_product`.`id` = '$query2' ";
			$return->color = $this->db->query(" SELECT DISTINCT `fynx_color`.`id`,`fynx_color`.`name` FROM `fynx_product` INNER JOIN `fynx_color` ON `fynx_product`.`color` = `fynx_color`.`id` WHERE `fynx_product`.`id` IN ($query2) ")->result();
			$return->size = $this->db->query(" SELECT DISTINCT `fynx_size`.`id`,`fynx_size`.`name` FROM `fynx_product` INNER JOIN `fynx_size` ON `fynx_product`.`size` = `fynx_size`.`id` WHERE `fynx_product`.`id` IN ($query2) ")->result();
			$return->subcategory = $this->db->query(" SELECT DISTINCT `fynx_subcategory`.`name`,`fynx_subcategory`.`id`,`fynx_subcategory`.`image1` FROM `fynx_product` INNER JOIN `fynx_subcategory` ON `fynx_product`.`subcategory` = `fynx_subcategory`.`id` WHERE `fynx_product`.`id` IN ($query2) " )->result();
			foreach($return->subcategory  as $sub)
			{
				$sub->types = $this->db->query(" SELECT DISTINCT `fynx_type`.`name`,`fynx_type`.`id` FROM `fynx_product` INNER JOIN `fynx_type` ON `fynx_product`.`type` = `fynx_type`.`id` WHERE `fynx_product`.`id` IN ($query2) AND `fynx_type`.`subcategory`= '$sub->id' ")->result();
			}
			return $return;
		}
    public function updateProfile($user,$name,$email,$phone,$billingline1,$billingline2,$billingline3,$billingcity,$billingstate,$billingcountry,$billingpincode,$shippingline1,$shippingline2,$shippingline3,$shippingcity,$shippingstate,$shippingpincode,$shippingcountry){
        $data  = array(
			'name' => $name,
			'email' => $email,

			'phone' => $phone,

			'billingcity' => $billingcity,
			'billingstate' => $billingstate,
			'billingcountry' => $billingcountry,
			'billingpincode' => $billingpincode,
			'shippingcity' => $shippingcity,
			'shippingstate' => $shippingstate,
			'shippingcountry' => $shippingcountry,
			'shippingpincode' => $shippingpincode,
            'billingline1' => $billingline1,
            'billingline2' => $billingline2,
            'billingline3' => $billingline3,
            'shippingline1' => $shippingline1,
            'shippingline2' => $shippingline2,
            'shippingline3' => $shippingline3
		);

		$this->db->where( 'id', $user );
		$query=$this->db->update( 'user', $data );

        $useridquery = $this->db->query("SELECT `id`, `name`,`email`, `phone`, `billingaddress`, `billingcity`, `billingstate`, `billingcountry`, `billingcontact`, `billingpincode`, `shippingaddress`, `shippingcity`, `shippingcountry`, `shippingstate`, `shippingpincode`, `shippingname`, `shippingcontact`,  `billingline1`, `billingline2`, `shippingline1`, `shippingline2`, `billingline3`, `shippingline3` FROM `user` WHERE `id`='$user'")->row();
        return $useridquery;
    }
     public function changepassword($id, $oldpassword, $newpassword, $confirmpassword) {
        $oldpassword = md5($oldpassword);
        $newpassword = md5($newpassword);
        $confirmpassword = md5($confirmpassword);
        if ($newpassword == $confirmpassword) {
            $useridquery = $this->db->query("SELECT `id` FROM `user` WHERE `password`='$oldpassword'");
            if ($useridquery->num_rows() == 0) {
                return 0;
            } else {
                $query = $useridquery->row();
                $userid = $query->id;
                $updatequery = $this->db->query("UPDATE `user` SET `password`='$newpassword' WHERE `id`='$userid'");
                return 1;
            }
        } else {
//            echo "New password and confirm password do not match!!!";
			return -1;
        }
    }
     public function checkstatus($orderid){
      $query=$this->db->query("SELECT * FROM `fynx_order` WHERE `id`='$orderid'")->row();
        $orderstatus=$query->orderstatus;
        if($orderstatus==1){
        return 1;
        }
        else
        {
        return 0;
        }
    }
		public function updateorderstatusafterpayment($orderid,$transactionid,$responsecode,$amount)
        {
            if($responsecode==0)
            {
             $checkamt=$this->db->query("SELECT IFNULL(SUM(`price`),0) as `totalamount` FROM `fynx_orderitem` WHERE `order`='$orderid'")->row();
                $totalamount=$checkamt->totalamount;
                if($totalamount==$amount){
                    $query1=$this->db->query("UPDATE `fynx_order` SET `orderstatus`=2,`transactionid`='$transactionid' WHERE `id`='$orderid'");
             // DESTROY CART
                    $getuser=$this->db->query("SELECT `user` FROM `fynx_order` WHERE `id`='$orderid'")->row();
                    $user=$getuser->user;
                    $this->cart->destroy();
                    $deletecart=$this->db->query("DELETE FROM `fynx_cart` WHERE `user`='$user'");
            redirect("http://www.myfynx.com/testing/#/thankyou/".$orderid);
                }
                else{
                      $query=$this->db->query("UPDATE `fynx_order` SET `orderstatus`=5,`transactionid`='$transactionid' WHERE `id`='$orderid'");
            redirect("http://www.myfynx.com/testing/#/sorry/".$orderid);
                }
                
            
            }
            else
            {
            $query=$this->db->query("UPDATE `fynx_order` SET `orderstatus`=5,`transactionid`='$transactionid' WHERE `id`='$orderid'");
            redirect("http://www.myfynx.com/testing/#/sorry/".$orderid);
            }
}
      public function checkproductquantity($prodid){
         $query=$this->db->query("SELECT `quantity` FROM `fynx_product` WHERE `id`='$prodid'")->row();
         $quantity=$query->quantity;
        return $quantity;
    }
}
?>
