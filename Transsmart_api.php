/*
  Class to test the creation of a shipment with Transsmart
  Copyright Victor Huisman 2017
 */

<?php
class Transsmart{
  var $user="transsmart@XXX.com";
  var $pass="PASSS";
  var $url="https://connect.test.api.transwise.eu/Api/";
  
  function do_http_request($function,$type,$parameters,$postdata=null)
  {
    /* send the API call to Transsmart
     */

    if (count($parameters)>0)
      $workurl=$this->url.$function."?".http_build_query($parameters);
    else
      $workurl=$this->url.$function;
    
    if ($type="post")
      // use key 'http' even if you send the request to https://...
      $options = array(
		       'http' => array(
				       //'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
				       'header'  => "Authorization: Basic " . base64_encode($this->user.":".$this->pass),
				       'method'  => 'POST',
				       'content' => $postdata
				     )
		       );
    else
      $options = array(
		       'http' => array(
				       //'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				       'header'=> "Authorization: Basic " . base64_encode($this->user.":".$this->pass),
				       'method'  => 'GET',
				       )
		       );
    
    $context  = stream_context_create($options);
    $result = file_get_contents($workurl, false, $context);
    var_dump($result);
    if ($result === FALSE) 
      return "error";
    else
      return $result;
  } 
  
  
  
  
  function createDocument()
  { 
    /* a document is needed to send a shipment to transsmart.
       this function sends a test document
    */

    $post='{
    "Reference": "1",
    "AddressName": "Mr. Test",
    "AddressStreet": "Saturnusstraat",
    "AddressStreetNo": "60",
    "AddressZipcode": "2615AH",
    "AddressCity": "Den haag",
    "AddressCountry": "NL",
    "DeliveryNoteInfo": [
      {
        "DeliveryNoteId": "20160425-001",
        "Currency": "EUR",
        "Price": 200,
        "DeliveryNoteInfoLines": [
          {
            "ArticleId": "A1000",
            "Currency": "EUR",
            "Description": "Item 1",
            "Price": 100,
            "Quantity": 2,
            "QuantityBackorder": 4,
            "QuantityOrder": 6,
            "SerialNumber": "S1222432"
          }
        ]
      }
    ],
    "ColliInformation": [
      {
        "PackagingType": "BOX",
        "Description": "Description",
        "Quantity": 1,
        "Length": 31,
        "Width": 30,
        "Height": 30,
        "Weight": 5
      }
    ]
}';
    
    echo $this->do_http_request("Document","POST",array('autobook'=>'0','autolabel'=>'0','label_user'=>'','qzhost'=>'','zplprinter'=>'','laserprinter'=>'' ),$post); 
    
  }
  
}

$t=new Transsmart();
$t->createDocument();

?>