<?php
include_once "utils/utils.php";
class Item {

    private $itemID;
    private $itemName;
    private $itemDescription;
    private $itemImageName;
    private $itemPrice;
    private $itemHidden;

    
    //getters

    function getItemID() : int
    {
        return $this->itemID;
    }

    function getItemName() : string 
    {
        return $this->itemName;
    }

    function getItemDescription() : string 
    {
        return $this->itemDescription;
    }

    function getItemImageName() : string 
    {
        return $this->itemImageName ?? "default.jpg";
    }

    function getItemPrice() : float 
    {
        return $this->itemPrice;
    } 

    function getItemHidden() : bool 
    {
        return $this->itemHidden;
    }

    public function getImage(int $size = 500) : string //returns an echoable <img> tag of the resized image
    {
        return Utility::ResizeImage("img/" . $this->getItemImageName(), $size);
    }

}
?>